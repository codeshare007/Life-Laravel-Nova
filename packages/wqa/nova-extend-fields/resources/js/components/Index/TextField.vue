<template>
    <div class="editMode" v-if="editMode">
        <component
            :is="'form-' + field.component"
            :resource-name="resourceName"
            :field="field"
        />
    </div>
    <div v-else>
        <div v-if="field.asHtml" v-html="field.value"></div>
        <span v-else class="whitespace-no-wrap">{{ field.value }}</span>
    </div>
</template>

<script>
export default {
    props: ['resourceName', 'field', 'editMode']
}
</script>
