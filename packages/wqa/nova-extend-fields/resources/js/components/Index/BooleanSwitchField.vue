<template>
  <div class="editMode" v-if="editMode">
    <component
      :is="'form-' + field.component"
      :resource-name="resourceName"
      :field="field"
      :resourceRowId="resourceRowId"
      :showWrapper="false"
    />
  </div>
  <div v-else class="text-center">
    <span
      class="inline-block rounded-full w-4 h-4"
      :class="{'bg-success': field.value, 'bg-danger': !field.value}"
    />
  </div>
</template>

<script>
  export default {
    props: ['resourceName', 'resourceRowId', 'field', 'editMode'],
  }
</script>
