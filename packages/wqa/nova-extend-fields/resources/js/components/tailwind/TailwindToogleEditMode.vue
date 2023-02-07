<template>
  <div>
    <div class="form-switch inline-block align-middle">
      <input v-bind:value="value" v-model="isToggleOn" v-on:change="$emit('input', $event.target.checked)" type="checkbox" :name="'edit_mode_'+resourceName" :id="'edit_mode'+resourceName" class="form-switch-checkbox" />
      <label class="form-switch-label" :for="'edit_mode'+resourceName"></label>
    </div>
    <label class="text-xs text-grey-dark" for="edit_mode">{{ `${ isToggleOn ? onText : offText }` }}</label>
  </div>
</template>

<script>
  export default {
    props: ['value', 'onText', 'offText', 'resourceName'],
    data () {
      return {
        isToggleOn: (this.$route.query.editMode === 'true')
      }
    },
    watch: {
      '$route.query.editMode'(status) {
        this.isToggleOn = status
      }
    }
  }
</script>

<style lang="postcss">
  /* CHECKBOX TOGGLE SWITCH */
  .form-switch {
    @apply relative select-none w-12 mr-2 leading-normal;
  }
  .form-switch-checkbox {
    @apply hidden;
  }
  .form-switch-label {
    @apply block overflow-hidden cursor-pointer bg-white border rounded-full h-6  shadow-inner;

    transition: background-color 0.2s ease-in;
  }
  .form-switch-label:before {
    @apply absolute block bg-white pin-y w-6 border rounded-full -ml-1;

    right: 50%;
    content: "";
    transition: all 0.2s ease-in;
  }
  .form-switch-checkbox:checked + .form-switch-label,
  .form-switch-checkbox:checked + .form-switch-label:before {

  }
  .form-switch-checkbox:checked + .form-switch-label {
    @apply bg-green shadow-none;
  }
  .form-switch-checkbox:checked + .form-switch-label:before {
    @apply pin-r;
  }
</style>
