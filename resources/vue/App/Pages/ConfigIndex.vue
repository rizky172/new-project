<script>

export default {
    data: function() {
        return {
            data: {},
            logo: {
                url: null,
                file: []
            },
            options: {
                permission_group: undefined,
            }
        };
    },
    created: function() {
        this.$appConfig.stopRefresh();
    },
    mounted: function() {
        var vm = this;

        vm.loadCategory();

        vm.createEmptyData();
        vm.create();
    },
    methods: {
        createEmptyData: function() {
            var vm = this;

            var data = {
                company_name: null,
                company_address: null,
                company_email: null,
                company_phone: null,

                logo: [],

                _delete_logo: [],
            };

            vm.data = Object.assign({}, data);
        },
        create: async function() {
            var vm = this;
            vm.$loading.on('loading');
            var resp = await vm.$api.Config.all({});

            if (!resp.data.is_error) {
                var data = resp.data.data;

                //delete soon
                data.file = [];
                data._delete_file = [];
                // --delete soon

                vm.logo.url = data.logo.url;

                var config = Object.assign({}, data);
                vm.$appConfig.setData(config);

                if(!Array.isArray(data))
                    vm.data = Object.assign({}, data);

                // console.log('from create method', vm.data);
            } else {
                vm.$flash.error(resp.data.message);
            }

            vm.$loading.off('loading');

        },
        submit: async function() {
            var vm = this;

            vm.$loading.on('loading');

            var data = vm._createPostData(vm.data);

            // console.log(data);
            var resp = await vm.$api.Config.create(data);

            if(!resp.data.is_error) {
                vm.create();

                vm.$flash.success(resp.data.message);
            } else {
                vm.$flash.error(resp.data.message);
            }

            vm.$loading.off('loading');
        },
        _createPostData: function(data) {
            var vm = this;

            data.files = {
                logo: vm.logo.file
            };

            return data;
        },
        addLogo: function(event) {
            var vm = this;
            var logo = vm.logo;

            if (logo.file.length > 0)
                if (typeof logo.file[0].id !== 'undefined' && logo.file[0].id !== null && !logo._delete_file.includes(logo.file[0].id))
                    vm.logo._delete_file.push(logo.file[0].id);

            var file = event.target.files[0];
            file.value = file.name;
            file._hash = Math.getRandomHash();

            logo.file[0] = file;

            var elLogo = this.$refs.fileLogoInput;
            elLogo.value = '';

            vm.logo = Object.assign({}, logo);
        },
        deleteFile: function(file) {
            var vm = this;

            // console.log('data yang ingin di delete', file);

            vm.data._delete_file.push(vm.data.file[0].id);
            vm.data.file.splice(0, 1);
            vm.data.logo = null;
        },
        loadCategory: async function() {
            var vm = this;

            var resp = await vm.$api.Category.all({ page: 'all', group_by:'permission_group'});
            var data = resp.data.data;

            var category = _.map(data, function(row) {

                return {
                    id: row.id,
                    text: row.name,
                    group_by: row.group_by
                }
            });

            vm.options.permission_group = _.where(category, {group_by: 'permission_group'});
        },
    }
}
</script>
<template>
    <section class="content">
        <div class="container-fluid">
            <wv-row>
                <wv-col>
                    <div class="card card-default">
                        <box-overlay v-if="$loading.get('loading')" />
                        <div class="card-header">
                            <h3 class="card-title">Biodata Perusahaan</h3>
                        </div> <!-- card-header -->
                        <form action="#" role="form" v-on:submit.prevent="submit()">
                            <div class="card-body">
                                <wv-row>
                                    <wv-text
                                        label="Nama"
                                        placeholder="Masukan Nama Perusahaan"
                                        v-bind:col="12"
                                        v-model="data.company_name">
                                    </wv-text>
                                    <wv-text
                                        label="Email"
                                        placeholder="Masukan Email Perusahaan"
                                        v-bind:col="12"
                                        v-model="data.company_email">
                                    </wv-text>
                                    <wv-text
                                        label="No. Telp"
                                        placeholder="Masukan No. Telepon Perusahaan"
                                        v-bind:col="12"
                                        v-model="data.company_phone">
                                    </wv-text>
                                    <wv-textarea
                                        label="Alamat"
                                        placeholder="Masukan Alamat Perusahaan"
                                        v-bind:col="12"
                                        v-model="data.company_address">
                                    </wv-textarea>
                                    <div class="form-group col-sm-12">
                                        <label>Logo</label><br>
                                        <img :src="logo.url" :alt="logo.url" class="my-3" height="100px" width="auto" v-if="logo.url != null"><br>
                                        <div class="custom-file">
                                            <input type="file" class="custom-file-input" id="logoImg" name="upload" v-on:change="addLogo($event)" ref="fileLogoInput">
                                            <label
                                                class="custom-file-label"
                                                for="logoImg">
                                                <span v-if="logo.file && logo.file.length > 0 && typeof logo.file[0].value !== 'undefined'">
                                                    {{ logo.file[0].value }}
                                                </span></label>
                                        </div>
                                        <small>Resolusi: 512x512px</small>
                                    </div>
                                </wv-row>
                            </div>
                            <div class="card-body">
                                <wv-row>
                                    <wv-col class="text-right" v-if="$ac.hasAccess('setting_create')">
                                        <wv-cancel-button v-on:click="create()"></wv-cancel-button>
                                        <wv-submit-button></wv-submit-button>
                                    </wv-col>
                                </wv-row>
                            </div>
                        </form>
                    </div> <!-- card card-default -->
                </wv-col>
            </wv-row>
        </div>
    </section>
</template>
