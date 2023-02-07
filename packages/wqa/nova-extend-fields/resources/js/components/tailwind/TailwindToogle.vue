<template>
  <div>
    <div class="form-switch inline-block align-middle">
      <input v-bind:value="field.value" v-model="isToggleOn" v-on:change="$emit('input', $event.target.checked)" type="checkbox" :name="field.attribute+'_'+resourceRowId" :id="field.attribute+'_'+resourceRowId" class="form-switch-checkbox" />
      <label class="form-switch-label" :for="field.attribute+'_'+resourceRowId"></label>
    </div>
    <label v-if="onText" class="text-xs text-grey-dark" :for="field.attribute+'_'+resourceRowId">{{ `${ field.value ? onText : offText }` }}</label>
  </div>
</template>

<script>
  export default {
    props: ['value', 'field', 'onText', 'offText', 'resourceRowId'],

    data () {
      return {
        isToggleOn: this.field.value || false
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
