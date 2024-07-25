<script>
import MixinList from '../Mixins/MixinList.js';

export default {
  mixins: [MixinList],
  data: function() {
      return {
          routeName: 'hak-akses',
      }
  },
  methods: {
        // ==========
        // Overide from mixin
        _search: async function(search) {
            return await this.$api.Category.all(search);
        },
        _delete: async function(params) {
            return await this.$api.PermissionGroup.delete(params.id)
        },
        _restore: async function(id) {
            return await this.$api.Category.restore(id);
        },
        _beforeSearch: function(search) {
            search.page = 'all';
            search.group_by = 'permission_group';

            // return search;
        },
        // ==========
        exportXls: function() {
            var vm = this;

            vm.$loading.on('loading-export');
            vm.$api.PermissionGroup.export(vm.search).then(function(resp) {
                if(!resp.data.is_error) {
                    var data = resp.data;

                    if(!data.is_error)
                        window.open(data.data.url);

                } else {
                    vm.$flash.error(resp.data.message);
                }

                vm.$loading.off('loading-export');
            });
        },
        importXls: function() {
            var el = this.$refs.fileInput;

            // Trigger element to open upload dialog
            el.click();
        },
        handleFileChange: function(e) {
            var vm = this;
            var file = e.target.files[0];
            var el = this.$refs.fileInput;

            // Reset to null again
            el.value = '';

            vm.$loading.on('loading-import');
            vm.$api.PermissionGroup.import({file: file}).then(function(resp) {
                if(!resp.data.is_error) {
                    var data = resp.data;

                    if(!data.is_error)
                        vm._doSearch();

                    vm.$flash.success(resp.data.message);
                } else {
                    vm.$flash.error(resp.data.message);
                }

                vm.$loading.off('loading-import');
            });
        }
    }
}
</script>

<template>
    <section class="content">
      <div class="container-fluid">
        <div class="row">
          <div class="col-md-12">
            <div class="card card-default">
              <box-overlay v-if="$loading.get('loading')" />
                <div class="card-body">
                  <div class="row">
                    <div class="col-sm-12">
                      <router-link v-if="$ac.hasAccess('access_create')" class="btn btn-primary" :to="{name: 'hak-akses-create'}"><i class="fas fa-plus"></i> Tambah</router-link>
                      <button class="btn btn-primary" v-if="$ac.hasAccess('access_create')" @click="exportXls" :disabled="$loading.get('loading-export')"><i class="fas fa-file-excel"></i> Export(xls) <spin v-if="$loading.get('loading-export')"></spin></button>
                      <button class="btn btn-primary" v-if="$ac.hasAccess('access_create')" @click="importXls" :disabled="$loading.get('loading-import')"><i class="fas fa-file-excel"></i> Import(xls) <spin v-if="$loading.get('loading-import')"></spin></button>
                      <input type="file" style="display: none;" @change="handleFileChange" ref="fileInput">
                    </div>
                    <div class="col-sm-12 mt-3">
                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <thead>
                                <tr>
                                    <th style="width: 10px">#</th>
                                    <th>Name Hak Akses</th>
                                    <th>Action</th>
                                </tr>
                                </thead>
                                <tbody>
                                <tr v-for="(tr, $index) in list">
                                    <td>{{ parseInt($index) + 1 }}</td>
                                    <td>{{ tr.label }}</td>
                                    <td>
                                    <router-link v-if="$ac.hasAccess('access_read')" title="Ubah" class="btn btn-warning" :to="{name: 'hak-akses-detail', params: {id: tr.id}}"><i class="fas fa-pencil-alt"></i></router-link>
                                    <button-confirm v-if="$ac.hasAccess('access_delete')" title="Hapus"
                                        @click="destroy(tr.id)"
                                        v-bind:disabled="$loading.get('loading-delete-' + tr.id)"
                                        v-bind:message="$confirm.delete">
                                    </button-confirm>
                                    </td>
                                </tr>
                                </tbody>
                            </table>
                      </div>
                    </div>
                  </div>
                </div>
            </div>
          </div>
        </div>
      </div>
    </section>
</template>
