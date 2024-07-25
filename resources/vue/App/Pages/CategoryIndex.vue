<script>
import MixinList from '../Mixins/MixinList.js';
import SidebarDetail from './Sidebar/SidebarCategory.vue';

export default {
    mixins: [MixinList],
    data: function() {
        return {
            routeName: null, // Updated based on route name

            sidebarDetail: SidebarDetail,
            detail: {},
            title: null
        }
    },
    created: function() {
        this.$sidebar.setComponent(SidebarDetail);
    },
    mounted: function() {
        var vm = this;

        vm.newDetail();
    },
    watch:{
        // Call when $route.query changed
        '$route': function() {
            var vm = this;

            this.load();
        }
    },
    computed: {
        hasAccessCreate: function() {
            var name = this.$route.name;
            var permission = 'category_' + name + '_create';

            if (name == 'item-category')
                permission = 'category_item_create';

            return this.$ac.hasAccess(permission)
        },
        hasAccessDelete: function() {
            var name = this.$route.name;
            var permission = 'category_' + name + '_delete';

            return this.$ac.hasAccess(permission)
        }
    },
    methods: {
        // ==========
        // Overide from mixin
        _search: async function(search) {
            return await this.$api.Category.all(search);
        },
        _delete: async function(params) {
            return await this.$api.Category.delete(params.id)
        },
        _restore: async function(id) {
            return await this.$api.Category.restore(id);
        },
        _beforeSearch: function(search) {
            search.group_by = this.$route.name;

            if (this.$route.name == 'item-category')
                search.group_by = 'item';

            // console.log(search.group_by);
            search.page = 'all';

            this.routeName = this.$route.name;
        },
        // ==========

        newDetail: function() {
            var vm = this;

            var detail = {
                id: null,
                item_id: null,
                name: null,
                label: null,
                coa_id: null,
                notes: null,
                level: null
            };

            vm.detail = Object.assign({}, detail);
        },
        addDetail: async function(detail) {
            var vm = this;

            vm.$loading.on('loading');

            detail.group_by = vm.$route.name;

            if (vm.$route.name == 'item-category')
                detail.group_by = 'item';

            var resp = await vm.$api.Category.create(detail);

            if(!resp.data.is_error) {
                vm._doSearch();

                vm.$flash.success(resp.data.message);
            } else {
                vm.$flash.error(resp.data.message);
            }

            vm.newDetail();
            vm.$loading.off('loading');
        },
    }
}
</script>

<template>
    <section class="content">
        <div class="container-fluid">
            <wv-row>
                <wv-col>
                    <wv-card title="Pencarian">
                        <form action="#" role="form" v-on:submit.prevent="doSearch()">
                            <wv-row>
                                <wv-text
                                    label="Kata Kunci"
                                    placeholder="Masukan Kata Kunci"
                                    v-model="search.keyword"></wv-text>
                                <wv-col class="text-right">
                                    <wv-cancel-button v-on:click="clear()"></wv-cancel-button>
                                    <wv-submit-button>Cari</wv-submit-button>
                                </wv-col>
                            </wv-row>
                        </form>
                    </wv-card>
                </wv-col>

                <wv-col>
                    <wv-card v-bind:loading="$loading.get('loading')">
                        <wv-row>
                            <wv-col>
                                <sidebar-button
                                    v-if="hasAccessCreate"
                                    class="btn-primary"
                                    v-bind:sidebar="sidebarDetail"
                                    v-model="detail"
                                    v-bind:success="addDetail">Tambah {{ title }}</sidebar-button>
                            </wv-col>
                            <wv-col class="mt-3">
                                <div class="table-responsive">
                                    <table class="table table-bordered">
                                        <thead>
                                            <tr>
                                                <th style="width: 10px">#</th>
                                                <th>Nama</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr v-for="(tr, $index) in list">
                                                <td>{{ parseInt($index) + 1 }}</td>
                                                <td>{{ tr.label }}</td>
                                                <td>
                                                    <sidebar-button title="Ubah" class="btn-warning"
                                                    v-bind:sidebar="sidebarDetail"
                                                    v-model="list[$index]"
                                                    v-bind:success="addDetail">
                                                    <i class="fas fa-pencil-alt"></i>
                                                    </sidebar-button>
                                                    <button-confirm
                                                        v-if="hasAccessDelete"
                                                        title="Hapus"
                                                        @click="destroy(tr.id)"
                                                        :disabled="$loading.get('loading-delete-' + tr.id)"
                                                        :message="$confirm.delete"></button-confirm>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </wv-col>
                        </wv-row>
                    </wv-card>
                </wv-col>
            </wv-row>
        </div>
    </section>
</template>
