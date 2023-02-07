<template>
    <div>
        <filter-select v-for="filter in filters" :key="filter.name">
            <h3 slot="default" class="text-sm uppercase tracking-wide text-80 bg-30 p-3">
                {{ filter.name }}
            </h3>

            <select slot="select"
                    :dusk="filter.name + '-filter-select'"
                    class="block w-full form-control-sm form-select"
                    v-model="filter.currentValue"
                    @change="filterChanged(filter)"
            >

                <option value="" selected>&mdash;</option>

                <optgroup v-for="(group) in filter.options" :label="group.name | capitalize | pluralize" v-if="group.value instanceof Object">
                  <option v-for="(value, name) in group.value" :value="`${value}_${group.name}`">
                    {{ name }}
                  </option>
                </optgroup>

                <option v-for="option in filter.options" :value="option.value" v-if="option.value instanceof String">
                  {{ option.name }}
                </option>

              </select>
        </filter-select>
    </div>
</template>

<script>
export default {
    props: ['filters', 'currentFilters'],

    /**
     * Mount the component.
     */
    mounted() {
        this.current = this.currentFilters
    },

    filters: {
      capitalize(value) {
        if (!value && value !== 0) return ''
        value = value.toString()
        return value.charAt(0).toUpperCase() + value.slice(1)
      },
      pluralize(value) {
        if (!value && value !== 0) return ''
        return value + 's';
      }
    },

    methods: {
        /**
         * Handle a filter selection change.
         */
        filterChanged(filter) {
            this.current = _.reject(this.current, f => f.class == filter.class)

            if (filter.currentValue !== '') {
                this.current.push({
                    class: filter.class,
                    value: filter.currentValue,
                })
            }

            this.$emit('update:currentFilters', this.current)
            this.$emit('changed')
        },
    },
}
</script>
