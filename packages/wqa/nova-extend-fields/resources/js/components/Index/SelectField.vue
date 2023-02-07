<template>
    <default-field :field="field">
        <template slot="field">
            <select
                v-if="!field.plugin"
                :id="field.attribute"
                v-model="value"
                class="w-full form-control form-select"
                :class="errorClasses"
            >
                <option value="" selected disabled>
                    {{__('Choose an option')}}
                </option>

                <option
                    v-for="option in field.options"
                    :value="option.value"
                    :selected="option.value == value"
                >
                    {{ option.label }}
                </option>
            </select>

            <multiselect
              v-if="field.plugin === 'vue-select'"
              :id="field.attribute"
              v-model="selected"
              :options="field.options"
              label="label"
              track-by="value"
              :max-height="400"
              :allowEmpty="false"
              @input="onSelected">
              <template slot="clear" slot-scope="props">
                <div @mousedown.prevent.stop="editFieldToggle" class="multiselect__edit">
                  <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 20 20" aria-labelledby="edit" role="presentation" class="fill-current">
                    <path d="M4.3 10.3l10-10a1 1 0 0 1 1.4 0l4 4a1 1 0 0 1 0 1.4l-10 10a1 1 0 0 1-.7.3H5a1 1 0 0 1-1-1v-4a1 1 0 0 1 .3-.7zM6 14h2.59l9-9L15 2.41l-9 9V14zm10-2a1 1 0 0 1 2 0v6a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2V4c0-1.1.9-2 2-2h6a1 1 0 1 1 0 2H2v14h14v-6z"></path>
                  </svg>
                </div>
              </template>
            </multiselect>

            <div id="select-field-wrap" v-if="field.plugin === 'vue-select-field'">
              <div class="flex items-center border-b border-b-2 border-green py-2 multiselect__save">
                <input v-model="selected.label" class="appearance-none bg-transparent border-none w-full text-grey-darker mr-3 py-1 px-2 leading-tight focus:outline-none" type="text" aria-label="Full name">
                <button class="flex-no-shrink btn-success text-sm text-white py-1 px-2 rounded" type="button" @mousedown.prevent.stop="editSaveField">Save</button>
              </div>
            </div>

            <p v-if="hasError" class="my-2 text-danger">
                {{ firstError }}
            </p>
        </template>
    </default-field>
</template>

<script>
import { FormField, HandlesValidationErrors } from 'laravel-nova'

export default {
    mixins: [HandlesValidationErrors, FormField],

    props: ['resourceRowId', 'resourceName', 'field', 'editMode'],

    data () {
      return {
        selected: this.field.selectedOption || [],
        selectedId: this.field.selectedOption.value || 0,
      }
    },

    created() {
      Nova.$on('reloadOptionsAfterSave_Resource'+this.resourceRowId, (data) => {
        this.selected.value = data.usage_id
        if (this.selected.value) {
          Nova.bus.$emit('onChangeSelect_Resource' + this.resourceRowId, [this.selected.value, this.field.attribute]);
        }
      });
      Nova.$on('batchUpdateSelected', (data) => {
        if (data.applyTo.length === 0 || data.applyTo.includes(this.resourceRowId)) {
          Nova.bus.$emit('onChangeSelect_Resource'+this.resourceRowId, [data.selected.value, this.field.attribute]);
          this.selected = data.selected
        }
      });
    },

    methods: {
        /**
         * Provide a function that fills a passed FormData object with the
         * field's internal value attribute. Here we are forcing there to be a
         * value sent to the server instead of the default behavior of
         * `this.value || ''` to avoid loose-comparison issues if the keys
         * are truthy or falsey
         */
        fill(formData) {
            formData.append(this.field.attribute, this.value)
        },

        onSelected() {
          Nova.bus.$emit('onChangeSelect_Resource'+this.resourceRowId, [this.selected.value, this.field.attribute]);
        },

        editFieldToggle() {
          this.field.plugin = 'vue-select-field'
          this.field.value = this.selected.label
        },

        editSaveField() {
          this.field.plugin = 'vue-select'
          this.field.value = this.selected

          Nova.bus.$emit('onFieldInlineSave_Resource'+this.resourceRowId, [this.selected.label, this.field.attribute]);
          Nova.bus.$emit('onFieldInlineSaveReload', [this.selected]);
        }
    },
}
</script>
<style src="vue-multiselect/dist/vue-multiselect.min.css"></style>
<style>

  div[dusk="ailments-detail-component"] .label-select-field {
    display: none;
  }
  div[dusk="ailments-detail-component"] .multiselect {
    margin: 10px 0;
  }
  .multiselect--active .multiselect__edit {
    display: none;
  }
  .multiselect:not(.multiselect--active) .multiselect__select {
    display: none;
  }
  .multiselect:not(.multiselect--active) .multiselect__edit {
    line-height: 16px;
    display: block;
    position: absolute;
    box-sizing: border-box;
    color: var(--primary);
    width: 40px;
    height: 38px;
    right: 0px;
    top: 5px;
    padding: 4px 8px;
    margin: 0;
    text-decoration: none;
    text-align: center;
    cursor: pointer;
    transition: transform 0.2s ease;
  }
</style>
