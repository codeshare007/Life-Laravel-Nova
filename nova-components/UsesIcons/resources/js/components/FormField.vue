<template>
    <default-field :field="field">
        <template slot="field">
            <draggable
                element="div"
                class="dragArea icons"
                :options="{ draggable: 'span' }"
                :list="icons"
                @update="onDraggableUpdate"
            >
                <span 
                    v-for="icon in icons"
                    v-bind:key="icon.name"
                    class="icons__icon" 
                    :class="{ 'icons__icon--active': icon.active }" 
                    v-if="icon.name
                ">
                    <img :src="'/icons/'+icon.name+'.svg'" @click="toggleIcon(icon)" width="30" height="30">
                </span>
            </draggable>
        </template>
    </default-field>
</template>

<script>
import draggable from 'vuedraggable'

export default {

    components: {
        draggable,
    },
    
    props: ['resourceName', 'field'],

    data: () => ({
        icons: [],
    }),

    mounted() {
        this.icons = this.getInitialIcons();

        this.field.fill = formData => {
            formData.append(this.field.attribute, this.getAsJson());
        }
    },

    methods: {
        toggleIcon(item) {
            item.active = ! item.active;
            this.$emit('change');
            this.field.value = this.getAsJson();
        },

        getAsJson() {
            this.updatePositionProperties();

            return JSON.stringify(this.icons);
        },

        updatePositionProperties() {
            if (this.icons) {
                this.icons.forEach((icon, i) => {
                    icon.position = i;
                });
            }
        },

        onDraggableUpdate() {
            this.$emit('change');
        },

        getInitialIcons() {
            if (this.field.value) {
                return JSON.parse(this.field.value).sort(function(a, b) {
                    return a.position - b.position;
                });
            }

            return [{"name":"Internal","active":true,"position":0},{"name":"Topical","active":true,"position":1},{"name":"Aromatic","active":true,"position":2}];
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
    cursor: move;
}

.icons__icon--active {
    opacity: 1;
}
</style>
