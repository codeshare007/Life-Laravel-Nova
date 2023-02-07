<template>
  <!--<div class="flex w-full justify-center items-center mx-6" v-if="onlyResources.includes(resourceName)">-->
    <!--<ToggleCheckbox v-model="isToggleOn" onText="Batch Edit Mode [ON]" offText="Batch Edit Mode [OFF]" @input="toggleEditMode"/>-->
    <!--<button v-if="isToggleOn" dusk="save-button" class="btn btn-default btn-success ml-6" @click="batchSaveChanges">-->
      <!--{{__('Save Changes')}}-->
    <!--</button>-->
  <!--</div>-->
  <div></div>
</template>

<script>

  // import {
  //   PerPageable,
  //   InteractsWithQueryString,
  //   InteractsWithResourceInformation,
  // } from 'laravel-nova'

  // import ToggleCheckbox from '../../../../nova-extend-fields/resources/js/components/tailwind/TailwindToogleEditMode'

  export default {

    // mixins: [
    //   PerPageable,
    //   InteractsWithResourceInformation,
    //   InteractsWithQueryString,
    // ],

    // components: {
    //   ToggleCheckbox,
    // },

    // props: ['resource', 'resourceName', 'resourceId'],

    // created() {
    //   Nova.$on('onEditModeSave', () => {
    //     this.updateQueryString({
    //       ['editMode']: false
    //     })

    //     this.isToggleOn = false;
    //   });
    // },

    // data() {
    //   return {
    //     onlyResources: ['ailments', 'top-uses'],
    //     isToggleOn: (this.$route.query.editMode === 'true'),
    //     formDataTmp: []
    //   }
    // },

    // methods: {
    //   toggleEditMode(status) {
    //     this.updateQueryString({
    //       ['editMode']: status
    //     })
    //   },
    //   batchSaveChanges() {
    //     Nova.bus.$emit('onBatchSaveChanges_'+this.resourceName);
    //   }
    // }
  }
</script>
