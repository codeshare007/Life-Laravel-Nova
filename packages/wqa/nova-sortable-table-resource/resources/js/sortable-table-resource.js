import Vue from "vue";

Nova.booting((Vue, router) => {
    Vue.config.devtools = true;
    Vue.prototype.$bus = new Vue(); // Global event bus
    Vue.component('custom-index-header', require('./components/CustomIndexHeader'));
    Vue.component('custom-index-toolbar', require('./components/CustomIndexToolbar'));
    Vue.component('custom-index-editmode', require('./components/CustomIndexEditmode'));
    Vue.component('custom-detail-header', require('./components/CustomDetailHeader'));
    Vue.component('custom-detail-toolbar', require('./components/CustomDetailToolbar'));
    Vue.component('resource-table', require('./components/ResourceTable'));
    Vue.component('resource-table-row', require('./components/ResourceTableRow'));
    Vue.component('batch-selected', require('./components/BatchSelected'));
    Vue.component('filter-select', require('./components/FilterSelect'))
    Vue.component('filter-selector', require('./components/FilterSelector'))


    Vue.component('batch-edit-toggle', require('./components/BatchEditToggle'))
})
