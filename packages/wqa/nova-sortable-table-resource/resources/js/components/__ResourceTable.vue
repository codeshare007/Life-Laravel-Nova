<template>
    <table
        v-if="resources.length > 0"
        class="table w-full"
        :class="{'editMode' : editMode}"
        cellpadding="0"
        cellspacing="0"
        data-testid="resource-table"
    >
        <thead>
        <tr>
            <!-- Select Checkbox -->
            <th :class="{
                    'w-16' : shouldShowCheckboxes,
                    'w-8' : !shouldShowCheckboxes
                }">&nbsp;</th>

            <!-- Field Names -->
            <th
                v-for="field in fields"
                :class="`text-${field.textAlign} ${field.attribute}`"
            >
                <sortable-icon
                    @sort="requestOrderByChange(field)"
                    :resource-name="resourceName"
                    :uri-key="field.attribute"
                    v-if="field.sortable"
                >
                    {{ field.indexName }}
                </sortable-icon>

                <span v-else>
                        {{ field.indexName }}
                    </span>
            </th>

            <th>&nbsp;<!-- View, Edit, Delete --></th>
        </tr>
        </thead>
        <draggable
            :options="{'disabled': editMode}"
            @start="onDraggableStart"
            @update="onDraggableUpdate"
            :list="resources"
            :element="'tbody'"
            ref="draggable">
            <tr
                v-for="(resource, index) in resources"
                :data-id="resource.id.value"
                :testId="`${resourceName}-items-${index}`"
                :key="resource.id.value"
                :delete-resource="deleteResource"
                :restore-resource="restoreResource"
                is="resource-table-row"
                :resource="resource"
                :resource-name="resourceName"
                :relationship-type="relationshipType"
                :via-relationship="viaRelationship"
                :via-resource="viaResource"
                :via-resource-id="viaResourceId"
                :via-many-to-many="viaManyToMany"
                :checked="selectedResources.indexOf(resource) > -1"
                :actions-are-available="actionsAreAvailable"
                :should-show-checkboxes="shouldShowCheckboxes"
                :update-selection-status="updateSelectionStatus"
            ></tr>
        </draggable>
    </table>
</template>

<script>

    import {
      Errors,
      Deletable,
      Filterable,
      HasCards,
      Minimum,
      Paginatable,
      InteractsWithQueryString,
      InteractsWithResourceInformation,
    } from 'laravel-nova'

    import draggable from 'vuedraggable'
    import PerPageable from '../mixins/PerPageable'

    export default {
        mixins: [
          Deletable,
          Filterable,
          HasCards,
          Paginatable,
          PerPageable,
          InteractsWithResourceInformation,
          InteractsWithQueryString,
        ],

        components: {
            draggable,
        },

        props: {
            resourceName: {
                default: null,
            },
            field: {
                default: 'id',
            },
            shouldShowCheckboxes: {
                type: Boolean,
                default: false,
            },
            viaResource: {
                default: null,
            },
            viaResourceId: {
                default: null,
            },
            viaRelationship: {
                default: null,
            },
            relationshipType: {
                default: null,
            },
            // updateSelectionStatus: {
            //     type: Function,
            // },
        },

        data: () => ({
            selectAllMatching: false,
            resourceCount: null,
            options: {
                disabled: false,
            },

            initialLoading: true,
            loading: true,

            resourceResponse: null,
            resources: [],
            softDeletes: false,
            selectedResources: [],
            selectAllMatchingResources: false,
            allMatchingResourceCount: 0,

            deleteModalOpen: false,

            actions: [],
            pivotActions: null,
            actionValidationErrors: new Errors(),

            filters: [],

            authorizedToRelate: false,

            orderBy: '',
            orderByDirection: '',
            trashed: '',

            updatedFields: {},


        }),

        /**
         * Mount the component and retrieve its initial data.
         */
        async created() {
          // Bind the keydown even listener when the router is visited if this
          // component is not a relation on a Detail page
          if (!this.viaResource && !this.viaResourceId) {
            document.addEventListener('keydown', this.handleKeydown)
          }

          this.initializeEditModeFromQueryString()
          this.initializeSearchFromQueryString()
          this.initializePerPageFromQueryString()
          this.initializeTrashedFromQueryString()
          this.initializeOrderingFromQueryString()

          await this.getResources()
          await this.getAuthorizationToRelate()
          await this.getLenses()
          await this.getActions()
          await this.getFilters()

          this.initialLoading = false

          this.$watch(
            () => {
              return (
                this.resourceName +
                this.encodedFilters +
                this.currentEditMode +
                this.currentSearch +
                this.currentPage +
                this.currentPerPage +
                this.currentOrderBy +
                this.currentOrderByDirection +
                this.currentTrashed
              )
            },
            () => {
              this.getResources()

              this.initializeEditModeFromQueryString()
              this.initializeSearchFromQueryString()
              this.initializePerPageFromQueryString()
              this.initializeTrashedFromQueryString()
              this.initializeOrderingFromQueryString()
              this.initializeFilterValuesFromQueryString()
            },
          )

          // Refresh the action events
          if (this.resourceName === 'action-events') {
            Nova.$on('refresh-action-events', () => {
              this.getResources()
            })

            this.actionEventsRefresher = setInterval(() => {
              this.getResources()
            }, 15 * 1000)
          }

          this.onBatchUpdate();
        },

        /**
         * Unbind the keydown even listener when the component is destroyed
         */
        destroyed() {
          if (this.actionEventsRefresher) {
            clearInterval(this.actionEventsRefresher)
          }

          document.removeEventListener('keydown', this.handleKeydown)
        },

        methods: {
            /**
             * Delete the given resource.
             */
            deleteResource(resource) {
                this.$emit('delete', [resource])
            },

            /**
             * Restore the given resource.
             */
            restoreResource(resource) {
                this.$emit('restore', [resource])
            },

            /**
             * Broadcast that the ordering should be updated.
             */
            requestOrderByChange(field) {
                this.$emit('order', field)
            },

            async reorderResource(ev) {
                try {
                    const response = await this.reorderRequest(ev);

                    this.$toasted.show(
                        this.__('The new order has been set!'),
                        {type: 'success'}
                    );

                    //this.$router.go(this.$router.currentRoute);
                } catch (error) {
                    this.$toasted.show(
                        this.__('An error occured while trying to reorder the resource.'),
                        {type: 'error'}
                    );
                }
            },

            reorderRequest(ev) {

                let url = '/nova-vendor/wqa/nova-sortable-table-resource/';


                if (this.relationshipType === 'belongsToMany') {
                  url += this.viaResource+'/'+this.viaResourceId+'/reorder';
                } else {
                  url += this.resourceName+'/'+ev.item.dataset.id+'/reorder';
                }

                let params = {
                    positions: this.$refs.draggable._sortable.toArray(),
                    resourceName: this.resourceName,
                    viaResourceId: this.viaResourceId
                };
                return Nova.request().patch(url, params);
            },

            onDraggableStart() {
                this.currentOrder = this.$refs.draggable._sortable.toArray();
            },

            onDraggableUpdate(ev) {
                this.options.disabled = true;
                return this.reorderResource(ev);
                // axios
                //     .post(...)
                //     .then(() => ())
                //     .catch((error) => {
                //         // Revert order
                //         this.$refs.draggable._sortable.sort(this.draggableCurrentOrder);
                //     })
                //     .finally(() => this.options.disabled = false);
            },

            /**
             * Handle the keydown event
             */
            handleKeydown(e) {
              // `c`
              if (!e.ctrlKey && !e.altKey && !e.metaKey && !e.shiftKey && e.keyCode == 67) {
                this.$router.push({ name: 'create', params: { resourceName: this.resourceName } })
              }
            },

            /**
             * Select all of the available resources
             */
            selectAllResources() {
              this.selectedResources = this.resources.slice(0)
            },

            /**
             * Toggle the selection of all resources
             */
            toggleSelectAll() {
              if (this.selectAllChecked) return this.clearResourceSelections()
              this.selectAllResources()
            },

            /**
             * Toggle the selection of all matching resources in the database
             */
            toggleSelectAllMatching() {
              if (!this.selectAllMatchingResources) {
                this.selectAllResources()
                this.selectAllMatchingResources = true

                return
              }

              this.selectAllMatchingResources = false
            },

            /*
             * Update the resource selection status
             */
            updateSelectionStatus(resource) {
              if (!_(this.selectedResources).includes(resource))
                return this.selectedResources.push(resource)
              const index = this.selectedResources.indexOf(resource)
              if (index > -1) return this.selectedResources.splice(index, 1)
            },

            /**
             * Get the resources based on the current page, search, filters, etc.
             */
            getResources() {
              this.$nextTick(() => {
                this.clearResourceSelections()

                return Minimum(
                  Nova.request().get('/nova-api/' + this.resourceName, {
                    params: this.resourceRequestQueryString,
                  })
                ).then(({ data }) => {
                  this.resources = []

                  this.resourceResponse = data
                  this.resources = data.resources
                  this.softDeletes = data.softDeletes

                  this.loading = false

                  this.getAllMatchingResourceCount()
                })
              })
            },

            /**
             * Get the relatable authorization status for the resource.
             */
            getAuthorizationToRelate() {
              if (
                !this.authorizedToCreate &&
                (this.relationshipType != 'belongsToMany' && this.relationshipType != 'morphToMany')
              ) {
                return
              }

              if (!this.viaResource) {
                return (this.authorizedToRelate = true)
              }

              return Nova.request()
                .get(
                  '/nova-api/' +
                  this.resourceName +
                  '/relate-authorization' +
                  '?viaResource=' +
                  this.viaResource +
                  '&viaResourceId=' +
                  this.viaResourceId +
                  '&viaRelationship=' +
                  this.viaRelationship +
                  '&relationshipType=' +
                  this.relationshipType
                )
                .then(response => {
                  this.authorizedToRelate = response.data.authorized
                })
            },

            /**
             * Get the lenses available for the current resource.
             */
            getLenses() {
              this.lenses = []

              if (this.viaResource) {
                return
              }

              return Nova.request()
                .get('/nova-api/' + this.resourceName + '/lenses')
                .then(response => {
                  this.lenses = response.data
                })
            },

            /**
             * Get the actions available for the current resource.
             */
            getActions() {
              this.actions = []
              this.pivotActions = null
              return Nova.request()
                .get(
                  '/nova-api/' +
                  this.resourceName +
                  '/actions' +
                  '?viaResource=' +
                  this.viaResource +
                  '&viaResourceId=' +
                  this.viaResourceId +
                  '&viaRelationship=' +
                  this.viaRelationship
                )
                .then(response => {
                  this.actions = _.filter(response.data.actions, action => {
                    return !action.onlyOnDetail
                  })
                  this.pivotActions = response.data.pivotActions
                })
            },

            /**
             * Get the filters available for the current resource.
             */
            getFilters() {
              this.filters = []
              this.currentFilters = []

              return Nova.request()
                .get('/nova-api/' + this.resourceName + '/filters')
                .then(response => {
                  this.filters = response.data
                  this.initializeFilterValuesFromQueryString()
                })
            },

            /**
             * Execute a search against the resource.
             */
            performSearch(event) {
              this.debouncer(() => {
                // Only search if we're not tabbing into the field
                if (event.which != 9) {
                  this.updateQueryString({
                    [this.pageParameter]: 1,
                    [this.searchParameter]: this.search,
                  })
                }
              })
            },

            debouncer: _.debounce(callback => callback(), 500),

            /**
             * Clear the selected resouces and the "select all" states.
             */
            clearResourceSelections() {
              this.selectAllMatchingResources = false
              this.selectedResources = []
            },

            /**
             * Get the count of all of the matching resources.
             */
            getAllMatchingResourceCount() {
              if (this.resourceName == 'action-events') {
                return
              }

              Nova.request()
                .get('/nova-api/' + this.resourceName + '/count', {
                  params: this.resourceRequestQueryString,
                })
                .then(response => {
                  this.allMatchingResourceCount = response.data.count
                })
            },

            /**
             * Sort the resources by the given field.
             */
            orderByField(field) {
              var direction = this.currentOrderByDirection == 'asc' ? 'desc' : 'asc'
              if (this.currentOrderBy != field.attribute) {
                direction = 'asc'
              }
              this.updateQueryString({
                [this.orderByParameter]: field.attribute,
                [this.orderByDirectionParameter]: direction,
              })
            },

          /**
           * Sync the current search value from the query string.
           */
          initializeEditModeFromQueryString() {
            this.editMode = this.currentEditMode
          },

          /**
             * Sync the current search value from the query string.
             */
            initializeSearchFromQueryString() {
              this.search = this.currentSearch
            },

            /**
             * Sync the current order by values from the query string.
             */
            initializeOrderingFromQueryString() {
              this.orderBy = this.currentOrderBy
              this.orderByDirection = this.currentOrderByDirection
            },

            /**
             * Sync the trashed state values from the query string.
             */
            initializeTrashedFromQueryString() {
              this.trashed = this.currentTrashed
            },

            /**
             * Update the trashed constraint for the resource listing.
             */
            trashedChanged() {
              this.updateQueryString({ [this.trashedParameter]: this.trashed })
            },

            onBatchUpdate() {

              Nova.$on('onFieldsUpdated', (resource) => {
                this.updatedFields[resource.resourceId+':'+resource.fieldName] = resource.value;
              });

              Nova.bus.$on('onBatchSaveChanges', () => {
                this.batchSave();
              });

            },

            batchSave() {
              let url = '/nova-vendor/wqa/nova-sortable-table-resource/'+this.resourceName+'/batch-update';
              let params = {
                resourceName: this.resourceName,
                viaResourceId: this.viaResourceId,
                fields: this.updatedFields
              };

              return Nova.request()
                .post(url, params)
                .then(response => {
                  this.getResources()
                  this.initializeEditModeFromQueryString()
                  this.initializeSearchFromQueryString()
                  this.initializePerPageFromQueryString()
                  this.initializeTrashedFromQueryString()
                  this.initializeOrderingFromQueryString()
                  this.initializeFilterValuesFromQueryString()

                  this.$toasted.show(
                    this.__('Resource updated'),
                    {type: 'success'}
                  );
                })
            }
        },

        computed: {
            /**
             * Get all of the available fields for the resources.
             */
            fields() {
                if (this.resources) {
                    return this.resources[0].fields
                }
            },

            /**
             * Determine if the current resource listing is via a many-to-many relationship.
             */
            viaManyToMany() {
                return (
                    this.relationshipType == 'belongsToMany' || this.relationshipType == 'morphToMany'
                )
            },

            /**
             * Determine if the current resource listing is via a has-one relationship.
             */
            viaHasOne() {
                return this.relationshipType == 'hasOne' || this.relationshipType == 'morphOne'
            },

            shouldShowCards() {
              // Don't show cards if this resource is not the main one being shown (e.g. a relation)
              return this.cards.length > 0 && this.resourceName == this.$route.params.resourceName
            },

            /**
             * Get the endpoint for this resource's metrics.
             */
            cardsEndpoint() {
              return `/nova-api/${this.resourceName}/cards`
            },

            /**
             * Get the name of the filter query string variable.
             */
            editModeParameter() {
              return this.resourceName + '_edit_mode'
            },

            /**
             * Get the name of the filter query string variable.
             */
            filterParameter() {
              return this.resourceName + '_filter'
            },

            /**
             * Get the name of the search query string variable.
             */
            searchParameter() {
              return this.resourceName + '_search'
            },

            /**
             * Get the name of the order by query string variable.
             */
            orderByParameter() {
              return this.resourceName + '_order'
            },

            /**
             * Get the name of the order by direction query string variable.
             */
            orderByDirectionParameter() {
              return this.resourceName + '_direction'
            },

            /**
             * Get the name of the trashed constraint query string variable.
             */
            trashedParameter() {
              return this.resourceName + '_trashed'
            },

            /**
             * Get the name of the per page query string variable.
             */
            perPageParameter() {
              return this.resourceName + '_per_page'
            },

            /**
             * Get the name of the page query string variable.
             */
            pageParameter() {
              return this.resourceName + '_page'
            },

            /**
             * Build the resource request query string.
             */
            resourceRequestQueryString() {
              return {
                editMode: this.currentEditMode,
                search: this.currentSearch,
                filters: this.encodedFilters,
                orderBy: this.currentOrderBy,
                orderByDirection: this.currentOrderByDirection,
                perPage: this.currentPerPage,
                trashed: this.currentTrashed,
                page: this.currentPage,
                viaResource: this.viaResource,
                viaResourceId: this.viaResourceId,
                viaRelationship: this.viaRelationship,
                viaResourceRelationship: this.viaResourceRelationship,
                relationshipType: this.relationshipType,
              }
            },

            /**
             * Determine if all resources are selected.
             */
            selectAllChecked() {
              return this.selectedResources.length == this.resources.length
            },

            /**
             * Determine if all matching resources are selected.
             */
            selectAllMatchingChecked() {
              return (
                this.selectedResources.length == this.resources.length &&
                this.selectAllMatchingResources
              )
            },

            /**
             * Get the IDs for the selected resources.
             */
            selectedResourceIds() {
              return _.map(this.selectedResources, resource => resource.id.value)
            },

            /**
             * Get all of the actions available to the resource.
             */
            allActions() {
              return this.hasPivotActions
                ? this.actions.concat(this.pivotActions.actions)
                : this.actions
            },

            /**
             * Determine if the resource has any pivot actions available.
             */
            hasPivotActions() {
              return this.pivotActions && this.pivotActions.actions.length > 0
            },

            /**
             * Determine if the resource has any actions available.
             */
            actionsAreAvailable() {
              return this.allActions.length > 0
            },

            /**
             * Get the name of the pivot model for the resource.
             */
            pivotName() {
              return this.pivotActions ? this.pivotActions.name : ''
            },

            /**
             * Get the edit mode status value from the query string.
             */
            currentEditMode() {
              return this.$route.query['editMode'] || ''
            },

            /**
             * Get the current search value from the query string.
             */
            currentSearch() {
              return this.$route.query[this.searchParameter] || ''
            },

            /**
             * Get the current order by value from the query string.
             */
            currentOrderBy() {
              return this.$route.query[this.orderByParameter] || ''
            },

            /**
             * Get the current order by direction from the query string.
             */
            currentOrderByDirection() {
              return this.$route.query[this.orderByDirectionParameter] || 'desc'
            },

            /**
             * Get the current trashed constraint value from the query string.
             */
            currentTrashed() {
              return this.$route.query[this.trashedParameter] || ''
            },

            /**
             * Determine if the current resource listing is via a many-to-many relationship.
             */
            viaManyToMany() {
              return (
                this.relationshipType == 'belongsToMany' || this.relationshipType == 'morphToMany'
              )
            },

            /**
             * Determine if the resource / relationship is "full".
             */
            resourceIsFull() {
              return this.viaHasOne && this.resources.length > 0
            },

            /**
             * Determine if the current resource listing is via a has-one relationship.
             */
            viaHasOne() {
              return this.relationshipType == 'hasOne' || this.relationshipType == 'morphOne'
            },

            /**
             * Get the singular name for the resource
             */
            singularName() {
              return Capitalize(this.resourceInformation.singularLabel)
            },

            /**
             * Get the selected resources for the action selector.
             */
            selectedResourcesForActionSelector() {
              return this.selectAllMatchingChecked ? 'all' : this.selectedResourceIds
            },

            /**
             * Determine if there are any resources for the view
             */
            hasResources() {
              return Boolean(this.resources.length > 0)
            },

            /**
             * Determine if there any filters for this resource
             */
            hasFilters() {
              return Boolean(this.filters.length > 0)
            },

            /**
             * Determine if there any lenses for this resource
             */
            hasLenses() {
              return Boolean(this.lenses.length > 0)
            },

            /**
             * Determine whether to show the toolbar for this resource index
             */
            shouldShowToolbar() {
              return Boolean(
                this.shouldShowCheckBoxes || this.hasFilters || this.hasLenses || this.softDeletes
              )
            },

            /**
             * Determine whether to show the selection checkboxes for resources
             */
            shouldShowCheckBoxes() {
              return (
                Boolean(this.hasResources && !this.viaHasOne) &&
                Boolean(
                  this.actionsAreAvailable ||
                  this.authorizedToDeleteAnyResources ||
                  this.canShowDeleteMenu
                )
              )
            },

            /**
             * Determine if any selected resources may be deleted.
             */
            authorizedToDeleteSelectedResources() {
              return Boolean(_.find(this.selectedResources, resource => resource.authorizedToDelete))
            },

            /**
             * Determine if any selected resources may be force deleted.
             */
            authorizedToForceDeleteSelectedResources() {
              return Boolean(
                _.find(this.selectedResources, resource => resource.authorizedToForceDelete)
              )
            },

            /**
             * Determine if the user is authorized to delete any listed resource.
             */
            authorizedToDeleteAnyResources() {
              return (
                this.resources.length > 0 &&
                Boolean(_.find(this.resources, resource => resource.authorizedToDelete))
              )
            },

            /**
             * Determine if the user is authorized to force delete any listed resource.
             */
            authorizedToForceDeleteAnyResources() {
              return (
                this.resources.length > 0 &&
                Boolean(_.find(this.resources, resource => resource.authorizedToForceDelete))
              )
            },

            /**
             * Determine if any selected resources may be restored.
             */
            authorizedToRestoreSelectedResources() {
              return Boolean(_.find(this.selectedResources, resource => resource.authorizedToRestore))
            },

            /**
             * Determine if the user is authorized to restore any listed resource.
             */
            authorizedToRestoreAnyResources() {
              return (
                this.resources.length > 0 &&
                Boolean(_.find(this.resources, resource => resource.authorizedToRestore))
              )
            },

            /**
             * Determinw whether the delete menu should be shown to the user
             */
            shouldShowDeleteMenu() {
              return Boolean(this.selectedResources.length > 0) && this.canShowDeleteMenu
            },

            /**
             * Determine whether the user is authorized to perform actions on the delete menu
             */
            canShowDeleteMenu() {
              return Boolean(
                this.authorizedToDeleteSelectedResources ||
                this.authorizedToForceDeleteSelectedResources ||
                this.authorizedToRestoreSelectedResources ||
                this.selectAllMatchingChecked
              )
            },
        },
    }
</script>
