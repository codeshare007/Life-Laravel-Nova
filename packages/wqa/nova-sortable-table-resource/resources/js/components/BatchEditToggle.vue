<template>
  <div>
    <button v-text="editModeOn ? 'Cancel' : 'Quick Edit'" class="btn btn-default" @click="toggleEditMode"></button>
    <button v-if="editModeOn" class="btn btn-default btn-primary ml-3" @click="saveChanges" :disabled="isLoading">{{__('Save Changes')}}</button>
  </div>
</template>

<script>

export default {

    props: [
        'indexId',
    ],

    data() {
        return {
            editModeOn: false,
            isLoading: false,
        }
    },

    mounted() {
        Nova.$on('onEditModeSaved', (data) => {
            if (data.indexId === this.indexId) {
                this.toggleEditMode();
                this.isLoading = false;
            }
        });
    },

    methods: {
        toggleEditMode() {
            this.editModeOn = ! this.editModeOn;

            Nova.bus.$emit('onToggleEditMode', {
                editModeOn: this.editModeOn,
                indexId: this.indexId,
            });
        },

        saveChanges() {
            this.isLoading = true;

            Nova.bus.$emit('triggerEditModeSaveChanges', {
                indexId: this.indexId,
            });
        }
    }
}
</script>
