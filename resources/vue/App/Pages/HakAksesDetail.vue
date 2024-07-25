<script>

export default {
    data: function() {
        return {
            permission: [],
            isBulkChecked: 0,
            filter: {
              category: '',
              keyword: ''
            },
            data: {},
        };
    },
    created: function() {
    },
    mounted: function() {
        var vm = this;

        vm.load();
    },
    computed: {
      permissionString: function() {
        var vm = this;
        var stringCode = [];

        stringCode =  vm.permission.filter(function(item) {
          return item.isChecked == 1;
        });

        stringCode = _.map(stringCode, 'name');

        return stringCode.join(", ");;

      },
      filteredPermissions: function() {
        var vm = this;
        var category =  vm.filter.category;
        var keyword =  vm.filter.keyword;
        var filtered = vm.permission;

        if(category) {
          filtered =  filtered.filter(function(item) {
            if(category == 'checked') {
              return item.isChecked == 1;
            } else {
              return item.isChecked == 0;
            }
          });
        }

        if(keyword) {
          filtered = filtered.filter(function(item) {
            return (item.name.toLowerCase().includes(keyword) || item.id.toString().includes(keyword)
              || item.notes.toLowerCase().includes(keyword));
          });
        }

        return filtered;
      }
    },
    watch: {
        $route: function(to, from){
          var vm = this;

          vm.load();
        },
        isBulkChecked: function() {
          this.bulkCheck();
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
                vm.loadCategory();
            }
        },
        createEmptyData: function() {
            var vm = this;

            var data = {
                id: null,
                ref_no: null,
                name: null,
                detail: []
            };

            vm.data = Object.assign({}, data);
        },
        create: async function() {
            var vm = this;
            vm.createEmptyData();

            vm.$loading.on('loading');
            var resp = await vm.$api.PermissionGroup.get(vm.$route.params.id);

            if (!resp.data.is_error) {
                var data = resp.data.data;

                vm.data = Object.assign({}, data);
                vm.loadCategory();

                // console.log('create', vm.data);
            } else {
                vm.$flash.error(resp.data.message);
            }

            vm.$loading.off('loading');
        },
        submit: async function() {
            var vm = this;

            vm.$loading.on('loading');

            var data = vm._createPostData(vm.data);

            var resp = await vm.$api.PermissionGroup.create(data);

            if(!resp.data.is_error) {
                vm.$ac.refresh();
                if (vm.$route.params.id) {
                    vm.create();
                } else {
                    vm.$router.push({ name: 'hak-akses-detail', params: { id: resp.data.data } });
                }

                vm.$flash.success(resp.data.message);
            } else {
                vm.$flash.error(resp.data.message);
            }

            vm.$loading.off('loading');
        },
        _createPostData: function(data) {
            var vm = this;

            // clone the data
            var _data = Object.assign({}, data);

            _data.permission_id = [];
            _data.delete_permission_id = [];

            vm.permission.forEach(function(item, index, arr) {
              if (item.isChecked == 1) {
                _data.permission_id.push(item.id);
              }
            });

            delete _data.detail;

            return _data;
        },
        loadCategory: async function() {
            var vm = this;

            var resp = await vm.$api.Category.all({page: 'all', group_by: 'permission'});

            var data = resp.data.data;

            var consicedData = _.map(data, function(row) {
                        return { id: row.id, name: row.name, notes: row.notes, isChecked: 0 };
                      });

            vm.permission = consicedData;

            if(vm.$route.params.id) {

              vm.permission.forEach(function(item, index, arr) {
                if(vm.data.detail.length > 0) {
                  vm.data.detail.forEach(function(itemDetail, indexDetail, arrDetail) {
                    if(item.id == itemDetail.id) {
                      item.isChecked = 1;
                    }
                  });
                }
              });
            }
        },
        bulkCheck: function() {
          var vm = this;

          vm.filteredPermissions.forEach(function(item, index, arr) {
            vm.permission.forEach(function(itemPermission, indexPermission, arrPermission) {
              if(item.id == itemPermission.id) {
                if(vm.isBulkChecked == 1) {
                  itemPermission.isChecked = 1;
                } else {
                  itemPermission.isChecked = 0;
                }
              }

            });
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
            <div class="card card-primary card-outline card-tabs">
              <box-overlay v-if="$loading.get('loading')" />
                <div class="card-header p-0 pt-1 border-bottom-0">
                  <ul class="nav nav-tabs" id="custom-tabs-two-tab" role="tablist">
                    <li class="nav-item">
                      <a class="nav-link active" id="custom-tabs-two-home-tab" data-toggle="pill" href="#custom-tabs-two-home" role="tab" aria-controls="custom-tabs-two-home" aria-selected="true">Detail</a>
                    </li>
                    <li class="nav-item">
                      <a class="nav-link" id="custom-tabs-two-profile-tab" data-toggle="pill" href="#custom-tabs-two-profile" role="tab" aria-controls="custom-tabs-two-profile" aria-selected="false">Akses</a>
                    </li>
                  </ul>
                </div>
                <div class="card-body">
                <div class="tab-content" id="custom-tabs-two-tabContent">
                  <div class="tab-pane fade show active" id="custom-tabs-two-home" role="tabpanel" aria-labelledby="custom-tabs-two-home-tab">
                    <form action="#" role="form" v-on:submit.prevent="submit()">
                      <div class="row">
                        <div class="form-group col-sm-12">
                          <label>Name <span class="text-red">*</span></label>
                          <input type="text" class="form-control" id="name" :required="true" placeholder="Enter Name" v-model="data.name"/>
                        </div>
                        <div class="form-group col-sm-12">
                          <label>Hak Akses</label>
                          <p>{{ permissionString }}</p>
                        </div>
                        <div class="form-group col-sm-12">
                          <div class="float-right" v-if="$ac.hasAccess('access_create')">
                              <wv-cancel-button v-on:click="load()"></wv-cancel-button>
                              <wv-submit-button>Simpan</wv-submit-button>
                          </div>
                        </div>
                      </div>
                    </form>
                  </div>

                  <div class="tab-pane fade" id="custom-tabs-two-profile" role="tabpanel" aria-labelledby="custom-tabs-two-profile-tab">
                    <form action="#" role="form" v-on:submit.prevent="submit()">
                      <div class="row">
                        <div class="col-sm-12">
                          <label>Hak Akses Terpilih</label>
                          <p>{{ permissionString }}</p>
                        </div>
                        <div class="col-sm-12">
                          <hr>
                        </div>
                        <div class="col-sm-12">
                          <label>Kata Kunci</label>
                          <input v-model="filter.keyword" type="text" class="form-control mb-4"/>
                        </div>
                        <div class="col-sm-12">
                          <label>Kategori</label>
                          <select v-model="filter.category" class="form-control mb-4">
                            <option value="">All</option>
                            <option value="checked">Check</option>
                            <option value="uncheck">Uncheck</option>
                          </select>
                        </div>
                        <div class="col-sm-12">
                          <div class="table-responsive">
                              <table class="table table-bordered">
                                  <thead>
                                  <tr>
                                      <th width="5%" class="text-center">
                                      <input v-model="isBulkChecked" type="checkbox">
                                      </th>
                                      <th>ID</th>
                                      <th>Code</th>
                                      <th>Notes</th>
                                  </tr>
                                  </thead>
                                  <tbody>
                                  <tr :key="tr.id" v-for="(tr, $index) in filteredPermissions">
                                      <td class="text-center">
                                      <input true-value="1" false-value="0" v-model="tr.isChecked" type="checkbox">
                                      </td>
                                      <td>
                                      {{tr.id}}
                                      </td>
                                      <td>
                                      {{tr.name}}
                                      </td>
                                      <td>
                                      {{tr.notes}}
                                      </td>
                                  </tr>
                                  </tbody>
                              </table>
                          </div>
                        </div>
                        <div class="col-sm-12">
                          <div class="float-right" v-if="$ac.hasAccess('access_create')">
                              <wv-cancel-button v-on:click="load()"></wv-cancel-button>
                              <wv-submit-button>Simpan</wv-submit-button>
                          </div>
                        </div>
                      </div>
                    </form>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>
</template>
