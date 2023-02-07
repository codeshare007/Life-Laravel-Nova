<template>
    <default-field :field="field">
        <template slot="field">
            <div v-if="showWrapper" class="bg-40 p-6 rounded flex justify-between items-center">
                <strong>{{ field.name }}</strong>
                <ToggleCheckbox onText="" offText="" @input="toggle" :field="field" :resourceRowId="resourceRowId" />
            </div>

            <ToggleCheckbox v-else onText="" offText="" @input="toggle" :field="field" :resourceRowId="resourceRowId" />

            <p v-if="hasError" class="my-2 text-danger" v-html="firstError" />
        </template>
    </default-field>
</template>

<script>
import { Errors, FormField, HandlesValidationErrors } from 'laravel-nova'
import ToggleCheckbox from '../../../../../nova-extend-fields/resources/js/components/tailwind/TailwindToogle'

export default {
    mixins: [HandlesValidationErrors, FormField],

    components: {
      ToggleCheckbox,
    },

    props: {
        resourceRowId: {
            type: Number,
        },
        showWrapper: {
            type: Boolean,
            default: true,
        },
    },

    data: () => ({
        value: false,
    }),

    mounted() {
        this.value = this.field.value || false

        this.field.fill = formData => {
            formData.append(this.field.attribute, this.trueValue)
        }
    },

    methods: {
        toggle() {
            this.value = !this.value
        },
    },

    computed: {
        checked() {
            return Boolean(this.value)
        },

        trueValue() {
            return +this.checked
        },
    },
}
</script>
