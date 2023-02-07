<template>
  <field-wrapper>
    <div v-if="showLabel">
      <slot>
        <form-label :for="field.name" :class="{'mb-2': field.helpText && showHelpText }">
          {{ fieldLabel || "&nbsp;" }}
        </form-label>
      </slot>
    </div>
    <div :class="fieldClasses">
      <slot name="field"/>

      <help-text class="error-text mt-2 text-danger" v-if="hasError && showErrors">
        {{ firstError }}
      </help-text>

      <help-text class="help-text mt-2" v-if="field.helpText && showHelpText">
        {{ field.helpText }}
      </help-text>
    </div>
  </field-wrapper>
</template>

<script>
  import { HandlesValidationErrors, Errors } from 'laravel-nova'

  export default {
    mixins: [HandlesValidationErrors],

    props: {
      field: { type: Object, required: true },
      fieldName: { type: String },
      showHelpText: { type: Boolean, default: true },
      showErrors: { type: Boolean, default: true },
      fullWidthContent: { type: Boolean, default: true },
    },

    computed: {
      fieldLabel() {
        // If the field name is purposefully an empty string, then
        // let's show it as such
        if (this.fieldName === '') {
          return ''
        }

        return this.fieldName || this.field.singularLabel || this.field.name
      },

      fieldClasses() {
        return this.fullWidthContent ? typeof this.field.plugin !== 'undefined' && this.field.plugin.includes('vue-select') || this.field.hideLabel ? 'w-full' : 'w-full' : 'w-1/2'
      },

      showLabel() {
        return !this.field.hideLabel
      }
    },
  }
</script>
