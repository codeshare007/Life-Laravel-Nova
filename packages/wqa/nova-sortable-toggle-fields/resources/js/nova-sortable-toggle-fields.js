Nova.booting((Vue, router) => {
    Vue.config.devtools = true;
    Vue.component('index-nova-sortable-toggle-fields', require('./components/SortableToggleFields'));
})
