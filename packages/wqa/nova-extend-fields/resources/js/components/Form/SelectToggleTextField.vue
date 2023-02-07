<template>
  <default-field :field="field">
    <template slot="field">
      <v-select
        v-model="selectedOption"
        :options="field.options"
        :placeholder="optionLabel"
      >
      </v-select>
      <input
        type="text"
        v-model="selectedOptionMatch"
        class="w-full form-control form-input-bordered"
        :placeholder="textLabel"
      />
      <button
        tag="button"
        dusk="create-button"
        v-on:click="createRelation"
        class="btn btn-default btn-primary align-content-end"
        style="margin-top: 20px !important;"
      >Set name</button>
    </template>
  </default-field>
</template>

<script>
  import {FormField, HandlesValidationErrors, InteractsWithResourceInformation} from 'laravel-nova'
  import Label from "./Label";

  export default {
    components: {Label},
    mixins: [HandlesValidationErrors, FormField, InteractsWithResourceInformation],

    watch: {
      selectedOption(newValues) {
        this.selectedOptionMatch = this.selectedOption ? this.optionMatches[this.selectedOption.value] : ''
      }
    },

    data() {
      return {
        optionLabel: this.field.optionLabel || '',
        textLabel: this.field.textLabel || '',
        selectedOption: this.field.selectedOption || null,
        optionMatches: this.field.optionMatches,
        selectedOptionMatch: this.field.selectedOption ? this.field.optionMatches[this.field.selectedOption.value] : '',
        resourceId: this.field.resourceId
      }
    },

    methods: {
      async createRelation() {
        try {
          const response = await this.createRequest();

          this.$toasted.show(
            this.__('The region name was updated!'),
            {type: 'success'}
          );

          // Reset the form by refetching the fields
          this.getFields()

          Nova.$emit('onReloadIndex', {
            indexId: this.indexId,
          });

          this.validationErrors = new Errors()
        } catch (error) {
          if (error.response.status == 422) {
            this.validationErrors = new Errors(error.response.data.errors)
          }
        }
      },

      createRequest() {
        return Nova.request().post(
          `/nova-api/product-names`,
          {
            id: this.resourceId,
            type: this.resourceName,
            region: this.selectedOption,
            name: this.selectedOptionMatch
          }
        );
      },

      createResourceFormData() {
        return '';
      }
    },
  }
</script>
