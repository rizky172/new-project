<script>
export default {
    data: function() {
        return {
            data: {},
        };
    },
    mounted: function() {
      var vm = this;

      vm.load();
    },
    watch: {
        $route (to, from){
          var vm = this;

          vm.load();
        }
    },
    methods: {
        load: function() {
            //this method for formatting data based on route to avoid undefined
            var vm = this;

            if (vm.$route.params.id) {
                vm.create();
            } else {
                vm.createEmptyData();
            }
        },
        createEmptyData: function() {
            var vm = this;

            var data = {
                id: null,
                person_id: null,

                name: null,
                username: null,
                email: null,
                phone: null,
                password: null,
                retype_password: null,
            };

            vm.data = Object.assign({}, data);
        },
        create: async function() {
            var vm = this;
            vm.createEmptyData();
            vm.$loading.on('loading');

            var resp = await vm.$api.Profile.get(vm.$route.params.id);

            if (!resp.data.is_error) {
                var data = resp.data.data;

                vm.data = Object.assign({}, data);
            } else {
                vm.$flash.error(resp.data.message);
            }

            vm.$loading.off('loading');
        },
        submit: async function() {
            var vm = this;
            vm.$loading.on('loading');

            var data = vm._createPostData(vm.data);
            var resp = await vm.$api.Profile.create(data);

            if(!resp.data.is_error) {
                var user = vm.$ac.getUser();
                if(user.id == vm.$route.params.id) {
                    // Update user information
                    vm.$ac.refresh();
                }

                if (vm.$route.params.id) {
                    vm.create();
                } else {
                    vm.$router.push({ name: 'profile-detail', params: { id: resp.data.data } });
                }

                vm.$flash.success(resp.data.message);
            } else {
                vm.$flash.error(resp.data.message);
            }

            vm.$loading.off('loading');
        },
        _createPostData: function(data) {
            var vm = this;
            var _data = Object.assign({}, data);

            return _data;
        },
    }
}
</script>

<template>
    <section class="content">
        <div class="container-fluid">
            <wv-row>
                <wv-col>
                    <wv-tab-group :loading="$loading.get('loading')">
                        <wv-tab title="Detail">
                            <form action="#" role="form" v-on:submit.prevent="submit()">
                                <wv-row>
                                    <wv-text
                                        label="Nama"
                                        placeholder="Masukkan Nama"
                                        :col="4"
                                        :required="true"
                                        v-model="data.name"></wv-text>
                                    <wv-text
                                        type="email"
                                        label="Email"
                                        placeholder="Masukkan Email"
                                        :col="4"
                                        :required="true"
                                        v-model="data.email"></wv-text>
                                    <wv-text
                                        label="No. Telp"
                                        placeholder="Masukkan No. Telp"
                                        :col="4"
                                        :required="true"
                                        v-model="data.phone"></wv-text>
                                    <wv-text
                                        label="Username"
                                        placeholder="Masukkan Username"
                                        :col="4"
                                        :required="true"
                                        :disabled="data.id ? true : false"
                                        v-model="data.username"></wv-text>
                                    <wv-text
                                        type="password"
                                        label="Password"
                                        placeholder="Masukkan Password"
                                        :col="4"
                                        :required="!data.id"
                                        v-model="data.password"></wv-text>
                                    <wv-text
                                        type="password"
                                        label="Ketik Ulang Password"
                                        placeholder="Masukkan Ulang Password"
                                        :col="4"
                                        :required="!data.id || data.password"
                                        v-model="data.retype_password"></wv-text>
                                    <wv-col>
                                        <div class="text-right" v-if="$ac.hasAccess('profile_create')">
                                            <wv-cancel-button v-on:click="load()"></wv-cancel-button>
                                            <wv-submit-button></wv-submit-button>
                                        </div>
                                    </wv-col>
                                </wv-row>
                            </form>
                        </wv-tab>
                    </wv-tab-group>
                </wv-col>
            </wv-row>
        </div>
    </section>
</template>