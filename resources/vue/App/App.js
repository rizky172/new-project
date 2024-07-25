import Vue from 'vue';
// Vuex
import Vuex from 'vuex';
// Vue Cookies
import VueCookie from 'vue-cookie'
// Vue Router
import VueRouter from 'vue-router';
// Vue Numeral Formatter FIlter
import VueNumeralFilter from 'vue-numeral-filter';
// Vue Currency Filter
import VueCurrencyFilter from 'vue-currency-filter';
import WevelopeVueComponents from 'wevelope-vue-components';
import CurrencyConfig from '../Lib/CurrencyConfig.js';

import { abbreviateNumber } from "js-abbreviation-number";

// Install
Vue.use(Vuex);
Vue.use(VueCookie);
Vue.use(VueRouter);
Vue.use(VueNumeralFilter);

Vue.use(VueCurrencyFilter,CurrencyConfig);
Vue.use(WevelopeVueComponents);

import AdminLTE from '../AdminLte/AdminLTE.js';

// Import global component setup
import components from './Components/components.js';

// Import component
// import AppHeader from './Partial/Header.vue';
// import AppNav from './Partial/Nav.vue';
// import AppFooter from './Partial/Footer.vue';
// import FlashMessage from './Components/FlashMessage.vue';

// Vue Router
import Route from './Routes/Route.js';

// Vuex
import StoreApp from './Store/StoreApp.js';
import StoreLoading from './Store/StoreLoading.js';
import StoreMessage from './Store/StoreMessage.js';
import StoreAccessControl from './Store/StoreAccessControl.js';

// Mixin
// import MixinList from './MixinList.js';

// Class: Plain class that could use anywhare using global vue property
import LoadingManager from '../Lib/LoadingManager.js';
import Api from '../Lib/Api/Api.js';
import Setting from '../Lib/Setting.js';
import FlashMessageManager from '../AdminLte/FlashMessageManager.js';
import Sidebar from '../AdminLte/Sidebar.js';
// import Function from '../Lib/Function.js';
import OrderBy from '../Lib/OrderBy.js';
import ConfirmMessage from '../Lib/ConfirmMessage.js';
import AccessControl from '../Lib/AccessControl.js';
import AppConfig from '../Lib/AppConfig.js';
import RouterMetaHandler from '../Lib/RouterMetaHandler.js';
import DateFormat from '../Lib/DateFormat.js';
import PriceFormatter from '../Lib/PriceFormatter.js';

// AXIOS
import axios from 'axios';

// ===== GLOBAL PROPERTY =====
// Prepare property that could access anywhare in vue instance

Vue.prototype.$api = null; // for API Call
// Vue.prototype.$func = Function;
Vue.prototype.$orderBy = new OrderBy;
Vue.prototype.$loading = null; // For loading state
Vue.prototype.$confirm = ConfirmMessage; // For confirm message list of string
Vue.prototype.$ac = null; // For $ac = access control
Vue.prototype.$config = {
    'APP_NAME': process.env.MIX_APP_NAME,
    'DEV': process.env.MIX_APP_ENV != 'production' ? '(dev)' : null
};
Vue.prototype.$appConfig = null; // For $appConfig = Get all config
Vue.prototype.$routerMetaHandler = null;

// Date formatting
Vue.prototype.$df = new DateFormat;
// Price formatting
Vue.prototype.$pf = new PriceFormatter;
// Number formating, example: 1000 => 1k
Vue.prototype.$nf = abbreviateNumber;

// console.log('process', process);
// Vue.prototype.$mixin = {
//     list: MixinList
// }

Vue.prototype.$flash = null; // For flash message
Vue.prototype.$sidebar = null; // Init in vue app

// ===== VUEX =====
// Setup VUEX
// console.log('store loading', StoreLoading);
var store = new Vuex.Store({
    modules: {
        flash: StoreMessage,
        loading: StoreLoading,
        app: StoreApp,
        ac: StoreAccessControl
    }
});

const app = new Vue({
    el: '#app',
    'store': store,
    'router': Route,
    created: function() {
        // console.log('created', this.$config);
        Vue.prototype.$routerMetaHandler = new RouterMetaHandler(this.$store);
        this.initAdminLte();
        this.initFlashMessages();
        this.initLoadingManager();
        this.initSidebar();
        this.initApiManager();
        this.initAxiosConfig();
        this.initAccessControl();
        this.initAppConfig();
    },
    methods: {
        initFlashMessages: function() {
            Vue.prototype.$flash = new FlashMessageManager(this.$store);
        },
        initLoadingManager: function() {
            Vue.prototype.$loading = new LoadingManager(this.$store);
        },
        initSidebar: function() {
            Vue.prototype.$sidebar = new Sidebar(this.$store);
        },
        initAdminLte: function() {
            AdminLTE.callOnce();
        },
        initApiManager: function() {
            Vue.prototype.$api = new Api(axios, this.$cookie);
        },
        initAxiosConfig: function() {
            Setting.setupAxios(axios, this.$router, this.$cookie, this.$flash);
        },
        initAccessControl: function() {
            Vue.prototype.$ac = new AccessControl(this.$store, this.$api);
            this.$ac.init();
            this.$ac.refresh();
        },
        initAppConfig: function() {
            Vue.prototype.$appConfig = new AppConfig(this.$store, this.$api);
            this.$appConfig.init();
            this.$appConfig.refresh();
        }
    }
});