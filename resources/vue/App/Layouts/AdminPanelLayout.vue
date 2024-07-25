<script>
import AdminLTE from '../../AdminLte/AdminLTE.js';
import AppHeader from '../Partials/Header.vue';
import AppNav from '../Partials/Nav.vue';
import AppFooter from '../Partials/Footer.vue';

export default {
    components: {
        'app-header': AppHeader,
        'app-nav': AppNav,
        'app-footer': AppFooter
    },
    data: function() {
        return {
            isShow: true,
            popup: [],
            popUpTitle: 'Example Popup'
        };
    },
    watch: {
        '$route.query': function() {
            // console.log('route change', this.$route.meta);
            this.onChangeRoute();
        }
    },
    created: function() {
        // console.log('route', this.$route.meta, this.$store);
        // console.log('store before', this.$store.state.app.breadcrumb);
        this.onChangeRoute();
        // console.log('store after', this.$store.state.app.breadcrumb);
    },
    mounted: function() {
        var vm = this;

        this.init();

        // console.log('admin', AdminLTE);
        if(this.$store.state.app.counter <= 0);
            // This must be called only once
            AdminLTE.callTreeview();

        // console.log('admin layout', this);
        this.$store.dispatch('app/increaseCounter');

        if (this.$cookie.get('is_first_login') == 1) {
            // this.loadPopUp();
        }

    },
    methods: {
        init: function() {
            var vm = this;

            document.body.className = 'sidebar-mini layout-fixed';
            document.body.removeAttribute('style');

            var el = document.getElementById('app');
            el.classList.remove('login-box');
        },
        onChangeRoute: function() {
            this.$routerMetaHandler.updateBreadcrumb(this.$route.meta.breadcrumb);
            this.$routerMetaHandler.updateTitle(this.$route.meta.pageTitle);
        },
        upgradeMembership: async function () {
            var vm = this;
            vm.$loading.on('loading-upgrade');

            var person_id = vm.$ac.getUser().person_id;

            var resp = await vm.$api.Cart.addMembership(person_id);

            if(!resp.data.is_error) {
                vm.$router.push({ name: 'cart', query: { person_id: person_id }});
                vm.$flash.success(resp.data.message);
            } else {
                vm.$flash.error(resp.data.message);
            }

            vm.$loading.off('loading-upgrade');
        },
        loadPopUp: async function () {
            var vm = this;

            var resp = await vm.$api.PopUp.getPopUp();
            var data = resp.data.data;

            if(data.length > 0){
                vm.popup = data;

                this.$store.dispatch('app/popup', true);
                this.$cookie.set('is_first_login', 0);
            }
        }
    },
    computed: {
        meta: function () {
            var vm = this;

            return {
                breadcrumb: vm.$store.state.app.breadcrumb,
                pageTitle: vm.$store.state.app.pageTitle
            };
        }
    }
}
</script>

<template>
<div class="wrapper">
    <app-header></app-header>
    <app-nav></app-nav>

    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <section class="content-header" v-if="$ac.getUser().type == 'free'">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-12">
                        <div class="callout callout-danger top-notif">
                            <p class="text-bold">Kamu adalah FREE member. Lakukan <span @click="upgradeMembership()" class="text-success" style="cursor: pointer;">Upgrade Sekarang</span>. <a target="_blank" :href="$ac.getUser().benefit_partner_link">Baca Benefitnya</a></p>
                            </div>
                    </div>
                </div>
            </div>
        </section>
        <breadcrumb v-model="meta"></breadcrumb>
        <!-- Content goes here: Table, form, report, etc -->
        <router-view></router-view>
    </div>
    <app-footer></app-footer>

  <!-- Control Sidebar -->
  <aside class="control-sidebar control-sidebar-dark">
    <!-- Control sidebar content goes here -->
  </aside>
  <!-- /.control-sidebar -->

  <sidebar-root></sidebar-root>
  <pop-up v-bind:title="popUpTitle" v-bind:image="popup"></pop-up>
</div>
<!-- ./wrapper -->
</template>