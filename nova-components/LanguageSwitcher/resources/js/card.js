Nova.booting((Vue, router, store) => {
    Vue.component('language-switcher-card', require('./components/Card'))
    Vue.component('language-switcher', require('./components/LanguageSwitcher'))
})
