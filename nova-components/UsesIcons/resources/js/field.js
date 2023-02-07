Nova.booting((Vue, router) => {
    Vue.component('index-uses-icons', require('./components/IndexField'));
    Vue.component('detail-uses-icons', require('./components/DetailField'));
    Vue.component('form-uses-icons', require('./components/FormField'));
})
