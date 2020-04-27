
require('./bootstrap');
import Vue from 'vue';
import VueRouter from 'vue-router';
import Vuex from 'vuex';
import MainApp from './components/MainApp.vue';
import StoreData from './store';
import { routes } from './routes';
import { initialize } from './helpers/general'
import Vue2Filters from 'vue2-filters'
import VueHtmlToPaper from 'vue-html-to-paper';
import VueProgressBar from 'vue-progressbar'
import BootstrapVue from 'bootstrap-vue/dist/bootstrap-vue.esm'
import axios from 'axios';

import 'bootstrap-vue/dist/bootstrap-vue.css';

Vue.use(BootstrapVue);

Vue.use(VueProgressBar, {
    color: 'rgb(143, 255, 199)',
    failedColor: 'red',
    height: '4px',
    thickness: '4px',
    transition: {
        speed: '0.2s',
        opacity: '0.6s',
        termination: 300
      }
})

const options = {
    name: '_blank',
    specs: [
      'fullscreen=yes',
      'titlebar=yes',
      'scrollbars=yes'
    ],
    styles: [
      'https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css',
      'https://unpkg.com/kidlat-css/css/kidlat.css'
    ]
}



Vue.use(VueRouter);
Vue.use(Vuex);
Vue.use(Vue2Filters);
Vue.use(VueHtmlToPaper, options);
Vue.use(require('vue-moment'));


Vue.directive('click-outside',
{
    bind () {
        this.event = event => this.vm.$emit(this.expression, event)
        this.el.addEventListener('click', this.stopProp)
        document.body.addEventListener('click', this.event)
    },
    unbind() {
        this.el.removeEventListener('click', this.stopProp)
        document.body.removeEventListener('click', this.event)

    },
    stopProp(event) {
    event.stopPropagation() }
})






const store = new Vuex.Store(StoreData);

const router = new VueRouter({
    routes,
    mode: 'history'
})

initialize(store,router)



axios.interceptors.request.use(config => {
    app.$Progress.start() // for every request start the progress
    return config;
});

axios.interceptors.response.use(response => {
    app.$Progress.finish() // finish when a response is received
    return response;
});

axios.interceptors.response.use(null, (error) => {
    app.$Progress.fail()
    return Promise.reject(error)
})

const app = new Vue({
    el: '#app',
    store,
    router,
    components: {
        MainApp
    },
    mixins: [Vue2Filters.mixin]
})


