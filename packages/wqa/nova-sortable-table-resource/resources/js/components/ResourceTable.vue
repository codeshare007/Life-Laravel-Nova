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
        :edit-mode="editMode"
        :indexId="indexId"
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
    InteractsWithResourceInformation,
} from 'laravel-nova'

import draggable from 'vuedraggable'


export default {
  mixins: [
    InteractsWithResourceInformation,
  ],

  components: {
    draggable,
  },

  props: {
    editMode: {
      default: false,
    },
    indexId: {
      default: null,
      required: true,
    },
    authorizedToRelate: {
      type: Boolean,
      required: true,
    },
    resourceName: {
      default: null,
    },
    singularName: {
      type: String,
      required: true,
    },
    selectedResources: {
      default: [],
    },
    selectedResourceIds: {},
    shouldShowCheckboxes: {
      type: Boolean,
      default: false,
    },
    actionsAreAvailable: {
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
    updateSelectionStatus: {
      type: Function,
    },
    resources: {
      default: [],
    },
  },

  data: () => ({
    selectAllResources: false,
    selectAllMatching: false,
    resourceCount: null,
    options: {
      disabled: false,
    },
  }),

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

    /**
   * Get the name of the page query string variable.
   */
    pageParameter() {
      return this.resourceName + '_page'
    },
  },
}
</script>
