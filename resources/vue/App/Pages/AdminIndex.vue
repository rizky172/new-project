<script>
import MixinList from '../Mixins/MixinList.js';

export default {
  mixins: [MixinList],
  data: function() {
      return {
          routeName: 'admin'
      }
  },
  methods: {
        // ==========
        // Overide from mixin
        _search: async function() {
            return await this.$api.User.all(this.search);
        },
        _delete: async function(params) {
            return await this.$api.User.delete(params)
        },
        _restore: async function(id) {
            return await this.$api.User.restore(id);
        },
        // ==========
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
                                <router-link v-if="$ac.hasAccess('admin_create')" class="btn btn-primary" :to="{name: 'admin-create'}"><i class="fas fa-plus"></i> Tambah</router-link>
                                <router-link
                                    class="btn btn-danger float-right"
                                    :to="{name: 'admin', query: {page: 1, deleted: 1}}"
                                    v-if="(search.deleted == 0 || search.deleted == null) && ($ac.hasAccess('admin_delete') && $ac.hasAccess('admin_full_access'))">
                                    <i class="fa fa-trash"></i> Sampah</router-link>
                                <router-link
                                    class="btn btn-success float-right"
                                    :to="{name: 'admin', query: {page: 1, deleted: 0}}"
                                    v-if="search.deleted == 1">
                                    <i class="fa fa-list"></i> List</router-link>
                            </wv-col>
                            <wv-col class="mt-3">
                                <div class="table-responsive">
                                    <table class="table table-bordered">
                                        <thead>
                                            <tr>
                                                <th style="width: 10px">#</th>
                                                <th>Name</th>
                                                <th>Email</th>
                                                <th>Username</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr v-for="(tr, $index) in list" :key="tr.id">
                                                <td>{{ parseInt($index) + 1 }}</td>
                                                <td>{{ tr.name }}</td>
                                                <td>{{ tr.email }}</td>
                                                <td>{{ tr.username }}</td>
                                                <td>
                                                    <!-- <router-link v-if="$ac.hasAccess('admin_read')" title="Ubah" class="btn btn-warning" :to="{name: 'admin-detail', params: {id: tr.id}}"><i class="fas fa-pencil-alt"></i></router-link>
                                                    <button-confirm v-if="$ac.hasAccess('admin_delete')" title="Hapus"
                                                        @click="destroy(tr.id)"
                                                        v-bind:disabled="$loading.get('loading-delete-' + tr.id)"
                                                        v-bind:message="$confirm.delete">
                                                    </button-confirm> -->
                                                    <template v-if="search.deleted == 0 || search.deleted == null">
                                                        <router-link
                                                            v-if="$ac.hasAccess('admin_read')"
                                                            title="Ubah"
                                                            class="btn btn-warning"
                                                            :to="{name: 'admin-detail', params: {id: tr.id}}">
                                                            <i class="fas fa-pencil-alt"></i></router-link>
                                                        <button-confirm
                                                            v-if="$ac.hasAccess('admin_delete')"
                                                            title="Hapus"
                                                            @click="destroy(tr.id)"
                                                            v-bind:disabled="$loading.get('loading-delete-' + tr.id)"
                                                            v-bind:message="$confirm.delete">
                                                            </button-confirm>
                                                    </template>
                                                    <template v-if="search.deleted == 1">
                                                            <button-confirm
                                                                v-if="$ac.hasAccess('admin_delete')"
                                                                title="Dipulihkan"
                                                                @click="restore(tr.id)"
                                                                v-bind:disabled="$loading.get('loading-delete-' + tr.id)"
                                                                v-bind:message="$confirm.restore"
                                                                v-bind:icon="'fas fa-trash-restore'"
                                                                v-bind:color="'btn-success'">
                                                                </button-confirm>
                                                            <button-confirm
                                                                title="Hapus Permanent"
                                                                @click="destroy(tr.id, 'permanent')"
                                                                v-bind:disabled="$loading.get('loading-delete-' + tr.id)"
                                                                v-bind:message="$confirm.deletePermanent">
                                                                </button-confirm>
                                                    </template>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </wv-col>
                            <wv-col>
                                <paging v-bind:model="paginator" v-on:goto="goto"/>
                            </wv-col>
                        </wv-row>
                    </wv-card>
                </wv-col>
            </wv-row>
        </div>
    </section>
</template>
