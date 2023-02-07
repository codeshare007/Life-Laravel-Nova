<template>
    <div v-if="shouldShowButtons">

        <!-- Create Related Models -->
        <button
            v-if="shouldShowCreateButton"
            tag="button"
            dusk="create-button"
            :class="classes"
        >
            {{ __('Create') }} {{ singularName }}
        </button>
    </div>
</template>

<script>
export default {
    props: {
        classes: { default: 'btn btn-default btn-primary' },
        singularName: {},
        resourceName: {},
        viaResource: {},
        viaResourceId: {},
        viaRelationship: {},
        relationshipType: {},
        authorizedToCreate: {},
        authorizedToRelate: {},
    },

    computed: {
        /**
         * Determine if any buttons should be displayed.
         */
        shouldShowButtons() {
            return this.shouldShowCreateButton
        },

        /**
         * Determine if the create button should be displayed.
         */
        shouldShowCreateButton() {
            return this.authorizedToCreate
        },
    },
}
</script>
