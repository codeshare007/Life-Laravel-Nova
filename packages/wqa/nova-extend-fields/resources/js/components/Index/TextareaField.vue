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
        <div class="py-3 text-sm leading-normal text-80 max-w-xl" v-else>{{ field.value }}</div>
    </div>
</template>

<script>
export default {
    props: ['resourceName', 'field', 'editMode']
}
</script>
