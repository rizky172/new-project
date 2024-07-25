// Handling get or set to VUEX App Config
export default function AppConfig($store, $api) {
    var vm = this;

    this.$store = $store;
    this.$api = $api;

    this.isRefresh = 0;

    this.init = function() {
        var vm = this;

        setTimeout(async function() {
            if(vm.isRefresh === 1 && vm.$api.getApiToken()) {
                // Refresh
                // console.log('app config, ajax');
                var resp = await vm.$api.Config.all({});
                var data = resp.data.data;

                // debugger;

                vm.setData(data);

                vm.isRefresh = 0;
                // console.log('data', data); 
            }

            vm.init();
        }, 500);
    };
    
    this.refresh = function() {
        this.isRefresh = 1;
    };

    this.stopRefresh = function() {
        this.isRefresh = 0;
    };

    this.setData = function(data) {
        this.$store.dispatch('app/setConfig', data);
    };

    this.getData = function() {
        return this.$store.state.app.config;
    };
}