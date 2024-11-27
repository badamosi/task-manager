require('./bootstrap');

import Vue from 'vue'; 

import VueRouter from 'vue-router';

import axios from 'axios';
import VueAxios from 'vue-axios';

import VuePaginate from 'vuejs-paginate';



Vue.use(VueRouter);
Vue.use(VueAxios, axios);

Vue.component('paginate', VuePaginate);


// Components
const TaskList = require('./components/TaskList.vue').default;
// const AnotherComponent = require('./components/AnotherComponent.vue').default;

// Routes
const routes = [
    { path: '/', component: TaskList, name: 'home' },
    { path: '*', component: TaskList, name: 'not-found' },
];

// Initialize Vue Router
const router = new VueRouter({
    mode: 'history', 
    routes,
});

// Initialize Vue instance
const app = new Vue({
    el: '#app', 
    router, 
    data: {
        developer: 'Majeed Opeyemi', 
    },
});
