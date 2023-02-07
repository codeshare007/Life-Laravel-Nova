<template>
    <component
        v-if="editMode"
        :is="'form-' + field.component"
        :resource-name="resourceName"
        :field="field"
        @change="$emit('change')"
    />
    <div v-else class="icons">
        <span 
            v-for="icon in icons"
            v-bind:key="icon.name"
            class="icons__icon" 
            :class="{ 'icons__icon--active': icon.active }" 
            v-if="icon.name
        ">
            <img :src="'/icons/'+icon.name+'.svg'" @click="toggleIcon(icon)" width="30" height="30">
        </span>
    </div>
</template>

<script>
export default {
    props: ['resourceName', 'field', 'editMode'],

    data: () => ({
        icons: [],
    }),

    mounted() {
        this.icons = this.getInitialIcons();
    },

    methods: {
        getInitialIcons() {
            if (this.field.value) {
                return JSON.parse(this.field.value).sort(function(a, b) {
                    return a.position - b.position;
                });
            }

            return null;
        },
    },
}
</script>

<style scoped>
.icons {
  display: flex;
  list-style: none;
  margin: 0 -5px;
  padding: 0;
}

.icons__icon {
    opacity: 0.3;
    margin: 0 5px;
    border-radius: 50%;
    overflow: hidden;
}

.icons__icon--active {
    opacity: 1;
}
</style>