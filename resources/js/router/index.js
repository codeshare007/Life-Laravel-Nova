import _ from 'lodash'
import Vue from 'vue'
import Router from 'vue-router'
import routes from './routes'

Vue.use(Router)

const router = createRouter({ base: window.config.base })

export default router

/**
 * The router factory
 */
function createRouter({ base }) {
    const router = new Router({
        // scrollBehavior,
        base,
        mode: 'history',
        routes,
    })

    router.beforeEach(beforeEach)
    router.afterEach(afterEach)

    return router
}

/**
 * Global router guard.
 *
 * @param {Route} to
 * @param {Route} from
 * @param {Function} next
 */
async function beforeEach(to, from, next) {
    // Get the matched components and resolve them.
    const components = await resolveComponents(router.getMatchedComponents({ ...to }))

    if (components.length === 0) {
        return next()
    }

    // Start the loading bar.
    if (components[components.length - 1].loading !== false) {
        router.app.$nextTick(() => router.app.$loading.start())
    }

    next()
}

/**
 * Global after hook.
 *
 * @param {Route} to
 * @param {Route} from
 * @param {Function} next
 */
async function afterEach(to, from, next) {
    await router.app.$nextTick()
    router.app.$loading.finish()
}

/**
 * Resolve async components.
 *
 * @param  {Array} components
 * @return {Array}
 */
function resolveComponents(components) {
    return Promise.all(
        components.map(component => {
            return typeof component === 'function' ? component() : component
        })
    )
}

/**
 * Scroll Behavior
 *
 * @link https://router.vuejs.org/en/advanced/scroll-behavior.html
 *
 * @param  {Route} to
 * @param  {Route} from
 * @param  {Object|undefined} savedPosition
 * @return {Object}
 */
function scrollBehavior(to, from, savedPosition) {
    if (savedPosition) {
        return savedPosition
    }

    if (to.hash) {
        return { selector: to.hash }
    }

    const [component] = router.getMatchedComponents({ ...to }).slice(-1)

    if (component && component.scrollToTop === false) {
        return {}
    }

    return { x: 0, y: 0 }
}
