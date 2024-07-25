<script>
import SidebarItems from '../Partials/SidebarItems.js';

export default {
    data: function() {
        return {
            sidebarItems: SidebarItems,
            items: null,
            searchQuery:'',
            active: null,
        };
    },
    watch: {
        '$route': function() {
          var vm = this;

          vm.setActive(vm.$route);
        }
    },
    mounted: function() {
      var vm = this;
      // console.log('mounted', this._items);
      vm.setActive(vm.$route);
    },
    computed: {
        logoUrl: function() {
            var temp = '';
            var configData = this.$appConfig.getData();

            if (configData && configData.hasOwnProperty('logo')) {
                temp = configData.logo.url;
            }

            return temp;
        },
        c_items: function() {
            var list = _.map(this.items, function(x) {
              x.to = {
                  name: x.name,
                  query: x.query ? x.query : null
              };

              x.permission = x.permission ? x.permission : [];

              return x;
            });

            // console.log('computed', list);

            return list;
        },
        filterMenu: function() {
            var vm = this;

            var list = _.map(vm.sidebarItems, function(x) {
                    x.to = {
                        name: x.name,
                        query: x.query ? x.query : null
                    };
                    x.permission = x.permission ? x.permission : [];
                return x;
            });

            var items = _.filter(list, function(c){
                  var search = c.header ? '' : vm.searchQuery.toLowerCase();
                  var lowerCase = String(c.label).toLowerCase();
                  var keyword = search;
                  return lowerCase.indexOf(keyword) > -1;
            })

            vm.items = items;

            var menuList = vm.groupByMenu(items, 0);
            return menuList;
        }
    },
    methods: {
        isActive: function(state) {
          var vm = this;

          if (state == vm.active) {
            return true;
          }

          return false;
        },
        setActive: function(route) {
          var vm = this;

          vm.active = route.name;
        },
        logout: function() {
            var vm = this;

            vm.$flash.success("Is logged out...");

            vm.$api.Auth.logout().then(function(resp) {
              vm.$api.unsetApiToken();
              vm.$flash.success("Logout berhasil");

              vm.$router.push({ name: 'login'});
            });
        },
        removeSearch: function() {
          var vm = this;

          vm.items = vm.sidebarItems;
          vm.searchQuery = '';
        },
        removeHeader: function (parentId) {
          var vm = this;

          var items = _.where(this.items, { parent_id : parentId });

          var list = _.map(items, function(row) {
                return {
                    permission: vm.$ac.hasAccesses(row.permission),
                    data: row
                }
          });

          var show = _.findWhere(list, { permission: true });

          if (show) {
            return true;
          }

          return false;

        },
        groupByMenu: function(list, parentId) {
          var children = [];

           for (let i = 0; i < list.length; i++) {
              //make parent
              if (list[i].parent_id == parentId) {
                //recursive
                var grandChildren = this.groupByMenu(list, list[i].id);
                if(grandChildren) {
                    list[i].children = grandChildren;
                }

                children.push(list[i]);
              }
            }

            return children;
        }
    },
}
</script>

<template>
  <!-- Main Sidebar Container -->
  <aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
    <a href="/" class="brand-link">
      <img v-bind:src="logoUrl" alt="Unitypump" class="brand-image img-circle elevation-3"
           style="opacity: .8">
      <span class="brand-text font-weight-light">{{ $config.APP_NAME }}{{ $config.DEV }}</span>
    </a>

    <!-- Sidebar -->
    <div class="sidebar">
      <!-- Sidebar user panel (optional) -->

      <div class="user-panel mt-3 pb-3 mb-3 d-flex">
        <div class="image">
          <div v-if="$ac.getUser().person_id">
            <router-link :to="{name: 'customer-detail', params: {id: $ac.getUser().person_id}}" class="d-block text-bold">
              <img v-bind:src="$ac.getUser().image_url" v-if="$ac.getUser().image_url" class="img-circle elevation-2" v-bind:alt="$ac.getUser().name_person">
              <!-- <img src="img/profile.jpg" v-else class="img-circle elevation-2"></img> -->
            </router-link>
          </div>
          <div v-else>
            <!-- <img src="img/profile.jpg" class="img-circle elevation-2"></img> -->
          </div>
        </div>
      </div>

      <!-- Search Box -->
        <div class="row">
          <div class="input-group input-group-sm mx-3 search-box">
            <input class="form-control form-control-navbar" type="text" placeholder="Search" aria-label="Search" v-model="searchQuery">
            <div class="input-group-append" style="background: rgba(0,0,0,.6);">
              <button class="btn btn-danger" v-on:click="removeSearch">
                <i class="fas fa-window-close"></i>
              </button>
            </div>
          </div>
        </div>

      <!-- Sidebar Menu -->
      <nav class="mt-2">
        <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
          <!-- Add icons to the links using the .nav-icon class with font-awesome or any other icon font library -->
            <template v-for="(tr, $index) in filterMenu">
              <li class="nav-item" v-if="(tr.name) && tr.name != 'logout' && $ac.hasAccesses(tr.permission)  && !tr.children">
                <router-link :to="tr.to" class="nav-link" :class="[{'active': isActive(tr.to)}]">
                  <i class="nav-icon fas" v-bind:class="[tr.icon ? tr.icon : 'fa-th']"></i>
                  <p>
                    {{ tr.label }}
                  </p>
                </router-link>
              </li>
              <li class="nav-item" v-if="tr.name == 'logout'">
                <a class="nav-link" :class="[{'active': isActive(tr.name)}]" @click="logout" style="cursor: pointer;">
                  <i class="nav-icon fas" v-bind:class="[tr.icon ? tr.icon : 'fa-th']"></i>
                  <p>
                    {{ tr.label }}
                  </p>
                </a>
              </li>
              <li class="nav-header pl-3" v-if="removeHeader(tr.id) && tr.header">{{ tr.header }}</li>
              <li v-if="$ac.hasAccesses(b.permission)" class="nav-item has-treeview" v-for="(b, $indexB) in tr.children">
                <router-link :to="b.to" class="nav-link" :class="[{'active': isActive(b.to)}]">
                  <i class="far nav-icon fa" v-bind:class="[b.icon ? b.icon : 'fa-th']"></i>
                  <p>{{ b.label }}</p>
                </router-link>
              </li>
            </template>
        </ul>
      </nav>
      <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
  </aside>
</template>
