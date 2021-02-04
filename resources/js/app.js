require('./bootstrap');

import moment                                         from 'moment';
import Vue                                            from 'vue';
import { App as InertiaApp, plugin as InertiaPlugin } from '@inertiajs/inertia-vue';
import PortalVue                                      from 'portal-vue';


Vue.mixin({methods : {route}});
Vue.use(InertiaPlugin);
Vue.use(PortalVue);

Vue.filter('from', (e) => {
	return moment(e).local().fromNow();
});

const app = document.getElementById('app');

new Vue({
	render : (h) =>
		h(InertiaApp, {
			props : {
				initialPage      : JSON.parse(app.dataset.page),
				resolveComponent : (name) => require(`./Pages/${name}`).default,
			},
		}),
}).$mount(app);
