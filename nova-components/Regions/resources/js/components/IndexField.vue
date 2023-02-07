<template>
    <component
        v-if="editMode"
        :is="'form-' + field.component"
        :resource-name="resourceName"
        :field="field"
        @change="$emit('change')"
    />
    <div v-else>
        <span>{{ description() }}</span>
    </div>
</template>

<script>
export default {
    props: ['resourceName', 'field', 'editMode'],

    methods: {
        description() {
            return this.field.region_options.map((option) => {
                if (this.field.value.includes(option.value)) {
                    return option.description;
                }
            }).filter(Boolean).join(', ');
        },
    }
}
</script>
