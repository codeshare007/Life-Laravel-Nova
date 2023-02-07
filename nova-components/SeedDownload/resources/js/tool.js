Nova.booting((Vue, router, store) => {
    router.addRoutes([
        {
            name: 'SeedDownload',
            path: '/SeedDownload',
            component: require('./components/Tool'),
        },
    ])
})
