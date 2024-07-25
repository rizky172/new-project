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

        // Add class to fix style
        var el = document.getElementById('app');
        el.classList.add('login-box');

        var elements = document.getElementsByTagName('body');
        var el = _.first(elements);
        el.setAttribute('style','height: auto; margin-top: 5%');

        vm.load();
    },
    methods: {
        createEmptyData: function() {
            var vm = this;

            var data = {
                hash: null,
                password: null,
                password_confirmation: null
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
            var resp = await vm.$api.Auth.resetPassword(data);

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
                hash: this.$route.query.hash,
                password: data.password,
                password_confirmation: data.password_confirmation
            };

            return _data;
        }
    }
}
</script>

<template>
    <div>
        <div class="login-logo">
            <router-link :to="{ url: '/' }"><b><label>{{data.company_name}}</label> {{ $config.DEV }}</b></router-link>
        </div>
        <!-- /.login-logo -->
        <wv-card v-bind:loading="$loading.get('loading')">
                <p class="login-box-msg" v-if="data.logo">
                    <img class="text-center" width="150px" :src="data.logo.url">
                </p>
                <p class="login-box-msg">Ubah Password</p>

                <form action="#" role="form" v-on:submit.prevent="submit()">
                    <wv-row>
                        <wv-text
                            type="password"
                            label="Password"
                            placeholder="Masukkan Password Baru"
                            v-bind:required="true"
                            v-model="data.password"
                            postfixIcon="fa-lock"></wv-text>
                        <wv-text
                            type="password"
                            label="Ketik kembali Password Baru"
                            placeholder="Masukkan kembali Password Baru"
                            v-bind:required="true"
                            v-model="data.password_confirmation"
                            postfixIcon="fa-lock"></wv-text>
                        <wv-col>
                            <button type="submit" class="btn btn-primary btn-block">Ubah Password</button>
                        </wv-col>
                    </wv-row>
                </form>

                <p class="mt-3 mb-1">
                    <router-link :to="{name: 'login'}">Login</router-link>
                </p>
        </wv-card>
    </div>
    <!-- /.login-box -->
</template>