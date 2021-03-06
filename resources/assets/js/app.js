
/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');
require('jquery-validation');
require('jquery-datetimepicker');
// window.Vue = require('vue');
require('jquery-ui');
require('jquery-ui/ui/widget');
require('jquery-ui/ui/widgets/autocomplete');
/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

// Vue.component('example-component', require('./components/ExampleComponent.vue'));

// const app = new Vue({
//     el: '#app'
// });
global.$ = global.jQuery = require('jquery');
