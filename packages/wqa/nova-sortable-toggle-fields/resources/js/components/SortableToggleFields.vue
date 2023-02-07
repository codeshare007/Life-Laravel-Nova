<template>
  <div class="drag inline-flex">
      <draggable
        element="ul"
        class="dragArea list-reset flex"
        :class="'list_'+viaResourceId"
        :options="{draggable:'.item', disabled: !editMode}"
        :list="field.value"
        @update="onDraggableUpdate"
        ref="toggleFields">
        <li v-for="icon in field.value" class="mr-1 ml-1 item" :class="{active: icon.active}" v-if="icon.name" :data-id="(icon.active ? icon.name : 0)">
            <img :src="'/icons/'+icon.name+'.svg'" @click="toggleIcon(icon)">
        </li>
      </draggable>
  </div>
</template>

<script>
    import { InteractsWithResourceInformation } from 'laravel-nova'
    import draggable from 'vuedraggable'
    export default {
        mixins: [InteractsWithResourceInformation],

        components: {
          draggable,
        },

        props: ['viaResource', 'viaResourceId', 'resourceName', 'field', 'editMode'],

        data: () => ({

        }),

        methods: {
          toggleIcon(item) {
            if (this.editMode) {
              item.active = !item.active;
              this.onDraggableUpdate();
            }
          },
          onDraggableUpdate() {
            let resourceId = this.$refs.toggleFields.$parent.$parent.$el.attributes['data-id'].value;
            Nova.bus.$emit('onToggleFields_Resource'+resourceId, [this.field.value, this.field.attribute]);
          },
        },
    }
</script>
