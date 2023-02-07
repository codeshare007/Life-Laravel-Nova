import Router from 'vue-router'
import routes from '../../../../../resources/js/router/routes'
import CustomRelationForm from './components/CustomRelationForm'
import CreateRelationButton from './components/CreateRelationButton'
import DetailExtend from './views/DetailExtend'

Nova.booting((Vue, router) => {
    Vue.config.devtools = true;

    Vue.component('custom-relation-form', CustomRelationForm)
    Vue.component('create-relation-button', CreateRelationButton)

    router = updateRouter(router);
})

function updateRouter(router) {

    // Replace the default detail view with the extended version
    routes.find(route => route.name === 'detail').component = DetailExtend;
    
    router.matcher = new Router({
        routes: routes
    }).matcher;

    return router;
}
