<template>
    <loading-view :loading="initialLoading">
        <custom-detail-header
            class="mb-3"
            :resource="resource"
            :resource-id="resourceId"
            :resource-name="resourceName"
        />

        <heading class="mb-3">{{__('Edit')}} {{ singularName }}</heading>

        <form v-if="fields" @submit.prevent="updateResource" autocomplete="off">

            <!-- Validation Errors -->
            <validation-errors :errors="validationErrors" />


            <!-- Update Button -->
            <div class="flex -mt-8 mb-6">
              <!-- <button type="button" dusk="update-and-continue-editing-button" @click="updateAndContinueEditing" class="ml-auto btn btn-default btn-primary mr-3">
                {{__('Update & Continue Editing')}}
              </button> -->

              <button dusk="update-button" class="ml-auto btn btn-default btn-primary">
                {{__('Update')}} {{ singularName }}
              </button>

              <action-selector
                      v-if="resource"
                      :resource-name="resourceName"
                      :actions="actions"
                      :pivot-actions="{ actions: [] }"
                      :selected-resources="selectedResources"
                      :query-string="{
                                currentSearch,
                                encodedFilters,
                                currentTrashed,
                                viaResource,
                                viaResourceId,
                                viaRelationship,
                            }"
                      @actionExecuted="actionExecuted"
                      class="ml-3"
              />
            </div>

            <div class="flex flex-wrap -mx-3 mb-3">
                <div
                  v-if="availableCards.length > 0"
                  v-for="card in availableCards"
                  :dusk="resourceName + '-detail-component'"
                  :class="`px-3 mb-6 w-${card.width}`"
                  :key="card.id">
                    <div class="card flex px-4 flex-col">
                      <!-- Fields -->
                      <div v-for="field in fields" v-if="field.card === card.name">
                        <component
                          @file-deleted="updateLastRetrievedAtTimestamp"
                          :is="'form-' + field.component"
                          :errors="validationErrors"
                          :resource-id="resourceId"
                          :resource-name="resourceName"
                          :field="field"
                        />
                      </div>
                    </div>
                </div>
            </div>
        </form>

        <div v-if="shouldShowCards">
            <cards
                v-if="smallCards.length > 0"
                :cards="smallCards"
                class="mb-3"
                :resource="resource"
                :resource-id="resourceId"
                :resource-name="resourceName"
                :only-on-detail="true"
            />

            <cards
                v-if="largeCards.length > 0"
                :cards="largeCards"
                size="large"
                :resource="resource"
                :resource-id="resourceId"
                :resource-name="resourceName"
                :only-on-detail="true"
            />
        </div>

        <!-- Resource Detail -->
        <div
            v-for="panel in availablePanels"
            :dusk="resourceName + '-detail-component'"
            class="mb-8"
            :key="panel.id"
        >
            <component
                v-if="! panel.name.includes('Details')"
                :is="panel.component"
                :resource-name="resourceName"
                :resource-id="resourceId"
                :resource="resource"
                :panel="panel"
            />
                <!--<div v-if="panel.showToolbar" class="flex items-center mb-3">-->
                    <!--<h4 class="text-90 font-normal text-2xl flex-no-shrink">{{ panel.name }}</h4>-->

                    <!--<div class="ml-3 w-full flex items-center">-->
                        <!--<custom-detail-toolbar-->
                            <!--:resource="resource"-->
                            <!--:resource-name="resourceName"-->
                            <!--:resource-id="resourceName"-->
                        <!--/>-->

                        <!--&lt;!&ndash; Actions &ndash;&gt;-->
                        <!--<action-selector-->
                            <!--v-if="resource"-->
                            <!--:resource-name="resourceName"-->
                            <!--:actions="actions"-->
                            <!--:pivot-actions="{ actions: [] }"-->
                            <!--:selected-resources="selectedResources"-->
                            <!--:query-string="{-->
                                <!--currentSearch,-->
                                <!--encodedFilters,-->
                                <!--currentTrashed,-->
                                <!--viaResource,-->
                                <!--viaResourceId,-->
                                <!--viaRelationship,-->
                            <!--}"-->
                            <!--@actionExecuted="actionExecuted"-->
                            <!--class="ml-3"-->
                        <!--/>-->

                        <!--<button-->
                            <!--v-if="resource.authorizedToDelete && !resource.softDeleted"-->
                            <!--data-testid="open-delete-modal"-->
                            <!--dusk="open-delete-modal-button"-->
                            <!--@click="openDeleteModal"-->
                            <!--class="btn btn-default btn-icon btn-white mr-3"-->
                            <!--title="Delete"-->
                        <!--&gt;-->
                            <!--<icon type="delete" class="text-80" />-->
                        <!--</button>-->

                        <!--<button-->
                            <!--v-if="resource.authorizedToRestore && resource.softDeleted"-->
                            <!--data-testid="open-restore-modal"-->
                            <!--dusk="open-restore-modal-button"-->
                            <!--@click="openRestoreModal"-->
                            <!--class="btn btn-default btn-icon btn-white mr-3"-->
                            <!--title="Restore"-->
                        <!--&gt;-->
                            <!--<icon type="restore" class="text-80" />-->
                        <!--</button>-->

                        <!--<button-->
                            <!--v-if="resource.authorizedToForceDelete"-->
                            <!--data-testid="open-force-delete-modal"-->
                            <!--dusk="open-force-delete-modal-button"-->
                            <!--@click="openForceDeleteModal"-->
                            <!--class="btn btn-default btn-icon btn-white mr-3"-->
                            <!--title="Force Delete"-->
                        <!--&gt;-->
                            <!--<icon type="force-delete" class="text-80" />-->
                        <!--</button>-->

                        <!--<portal to="modals">-->
                            <!--<transition name="fade">-->
                                <!--<delete-resource-modal-->
                                    <!--v-if="deleteModalOpen"-->
                                    <!--@confirm="confirmDelete"-->
                                    <!--@close="closeDeleteModal"-->
                                    <!--mode="delete"-->
                                <!--/>-->
                            <!--</transition>-->
                        <!--</portal>-->

                        <!--<portal to="modals">-->
                            <!--<transition name="fade">-->
                                <!--<restore-resource-modal-->
                                    <!--v-if="restoreModalOpen"-->
                                    <!--@confirm="confirmRestore"-->
                                    <!--@close="closeRestoreModal"-->
                                <!--/>-->
                            <!--</transition>-->
                        <!--</portal>-->

                        <!--<portal to="modals">-->
                            <!--<transition name="fade">-->
                                <!--<delete-resource-modal-->
                                    <!--v-if="forceDeleteModalOpen"-->
                                    <!--@confirm="confirmForceDelete"-->
                                    <!--@close="closeForceDeleteModal"-->
                                    <!--mode="force delete"-->
                                <!--/>-->
                            <!--</transition>-->
                        <!--</portal>-->

                        <!--<router-link-->
                            <!--v-if="resource.authorizedToUpdate"-->
                            <!--data-testid="edit-resource"-->
                            <!--dusk="edit-resource-button"-->
                            <!--:to="{ name: 'edit', params: { id: resource.id } }"-->
                            <!--class="btn btn-default btn-icon bg-primary"-->
                            <!--title="Edit"-->
                        <!--&gt;-->
                            <!--<icon-->
                                <!--type="edit"-->
                                <!--class="text-white"-->
                                <!--style="margin-top: -2px; margin-left: 3px"-->
                            <!--/>-->
                        <!--</router-link>-->
                    <!--</div>-->
                <!--</div>-->
        </div>
    </loading-view>
</template>

<script>
import {
    InteractsWithResourceInformation,
    Errors,
    Deletable,
    Minimum,
    HasCards,
} from 'laravel-nova'

export default {
    props: {
        resourceName: {
            type: String,
            required: true,
        },
        resourceId: {
            required: true,
        },
        // viaResource: {
        //     default: '',
        // },
        // viaResourceId: {
        //     default: '',
        // },
        // viaRelationship: {
        //     default: '',
        // },
    },

    mixins: [Deletable, HasCards, InteractsWithResourceInformation],

    data: () => ({
        relationResponse: null,
        fields: [],
        validationErrors: new Errors(),
        lastRetrievedAt: null,

        initialLoading: true,
        loading: true,

        resource: null,
        panels: [],
        cards: [],
        actions: [],
        actionValidationErrors: new Errors(),
        deleteModalOpen: false,
        restoreModalOpen: false,
        forceDeleteModalOpen: false,
    }),

    watch: {
        resourceId: function(newResourceId, oldResourceId) {
            if (newResourceId != oldResourceId) {
                this.initialLoading = true;
                this.initializeComponent()
            }
        },
    },

    /**
     * Bind the keydown even listener when the component is created
     */
    async created() {
        // If this update is via a relation index, then let's grab the field
        // and use the label for that as the one we use for the title and buttons
        if (this.isRelation) {
            const { data } = await Nova.request(
                '/nova-api/' + this.viaResource + '/field/' + this.viaRelationship
            )
            this.relationResponse = data
        }

        this.getFields()

        this.updateLastRetrievedAtTimestamp()

        document.addEventListener('keydown', this.handleKeydown)
    },

    /**
     * Unbind the keydown even listener when the component is destroyed
     */
    destroyed() {
        document.removeEventListener('keydown', this.handleKeydown)
    },

    /**
     * Mount the component.
     */
    mounted() {
        this.initializeComponent()
    },

    methods: {
        /**
         * Get the available fields for the resource.
         */
        async getFields() {
            this.loading = true

            this.fields = []

            const { data: fields } = await Nova.request()
                .get(`/nova-api/${this.resourceName}/${this.resourceId}/update-fields`)
                .catch(error => {
                    if (error.response.status == 404) {
                        this.$router.push({ name: '404' })
                        return
                    }
                })

            this.fields = fields

            this.loading = false
        },

        /**
         * Update the resource using the provided data.
         */
        async updateResource() {
            try {
                const response = await this.updateRequest()

                this.$toasted.show(
                    this.__('The :resource was updated!', {
                        resource: this.resourceInformation.singularLabel.toLowerCase(),
                    }),
                    { type: 'success' }
                )

                this.$router.push({
                    name: 'detail',
                    params: {
                        resourceName: this.resourceName,
                        resourceId: this.resourceId,
                    },
                })

                this.updateLastRetrievedAtTimestamp();
            } catch (error) {
                if (error.response.status == 422) {
                    this.validationErrors = new Errors(error.response.data.errors)
                }

                if (error.response.status == 409) {
                    this.$toasted.show(
                        this.__(
                            'Another user has updated this resource since this page was loaded. Please refresh the page and try again.'
                        ),
                        { type: 'error' }
                    )
                }
            }
        },

        /**
         * Update the resource and reset the form
         */
        async updateAndContinueEditing() {
            try {
                const response = await this.updateRequest()

                this.$toasted.show(
                    this.__('The :resource was updated!', {
                        resource: this.resourceInformation.singularLabel.toLowerCase(),
                    }),
                    { type: 'success' }
                )

                // Reset the form by refetching the fields
                this.getFields()

                this.validationErrors = new Errors()

                this.updateLastRetrievedAtTimestamp()
            } catch (error) {
                if (error.response.status == 422) {
                    this.validationErrors = new Errors(error.response.data.errors)
                }

                if (error.response.status == 409) {
                    this.$toasted.show(
                        this.__(
                            'Another user has updated this resource since this page was loaded. Please refresh the page and try again.'
                        ),
                        { type: 'error' }
                    )
                }
            }
        },

        /**
         * Send an update request for this resource
         */
        updateRequest() {
            return Nova.request().post(
                `/nova-api/${this.resourceName}/${this.resourceId}`,
                this.updateResourceFormData
            )
        },

        /**
         * Update the last retrieved at timestamp to the current UNIX timestamp.
         */
        updateLastRetrievedAtTimestamp() {
            this.lastRetrievedAt = Math.floor(new Date().getTime() / 1000)
        },
        /**
         * Handle the keydown event
         */
        handleKeydown(e) {
            if (
                !e.ctrlKey &&
                !e.altKey &&
                !e.metaKey &&
                !e.shiftKey &&
                e.keyCode == 69 &&
                e.target.tagName != 'INPUT' &&
                e.target.tagName != 'TEXTAREA'
            ) {
                this.$router.push({ name: 'edit', params: { id: this.resource.id } })
            }
        },

        /**
         * Initialize the compnent's data.
         */
        async initializeComponent() {
            await this.getResource()
            await this.getActions()
            await this.getFields()

            this.initialLoading = false
        },

        /**
         * Get the resource information.
         */
        getResource() {
            this.resource = null

            return Minimum(
                Nova.request().get('/nova-api/' + this.resourceName + '/' + this.resourceId)
            )
                .then(({ data: { panels, cards, resource } }) => {
                    this.panels = panels
                    this.cards = cards
                    this.resource = resource
                    this.loading = false
                })
                .catch(error => {
                    if (error.response.status >= 500) {
                        Nova.$emit('error', error.response.data.message)
                        return
                    }

                    if (error.response.status === 404 && this.initialLoading) {
                        this.$router.push({ name: '404' })
                        return
                    }

                    if (error.response.status === 403) {
                        this.$router.push({ name: '403' })
                        return
                    }

                    this.$toasted.show(this.__('This resource no longer exists'), { type: 'error' })

                    this.$router.push({
                        name: 'index',
                        params: { resourceName: this.resourceName },
                    })
                })
        },

        /**
         * Get the available actions for the resource.
         */
        getActions() {
            this.actions = []

            return Nova.request()
                .get('/nova-api/' + this.resourceName + '/actions')
                .then(response => {
                    this.actions = response.data.actions
                })
        },

        /**
         * Handle an action executed event.
         */
        async actionExecuted() {
            await this.getResource()
            await this.getActions()
        },

        /**
         * Create a new panel for the given field.
         */
        createPanelForField(field) {
            return _.tap(_.find(this.panels, panel => panel.name == field.panel), panel => {
                panel.fields = [field]
            })
        },

        /**
         * Create a new panel for the given relationship field.
         */
        createPanelForRelationship(field) {
            return {
                component: 'relationship-panel',
                prefixComponent: true,
                name: field.name,
                fields: [field],
            }
        },

        /**
         * Create a new card for the given field.
         */
        createCardForField(field) {
          return _.tap(_.find(this.cards, card => card.name == field.card), card => {
            card.fields = [field]
          })
        },

        /**
         * Create a new card for the given relationship field.
         */
        createCardForRelationship(field) {
          return {
            component: 'relationship-panel',
            prefixComponent: true,
            name: field.name,
            fields: [field],
          }
        },

        /**
         * Show the confirmation modal for deleting or detaching a resource
         */
        async confirmDelete() {
            this.deleteResources([this.resource], () => {
                this.$toasted.show(
                    this.__('The :resource was deleted!', {
                        resource: this.resourceInformation.singularLabel.toLowerCase(),
                    }),
                    { type: 'success' }
                )

                if (!this.resource.softDeletes) {
                    this.$router.push({
                        name: 'index',
                        params: { resourceName: this.resourceName },
                    })
                    return
                }

                this.closeDeleteModal()
                this.getResource()
            })
        },

        /**
         * Open the delete modal
         */
        openDeleteModal() {
            this.deleteModalOpen = true
        },

        /**
         * Close the delete modal
         */
        closeDeleteModal() {
            this.deleteModalOpen = false
        },

        /**
         * Show the confirmation modal for restoring a resource
         */
        async confirmRestore() {
            this.restoreResources([this.resource], () => {
                this.$toasted.show(
                    this.__('The :resource was restored!', {
                        resource: this.resourceInformation.singularLabel.toLowerCase(),
                    }),
                    { type: 'success' }
                )

                this.closeRestoreModal()
                this.getResource()
            })
        },

        /**
         * Open the restore modal
         */
        openRestoreModal() {
            this.restoreModalOpen = true
        },

        /**
         * Close the restore modal
         */
        closeRestoreModal() {
            this.restoreModalOpen = false
        },

        /**
         * Show the confirmation modal for force deleting
         */
        async confirmForceDelete() {
            this.forceDeleteResources([this.resource], () => {
                this.$toasted.show(
                    this.__('The :resource was deleted!', {
                        resource: this.resourceInformation.singularLabel.toLowerCase(),
                    }),
                    { type: 'success' }
                )

                this.$router.push({ name: 'index', params: { resourceName: this.resourceName } })
            })
        },

        /**
         * Open the force delete modal
         */
        openForceDeleteModal() {
            this.forceDeleteModalOpen = true
        },

        /**
         * Close the force delete modal
         */
        closeForceDeleteModal() {
            this.forceDeleteModalOpen = false
        },
    },

    computed: {
        /**
         * Create the form data for creating the resource.
         */
        updateResourceFormData() {
            return _.tap(new FormData(), formData => {
                _(this.fields).each(field => {
                    try {
                        field.fill(formData)
                    } catch(e) {
                        console.error('NOVA ERROR: The field "' + field.attribute + '" is probably using the base Nova field instead of the extended version.');
                        this.$toasted.show(
                            'An error occurred updating the resource. See log for details.',
                            { type: 'error' }
                        )
                    }
                })

                formData.append('_method', 'PUT')
                formData.append('_retrieved_at', this.lastRetrievedAt)
            })
        },

        singularName() {
            if (this.relationResponse) {
                return this.relationResponse.singularLabel
            }

            return this.resourceInformation.singularLabel
        },

        isRelation() {
            return Boolean(this.viaResourceId && this.viaRelationship)
        },
        /**
         * Get the available field panels.
         */
        availablePanels() {
            if (this.resource) {
                var panels = {}

                var fields = _.toArray(JSON.parse(JSON.stringify(this.resource.fields)))

                fields.forEach(field => {
                    if (field.listable) {
                        return (panels[field.name] = this.createPanelForRelationship(field))
                    } else if (panels[field.panel]) {
                        return panels[field.panel].fields.push(field)
                    }

                    panels[field.panel] = this.createPanelForField(field)
                })

                return _.toArray(panels)
            }
        },
        /**
         * Get the available field panels.
         */
        availableCards() {
          if (this.resource) {
            var cards = {}

            var fields = _.toArray(JSON.parse(JSON.stringify(this.resource.fields)))

            fields.forEach(field => {
              if (field.listable) {
                //return (cards[field.name] = this.createCardForRelationship(field))
              } else if (cards[field.card]) {
                return cards[field.card].fields.push(field)
              }

              if (field.card) {
                cards[field.card] = this.createCardForField(field)
              }
            })

            return _.toArray(cards)
          }
        },
        /**
         * These are here to satisfy the parameter requirements for deleting the resource
         */
        currentSearch() {
            return ''
        },

        encodedFilters() {
            return []
        },

        currentTrashed() {
            return ''
        },

        viaResource() {
            return ''
        },

        viaResourceId() {
            return ''
        },

        viaRelationship() {
            return ''
        },

        selectedResources() {
            return [this.resourceId]
        },

        /**
         * Determine whether this is a detail view for an Action Event
         */
        isActionDetail() {
            return this.resourceName == 'action-events'
        },

        /**
         * Get the endpoint for this resource's metrics.
         */
        cardsEndpoint() {
            return `/nova-api/${this.resourceName}/cards`
        },
    },
}
</script>
