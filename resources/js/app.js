
/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

window.Vue = require('vue');
import VModal from 'vue-js-modal'
import { ModelSelect, BasicSelect } from 'vue-search-select'
import vSelect from 'vue-select'
import VueDraggableResizable from 'vue-draggable-resizable'
import BootstrapVue from 'bootstrap-vue'

Vue.use(BootstrapVue);

import 'bootstrap/dist/css/bootstrap.css'
import 'bootstrap-vue/dist/bootstrap-vue.css'
 
Vue.use(VModal)
Vue.use(ModelSelect)
import VueApexCharts from 'vue-apexcharts'
window.ApexCharts = VueApexCharts;
Vue.use(VueApexCharts);
import VueGridLayout from 'vue-grid-layout';

import '@progress/kendo-ui'
import '@progress/kendo-theme-default/dist/all.css'

import { TreeMap, TreeMapInstaller } from '@progress/kendo-treemap-vue-wrapper'

Vue.use(TreeMapInstaller)

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

Vue.component('example-component', require('./components/ExampleComponent.vue'));
Vue.component('d3-treemap', require('./components/D3Treemap.vue'));
Vue.component('dashboard', require('./components/Dashboard.vue'));
Vue.component('model-select', ModelSelect);
Vue.component('basic-select', BasicSelect);
Vue.component('v-select', vSelect)
Vue.component('apex-charts', VueApexCharts)
Vue.component('vue-draggable-resizable', VueDraggableResizable)

const app = new Vue({
    el: '#app',
    components: {
       GridLayout: VueGridLayout.GridLayout,
       GridItem: VueGridLayout.GridItem
   }
});
