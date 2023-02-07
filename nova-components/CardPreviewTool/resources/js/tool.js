Nova.booting((Vue, router, store) => {
    Vue.component('card-preview-tool', require('./components/Tool'))
    Vue.component('card-preview', require('./components/CardPreview'))
})
