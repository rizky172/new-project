<script>
export default {
    data: function() {
        return {
            data: {},
            options: {
              permission_group: undefined
            }
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
    computed: {
        permissionString: function() {
            if(!this.data.permission)
                return null;

            return this.data.permission.join(', ');
        },
        permissionGroupString: function() {
            if(!this.data.permission_group)
                return null;

            return this.data.permission_group.join(', ');
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

            vm.loadCategory();
        },
        loadCategory: async function() {
            var vm = this;

            var resp = await vm.$api.Category.all({ page: 'all', group_by:'permission_group'});
            var data = resp.data.data;

            var category = _.map(data, function(row) {
                return {
                    id: row.id,
                    text:row.name,
                    value: row.id,
                }
            });

            vm.options.permission_group = category;
        },
        createEmptyData: function() {
            var vm = this;

            var data = {
                id: null,
                person_id: null,

                name: null,
                username: null,
                email: null,
                password: null,
                retype_password: null,
                permission_group_id: []

            };

            vm.data = Object.assign({}, data);
        },
        create: async function() {
            var vm = this;
            vm.createEmptyData();
            vm.$loading.on('loading');

            var resp = await vm.$api.User.get(vm.$route.params.id);

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
            var resp = await vm.$api.User.create(data);

            if(!resp.data.is_error) {
                var user = vm.$ac.getUser();
                if(user.id == vm.$route.params.id) {
                    // Update user information
                    vm.$ac.refresh();
                }

                if (vm.$route.params.id) {
                    vm.create();
                } else {
                    vm.$router.push({ name: 'admin-detail', params: { id: resp.data.data } });
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

            delete _data.permission;

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
                    <wv-tab-group v-bind:loading="$loading.get('loading')">
                        <wv-tab title="Detail">
                            <form action="#" role="form" v-on:submit.prevent="submit()">
                                <wv-row>
                                    <wv-text
                                        label="Nama"
                                        placeholder="Masukan Nama"
                                        v-bind:col="6"
                                        v-bind:required="true"
                                        v-model="data.name"></wv-text>
                                    <wv-text
                                        type="email"
                                        label="Email"
                                        placeholder="Masukan Email"
                                        v-bind:col="6"
                                        v-bind:required="true"
                                        v-model="data.email"></wv-text>
                                    <wv-text
                                        label="Username"
                                        placeholder="Masukan Username"
                                        v-bind:col="4"
                                        v-bind:required="true"
                                        v-model="data.username"></wv-text>
                                    <wv-text
                                        type="password"
                                        label="Password"
                                        placeholder="Masukan Password"
                                        v-bind:col="4"
                                        v-bind:required="!data.id"
                                        v-model="data.password"></wv-text>
                                    <wv-text
                                        type="password"
                                        label="Ketik Ulang Password"
                                        placeholder="Ketik Ulang Password"
                                        v-bind:col="4"
                                        v-bind:required="!data.id || data.password"
                                        v-model="data.retype_password"></wv-text>
                                    <wv-group
                                        label="Hak Akses">
                                        <span>{{ permissionGroupString }}</span>
                                    </wv-group>

                                    <wv-group
                                        label="Akses">
                                        <span>{{ permissionString }}</span>
                                    </wv-group>

                                    <wv-col>
                                        <div class="text-right" v-if="$ac.hasAccess('admin_create')">
                                            <wv-cancel-button v-on:click="load()"></wv-cancel-button>
                                            <wv-submit-button></wv-submit-button>
                                        </div>
                                    </wv-col>
                                </wv-row>
                            </form>
                        </wv-tab>

                        <wv-tab title="Hak Akses" v-bind:isShow="data.id ? true : false">
                            <form action="#" role="form" v-on:submit.prevent="submit()">
                                <wv-row>
                                    <wv-select2
                                        label="Hak Akses"
                                        placeholder="Pilih Hak Akses"
                                        v-bind:col="12"
                                        v-bind:tags="true"
                                        v-bind:options="options.permission_group"
                                        v-model="data.permission_group_id"></wv-select2>
                                    <wv-col>
                                        <div class="text-right" v-if="$ac.hasAccess('admin_create')">
                                            <wv-cancel-button v-on:click="load()"></wv-cancel-button>
                                            <wv-submit-button>Simpan</wv-submit-button>
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