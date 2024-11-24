import Vue from 'vue'
import App from './App.vue'

import store from './store/store.js'

import BootstrapVue from 'bootstrap-vue'
import router from './router'
import Vuelidate from 'vuelidate'


// * Loading Spinner
// import 'vue-spinners/dist/vue-spinners.css'
// import VueSpinners from 'vue-spinners/dist/vue-spinners.common'

// Vue.use(VueSpinners)

import { VueSpinners } from '@saeris/vue-spinners'

Vue.use(VueSpinners)

import { XlsxRead, XlsxTable, XlsxSheets, XlsxJson } from "vue-xlsx/dist/vue-xlsx.es.js"
Vue.component('xlsx-read', XlsxRead);
Vue.component('xlsx-table', XlsxTable);
Vue.component('xlsx-sheets', XlsxSheets);
Vue.component('xlsx-json', XlsxJson);

// // * Vuetify
// import Vuetify from 'vuetify' // Import Vuetify to your project
// // import 'https://fonts.googleapis.com/css?family=Roboto:100,300,400,500,700,900'
// import '@mdi/font/css/materialdesignicons.css'
// // Vuetify's CSS styles 
// import 'vuetify/dist/vuetify.min.css'// import plugin


// Vue.use(Vuetify) // Add Vuetify as a plugin

// Install BootstrapVue
Vue.use(BootstrapVue)

Vue.use(Vuelidate)

import 'bootstrap/dist/css/bootstrap.css'
import 'bootstrap-vue/dist/bootstrap-vue.css'

import 'viewerjs/dist/viewer.css'
import Viewer from 'v-viewer'
Vue.use(Viewer)

// * Date Picker
import VueCtkDateTimePicker from 'vue-ctk-date-time-picker';
import 'vue-ctk-date-time-picker/dist/vue-ctk-date-time-picker.css';

Vue.component('VueCtkDateTimePicker', VueCtkDateTimePicker);

// // * Vue Material
// import VueMaterial from 'vue-material'
// import 'vue-material/dist/vue-material.min.css'
// Vue.use(VueMaterial)

// * Rich Text (Vue Wysiwyg) 
import wysiwyg from "vue-wysiwyg";
Vue.use(wysiwyg, {
  // { [module]: boolean (set true to hide) }
  hideModules: { "link": true, "code": true, "image": true, "table": true, "separator": true },
  // limit content height if you wish. If not set, editor size will grow with content.
  maxHeight: "100px",
}); // config is optional. more below

import "vue-wysiwyg/dist/vueWysiwyg.css";

Vue.config.productionTip = false

const authData = localStorage.getItem("auth");

new Vue({
  store,
  router,
  validations: {},
  data: {
    auth: authData ? JSON.parse(authData) : null
  },//global data in application
  render: h => h(App),
}).$mount('#app')
