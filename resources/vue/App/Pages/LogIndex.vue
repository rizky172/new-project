<script>
import MixinList from '../Mixins/MixinList.js';

export default {
  mixins: [MixinList],
  data: function() {
      return {
          routeName: 'log'
      }
  },
  watch:{
    $route (to, from){
      var path = '/log?table_name=log';
      // if(from.query.table_name != to.query.table_name){
      //   this.$router.push(path)
      // }
    }
  } ,
  mounted: function() {
    var vm = this;
    // var path = '/log?table_name=log';
    // if (this.$route.path !== path) {
    //   this.$router.push(path).catch(err => {});
    // }
  },
  methods: {
        // ==========
        // Overide from mixin
        _search: async function() {
            this.search.table_name = 'log';
            return await this.$api.Log.all(this.search);
        },
        _delete: async function(params) {
            debugger;
            return await this.$api.JournalEntry.delete(params)
        },
        _restore: async function(id) {
            return await this.$api.JournalEntry.restore(id);
        },
    }
}
</script>
<template>
    <section class="content">
      <div class="container-fluid">
        <div class="row">
          <div class="col-md-12">
            <div class="card card-default">
              <div class="card-header">
                <h3 class="card-title">Filter</h3>
              </div>
              <form action="#" role="form" v-on:submit.prevent="doSearch()">
                <div class="card-body">
                  <div class="row">
                    <div class="form-group col-sm-6">
                      <label>Keyword</label>
                      <input type="text" class="form-control" id="keyword" placeholder="Enter Keyword" v-model="search.keyword"/>
                    </div>
                     <div class="form-group col-sm-6">
                      <label>Category</label>
                       <select class="form-control">
                            <option value="all">All</option>
                      </select>
                    </div>
                    <div class="form-group col-sm-6">
                        <label>Date From</label>
                        <div class="input-group">
                            <div class="input-group-prepend">
                            <span class="input-group-text"><i class="fas fa-calendar-alt"></i></span>
                            </div>
                            <datepicker v-model="search.date_from" />
                        </div>
                    </div>
                    <div class="form-group col-sm-6">
                        <label>Date To</label>
                        <div class="input-group">
                            <div class="input-group-prepend">
                            <span class="input-group-text"><i class="fas fa-calendar-alt"></i></span>
                            </div>
                            <datepicker v-model="search.date_to" />
                        </div>
                    </div>
                  </div>
                </div>
                <div class="card-footer text-right">
                  <button type="button" class="btn btn-default" @click="clear">Clear</button>
                  <button type="submit" class="btn btn-primary">Search</button>
                </div>
              </form>
            </div>
          </div>

          <div class="col-md-12">
            <div class="card card-default">
              <box-overlay v-if="$loading.get('loading')" />
                <div class="card-body">
                  <div class="row">
                    <div class="col-sm-12">
                      <!-- <router-link v-if="$ac.hasAccess('journal_entry_create')" class="btn btn-primary" :to="{name: 'journal-entry-create'}"><i class="fas fa-plus"></i> Tambah</router-link>
                      <button class="btn btn-primary" v-if="$ac.hasAccess('journal_entry_create')" @click="exportXls" :disabled="$loading.get('loading-export')"><i class="fas fa-file-excel"></i> Export(xls) <spin v-if="$loading.get('loading-export')"></spin></button>
                      <button class="btn btn-primary" v-if="$ac.hasAccess('journal_entry_create')" @click="importXls" :disabled="$loading.get('loading-import')"><i class="fas fa-file-excel"></i> Import(xls) <spin v-if="$loading.get('loading-import')"></spin></button> -->
                      <input type="file" style="display: none;" ref="fileInput">
                    </div>
                    <div class="col-sm-12 mt-3">
                        <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead>
                            <tr>
                                <th style="width: 10px">#</th>
                                <th>Date</th>
                                <th>Category</th>
                                <th>Notes</th>
                            </tr>
                            </thead>
                            <tbody>
                                <tr v-for="(tr, $index) in list">
                                    <td>{{ parseInt($index)+1 }}</td>
                                    <td>{{ tr.created }}</td>
                                    <td>{{ tr.table_name }}</td>
                                    <td>{{ tr.notes }}</td>
                                </tr>
                            </tbody>
                        </table>
                        </div>
                    </div>
                  </div>
                </div>
                <div class="card-footer clearfix">
                  <!-- <paging v-bind:model="paginator" v-on:goto="goto"/> -->
                </div>
            </div>
          </div>
        </div>
      </div>
    </section>
</template>
