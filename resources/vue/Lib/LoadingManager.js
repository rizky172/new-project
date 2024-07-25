// To manage list of loading by their "key" that pass to this class
// This similar with LoadingService in AngularJS(from older WeVelope project)
export default function LoadingManager($store) {
    var vm = this;

    this.$store = $store;

    // Setter
    this.off = function(key) {
        // console.log('off');
        this.set(key, false);
    }

    this.on = function(key) {
        // console.log('on');
        this.set(key, true);
    }

    this.set = function(key, val) {
        // console.log('set', vm.$store, key, val);
        vm.$store.commit('loading/set', {
            'key': key,
            'val': val
        })
    }

    this.get = function(key) {
        return vm.$store.state.loading.list[key];
    };
};