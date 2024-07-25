// Handle show, hide, and send data to sidebar.
export default function Sidebar($store) {
    var vm = this;
    this.$store = $store;

    this.setParams = function(params) {
        this.$store.dispatch('app/sidebarParams', params);
    };

    this.setComponent = function(component) {
        this.$store.dispatch('app/sidebarComponent', component);
    };

    this.show = function(component) {
        if(component)
            this.setComponent(component);

        this.$store.dispatch('app/sidebar', true);
    };

    this.hide = function() {
        this.$store.dispatch('app/sidebar', false);
    };
}
