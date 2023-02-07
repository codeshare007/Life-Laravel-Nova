<template>
    <div class="batchSelected absolute pin-r mr-24 w-4/5">
      <multiselect
        :options="options"
        v-model="selected"
        label="label"
        track-by="value"
        :max-height="400"
        :allowEmpty="false">
      </multiselect>
      <button v-if="selected.value" class="btn-success text-white font-bold py-2 px-4 rounded" @click="applyToSelected">
        {{ applyTextValue }}
      </button>
    </div>
</template>

<script>
export default {
    props: [
        'resources',
        'selectedResources',
        'viaManyToMany',
        'allMatchingResourceCount',
        'allMatchingSelected',
        'authorizedToDeleteSelectedResources',
        'authorizedToForceDeleteSelectedResources',
        'authorizedToDeleteAnyResources',
        'authorizedToForceDeleteAnyResources',
        'authorizedToRestoreSelectedResources',
        'authorizedToRestoreAnyResources',
    ],

    mounted() {
      this.reload()
      Nova.$on('onFieldInlineSaveReload', (data) => {
        this.reload()
        this.selected = data[0]
      });
    },

    data: () => ({
        selected: [],
        options: [],
        deleteSelectedModalOpen: false,
        forceDeleteSelectedModalOpen: false,
        restoreModalOpen: false,
    }),

    computed: {
      applyTextValue() {
        let nbSelected = (this.selectedResources.length === 0 ? 'all' : this.selectedResources.length+' Selected');
        return `Apply to ${nbSelected}`;
      }
    },

    methods: {
        confirmDeleteSelectedResources() {
            this.deleteSelectedModalOpen = true
        },
        applyToSelected() {
          Nova.bus.$emit('batchUpdateSelected', {
            'selected': this.selected,
            'applyTo': this.selectedResources.map(obj => obj.id.value)
          })
        },
        reload() {
          let url = '/nova-vendor/wqa/nova-sortable-table-resource/'+this.resourceName+'/populate-usage-options';
          return Nova.request().get(url).then(response => {
            this.options = response.data.usages
          })
        }
    }
}
</script>

<style>
  .batchSelected button {
    position: absolute;
    font-size: 12px;
    padding: 8px;
    right: 5px;
    top: 15px;
  }
</style>
