<script>
export default {
    data: function() {
        return {
            data: {}
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

        vm.$loading.on('loading');

        vm.$loading.off('loading');
        // Add class to fix style
        var el = document.getElementById('app');
        el.classList.add('login-box');

        var elements = document.getElementsByTagName('body');
        var el = _.first(elements);
        el.setAttribute('style','height: auto; margin-top: 10%');

        vm.load();
    },
    methods: {
        load: async function() {
            var vm = this;

            vm.createEmptyData();

            vm.$loading.on('loading');

            var resp = await vm.$api.Config.all({});

            if (!resp.data.is_error) {
                var data = resp.data.data;

                data.file = [];
                if (data.logo) {
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
        createEmptyData: function() {
            var vm = this;

            var data = {
                email: null,
            };

            vm.data = Object.assign({}, data);
        },
        submit: async function() {
            var vm = this;

            vm.$loading.on('loading');

            var data = vm._createPostData(vm.data);
            var resp = await vm.$api.Auth.forgotPassword(data);

            if (!resp.data.is_error) {
                vm.$flash.success(resp.data.message);
            } else {
                vm.$flash.error(resp.data.message);
            }

            vm.$loading.off('loading');
        },
        _createPostData: function(data) {
            var vm = this;

            return data;
        }
    }
}
</script>
<template>
<div>
    <div class="login-logo" v-if="!data.logo">
        <router-link :to="{ url: '/' }"><b><label>{{data.company_name}}</label> {{ $config.DEV }}</b></router-link>
    </div>
    <!-- /.login-logo -->
    <div class="card">
        <box-overlay v-if="$loading.get('loading')" />
        <div class="card-body login-card-body">
            <p class="login-box-msg" v-if="data.logo">
                <img class="text-center" width="150px" :src="data.logo.url">
            </p>
            <p class="login-box-msg">Lupa Password</p>

            <form action="#" role="form" v-on:submit.prevent="submit()">
                <div class="input-group mb-3">
                    <input type="email" class="form-control" placeholder="Email" v-model="data.email">
                    <div class="input-group-append">
                        <div class="input-group-text">
                            <span class="fas fa-envelope"></span>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-12">
                        <button type="submit" class="btn btn-primary btn-block">Kirim Link Ubah Password</button>
                    </div>
                    <!-- /.col -->
                </div>

                <p class="mt-3 mb-1">
                    <router-link :to="{name: 'login'}">Login</router-link>
                </p>
            </form>
            <p style="margin-top:5px;">
                {{data.company_address}}
            </p>
            <!-- <p style="margin-top:-15px;">
                No. Telp: {{ data.company_phone }}
            </p>
            <p style="margin-top:-15px;">
                NPWP: {{data.company_npwp}}
            </p> -->
        </div>
        <!-- /.login-card-body -->
    </div>
</div>
<!-- /.login-box -->
</template>