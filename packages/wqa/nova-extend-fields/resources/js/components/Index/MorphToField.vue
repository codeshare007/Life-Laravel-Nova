<template>
    <div class="editMode" v-if="editMode">
        <component
            :is="'form-' + field.component"
            :resource-name="resourceName"
            :field="field"
        />
    </div>
    <router-link
        v-else-if="field.value"
        :to="{
            name: 'detail',
            params: {
                resourceName: field.resourceName,
                resourceId: field.morphToId
            }
        }"
        class="no-underline dim text-primary font-bold"
    >
        {{ field.resourceLabel }}: {{ field.value }}
    </router-link>

    <span v-else>&mdash;</span>
</template>

<script>
export default {
    props: ['resourceName', 'field', 'editMode'],
}
</script>
