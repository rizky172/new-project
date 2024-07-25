// To manage list of loading by their "key" that pass to this class
// This similar with LoadingService in AngularJS(from older WeVelope project)
export default function AccessControl($store, $api) {
    var vm = this;

    this.$store = $store;
    this.$api = $api;

    this.isRefresh = 0;

    this.init = function() {
        var vm = this;

        setTimeout(async function() {
            if(vm.isRefresh === 1 && vm.$api.getApiToken()) {
                // Refresh
                var resp = await vm.$api.Auth.profile();
                var data = resp.data.data;

                // debugger;

                vm.setUser(data);

                vm.isRefresh = 0;
            }

            vm.init();
        }, 500);
    };
    
    this.refresh = function() {
        this.isRefresh = 1;
    };

    this.getUser = function() {
        return this.$store.state.ac.user;
    };

    this.getPermissions = function() {
        return this.getUser().permission;
    };

    this.setUser = function(user) {
        this.$store.dispatch('ac/setUser', user);
    };

    this.hasAccess = function(name) {
        var temp = false;
        var permissions = this.getPermissions();

        if(Array.isArray(permissions))
          for(var i=0; i<permissions.length; i++) {
              if(permissions[i] === name) {
                  temp = true;
                  break;
              }
          }

        return temp;
    };

    // Check if has accesses one of them
    this.hasAccesses = function(list) {
        var temp = false;
        for(var i = 0; i<list.length; i++) {
            if(this.hasAccess(list[i])) {
                temp = true;
                break;
            }
        }

        return temp;
    };

    this.mustHasAccesses = function(list) {
        var temp = true;
        for(var i = 0; i<list.length; i++) {
            if(!this.hasAccess(list[i])) {
                temp = false;
                break;
            }
        }

        return temp;
    };
}