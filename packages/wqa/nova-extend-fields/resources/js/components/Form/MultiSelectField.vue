<template>
    <div class="multi-select-wrap">
      <default-field :field="field">
          <template slot="field">
              <v-select
                multiple
                v-model="selected"
                :taggable="taggable"
                :options="field.options"
                @option:created="appendNewOption">
              </v-select>
              <p v-if="hasError" class="my-2 text-danger">
                  {{ firstError }}
              </p>
          </template>
      </default-field>
    </div>
</template>

<script>
import { FormField, HandlesValidationErrors } from 'laravel-nova'
export default {
    mixins: [HandlesValidationErrors, FormField],

    watch: {
      selected(newValues) {
        this.selectedIds = newValues.map(obj => (obj.value !== undefined ? obj.value : obj.label));
      }
    },

    data () {
      return {
        taggable: this.field.taggable,
        selected: this.field.selectedOptions || [],
        selectedIds: this.field.selectedOptions.map(obj => obj.value) || [],
      }
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
            //console.info(this.field.attribute, JSON.stringify(this.selected));
            formData.append(this.field.attribute+'_list', this.selectedIds);
        },

        appendNewOption(newOption) {
          console.info(newOption);
          this.selectedIds.push(newOption);
        }
    },
}
</script>

<style lang="scss">
  .v-select {
    .dropdown-toggle {
      border: 1px solid #e3e7ea;
      padding: 10px;
    }

    .vs__selected-options {
      margin: -5px;
    }

    input[type=search],
    input[type=search]:focus {
      margin-top: 0;
    }

    .selected-tag {
      border-radius: 1000px;
      background-color: #e3e7ea;
      color: #69798d;
      border: 0;
      padding: 10px 20px;
      font-size: 12px;
      margin: 5px;

      .close {
        opacity: 1;
        margin-left: 10px;

        span {
          background: white;
          border-radius: 50%;
          opacity: 1;
          color: #69798d;
          font-weight: normal;
          width: 16px;
          height: 16px;
          display: flex;
          justify-content: center;
          align-items: center;
          text-shadow: none;
        }

        &:hover span {
          background: #e74444;
          color: white;
        }
      }
    }
  }
</style>
