<template>
  <div class="editMode" v-if="editMode">
    <ToggleCheckbox :field="field" :resource-row-id="resourceRowId" @input="toggleMode"/>
  </div>
  <div v-else>
    <div class="text-center">
        <span
            class="inline-block rounded-full w-2 h-2"
            :class="{'bg-success': field.value, 'bg-danger': !field.value}" />
    </div>
  </div>
</template>

<script>
  import ToggleCheckbox from '../tailwind/TailwindToogle'
  export default {

    props: ['resourceRowId', 'resourceName', 'field', 'editMode'],

    components: {
      ToggleCheckbox,
    },

    methods: {
      toggleMode(status) {
        // this.updateQueryString({
        //   ['editMode']: status
        // })
      }
    }
  }
</script>
