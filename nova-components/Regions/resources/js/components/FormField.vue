<template>
    <default-field :field="field" :errors="errors">
        <template slot="field">
            <div class="region-option-buttons"> 
                <button 
                    type="button"
                    v-for="option in field.region_options" 
                    :key="option.value"
                    class="region-option-button"
                    :class="{ 'region-option-button--active': selectedRegions.includes(option.value) }"
                    @click="toggleOption(option.value)"
                >
                    {{ option.description }}
                </button>
            </div>
        </template>
    </default-field>
</template>

<script>
import { FormField, HandlesValidationErrors } from 'laravel-nova'

export default {
    mixins: [FormField, HandlesValidationErrors],

    props: ['resourceName', 'resourceId', 'field'],

    data: function () {
        return {
            selectedRegions: [],
        }
    },

    methods: {
        /*
         * Set the initial, internal value for the field.
         */
        setInitialValue() {
            this.value = this.field.value || '';
            this.selectedRegions = this.field.value || [];
        },

        /**
         * Fill the given FormData object with the field's internal value.
         */
        fill(formData) {
            formData.append(this.field.attribute, JSON.stringify(this.value || []))
        },

        toggleOption(optionValue) {
            if (this.selectedRegions.includes(optionValue)) {
                this.selectedRegions = this.selectedRegions.filter(function(v) {
                    return v !== optionValue
                });
            } else {
                this.selectedRegions.push(optionValue);
            }

            this.value = this.selectedRegions;
            this.$emit('change');
        }
    },
}
</script>
