<script>
export default {
    data: function() {
        return {
            data: {},
        };
    },
    created: function() {
        var vm = this;

        if (vm.$api.getApiToken()) {
            vm.$router.push({ name: 'homepage'});
        }

        // Release lock when come to login page
        this.$flash.unlock();
    },
    mounted: function() {
        var vm = this;
        // console.log('test');
        // Add class to fix style
        var el = document.getElementById('app');
        el.classList.add('login-box');

        var elements = document.getElementsByTagName('body');
        var el = _.first(elements);
        el.setAttribute('style','height: auto; margin-top: 5%');

        vm.load();
    },
    watch: {
        $route (to, from){
          var vm = this;

          vm.data.affiliate = vm.$route.query.affiliate;
        }
    },
    methods: {
        createEmptyData: function() {
            var vm = this;

            var data = {
                name: null,
                email: null,
                phone: null,
                username: null,
                password: null,
                retype_password: null,
                affiliate: vm.$route.query.affiliate,
            };

            vm.data = Object.assign({}, data);
        },
        load: async function() {
            var vm = this;

            vm.createEmptyData();

            vm.$loading.on('loading');

            var resp = await vm.$api.Config.all({});

            if (!resp.data.is_error) {
                var data = resp.data.data;

                data.file = [];
                if (data.logo) {
                    data.file[0] = {
                        id: data.id,
                        url: data.logo,
                        _hash: Math.getRandomHash()
                    };
                    vm.data.logo = data.logo;
                    vm.data.company_name = data.company_name;
                    vm.data.company_address = data.company_address;
                    vm.data.company_phone = data.company_phone;
                    vm.data.company_npwp = data.company_npwp;
                }
            } else {
                vm.$flash.error(resp.data.message);
            }

            vm.$forceUpdate();
            vm.$loading.off('loading');
        },
        submit: async function() {
            var vm = this;

            vm.$loading.on('loading');

            var data = vm._createPostData(vm.data);
            var resp = await vm.$api.Auth.register(data)

            if(!resp.data.is_error) {
                vm.$api.setApiToken(resp.data.data);
                vm.$ac.refresh();
                vm.$appConfig.refresh();
                vm.$flash.success(resp.data.message);

                var el = document.getElementById('app');
                el.classList.remove('login-box');
                vm.$router.push({ path: 'landing-page'});
            } else {
                vm.$flash.unlock();
                vm.$flash.error(resp.data.message);
            }

            vm.$loading.off('loading');
        },
        _createPostData: function(data) {
            var _data = {
                name: data.name,
                email: data.email,
                phone: data.phone,
                username: data.username,
                password: data.password,
                retype_password: data.retype_password,
                affiliate: data.affiliate
            };

            return _data;
        }
    }
}
</script>

<template>
    <div>
        <div class="login-logo" :if="!data.logo">
            <router-link :to="{ url: '/' }"><b><label>{{data.company_name}}</label> {{ $config.DEV }}</b></router-link>
        </div>
        <!-- /.login-logo -->
        <wv-card v-bind:loading="$loading.get('loading')">
                <p class="login-box-msg" v-if="data.logo">
                    <img class="text-center" width="150px" :src="data.logo.url">
                </p>
                <p class="login-box-msg">Sign up to start your session</p>

                <form action="#" role="form" v-on:submit.prevent="submit()">
                    <wv-row>
                        <wv-col>
                            <p class="text-center">- Biodata -</p></wv-col>
                        <wv-text
                            label="Nama"
                            placeholder="Masukkan Nama"
                            v-bind:required="true"
                            v-model="data.name"
                            postfixIcon="fa-user"></wv-text>
                        <wv-text
                            label="Email"
                            placeholder="Masukkan Email"
                            type="email"
                            v-model="data.email"
                            v-bind:required="true"
                            postfixIcon="fa-envelope"></wv-text>
                        <wv-text
                            label="No. Telp"
                            placeholder="Masukkan No. Telp"
                            v-bind:required="true"
                            v-model="data.phone"
                            postfixIcon="fa-phone"></wv-text>
                        <wv-col>
                            <p class="text-center">- Akun -</p></wv-col>
                        <wv-text
                            label="Username"
                            placeholder="Masukkan Username"
                            v-bind:required="true"
                            v-model="data.username"
                            postfixIcon="fa-user"></wv-text>
                        <wv-text
                            type="password"
                            label="Password"
                            placeholder="Masukkan Password"
                            v-bind:required="true"
                            v-model="data.password"
                            postfixIcon="fa-lock"></wv-text>
                        <wv-text
                            type="password"
                            label="Ketik Ulang Password"
                            placeholder="Masukkan Ulang Password"
                            v-bind:required="true"
                            v-model="data.retype_password"
                            postfixIcon="fa-lock"></wv-text>
                        <wv-text
                            label="Kode Affiliate"
                            placeholder="Masukkan Kode Affiliate"
                            :disabled="$route.query.affiliate ? true : false"
                            v-model="data.affiliate"></wv-text>
                        <wv-col>
                            <button type="submit" class="btn btn-primary btn-block">Sign Up</button>
                            <p class="mt-3 mb-1">
                                <router-link :to="{name: 'login'}">Login</router-link>
                            </p>
                        </wv-col>
                    </wv-row>
                </form>
        </wv-card>
    </div>
    <!-- /.login-box -->
</template>