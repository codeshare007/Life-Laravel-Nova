Nova.booting((Vue, router) => {
    router.addRoutes([
        {
            name: 'global-status',
            path: '/global-status',
            component: require('./components/Tool'),
        },
    ])
})
