<script>
export default {
    data: function() {
        return {
            data: {},
            isPasswordType: true,
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
        vm.$api.Config.all({}).then(function(resp) {
            if (!resp.data.is_error) {
                var data = resp.data.data;

                vm.data.logo = data.logo;
                vm.data.company_name = data.company_name;
                vm.data.company_address = data.company_address;
                vm.data.company_phone = data.company_phone;
                vm.data.company_npwp = data.company_npwp;
        } else {
            vm.$flash.error(resp.data.message);
        }

          vm.$forceUpdate();
          vm.$loading.off('loading');
        });
        // Add class to fix style
        var el = document.getElementById('app');
        el.classList.add('login-box');

        var elements = document.getElementsByTagName('body');
        var el = _.first(elements);
        el.setAttribute('style','height: auto; margin-top: 10%');
    },
    methods: {
        createEmptyData: function() {
            var vm = this;

            var data = {
                username: null,
                password: null,
                remember_me: null
            };

            vm.data = Object.assign({}, data);
        },
        submit: function() {
            var vm = this;

            vm.$loading.on('loading');

            var data = vm._createPostData(vm.data);
            vm.$api.Auth.login(data).then(function(resp) {
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
            });
        },
        _createPostData: function(data) {
            var vm = this;

            return data;
        },
        switchPasswordType: function() {
            var vm = this;

            vm.isPasswordType = !vm.isPasswordType;
        },
        getPasswordType: function() {
            var vm = this;

            if (vm.isPasswordType)
                return 'password';

            return 'text';
        }
    }
}
</script>
<template>
<div>
  <div class="login-logo">
    <router-link :to="{ url: '/' }"><b><label>{{data.company_name}}</label></b></router-link>
  </div>
  <!-- /.login-logo -->
  <div class="card">
  <box-overlay v-if="$loading.get('loading')" />
    <div class="card-body login-card-body">
      <p class="login-box-msg" v-if="data.logo">
        <img class="text-center" width="150px" :src="data.logo.url">
      </p>
      <p class="login-box-msg">Sign in to start your session</p>

      <form action="#" role="form" v-on:submit.prevent="submit()">
        <div class="input-group mb-3">
          <input type="text" class="form-control" placeholder="Username" v-model="data.username">
          <div class="input-group-append">
            <div class="input-group-text">
              <span class="fas fa-envelope"></span>
            </div>
          </div>
        </div>
        <div class="input-group mb-3">
          <input :type="getPasswordType()" class="form-control" placeholder="Password" v-model="data.password">
          <div class="input-group-append">
            <button type="button" class="btn btn-default ml-0" @click="switchPasswordType()">
              <i class="fas fa-eye" v-if="isPasswordType"></i>
              <i class="fas fa-eye-slash" v-if="!isPasswordType"></i>
            </button>
          </div>
        </div>
        <div class="row">
          <div class="col-12">
            <button type="submit" class="btn btn-primary btn-block">Sign In</button>
          </div>
          <!-- /.col -->
        </div>

        <!-- <p class="mb-1">
          <router-link :to="{name: 'forgot-password'}">I forgot my password</router-link>
        </p> -->

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