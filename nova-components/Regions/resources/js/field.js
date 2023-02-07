Nova.booting((Vue, router, store) => {
    Vue.component('index-regions', require('./components/IndexField'))
    Vue.component('detail-regions', require('./components/DetailField'))
    Vue.component('form-regions', require('./components/FormField'))
})
