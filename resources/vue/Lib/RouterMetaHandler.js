// Handle Router Meta
export default function RouterMetaHandler($store) {
    var vm = this;

    this.$store = $store;

    this.updateTitle = function(title) {
        this.$store.dispatch('app/updateTitle', title);
    }

    this.updateBreadcrumb = function(breadcrumb) {
        this.$store.dispatch('app/updateBreadcrumb', breadcrumb);
    }

    /* Update breadcrumb array at $index
     *
     * @params int Index of array
     * @params Object Update breadcrumb object with new object. With new object,
     *   you could set new title, url, and params
     */
    this.updateBreadcrumbAt = function(index, newBreadcrumbObject) {
        this.$store.dispatch('app/updateBreadcrumbAt', {
            'index': index,
            'breadcrumb': newBreadcrumbObject
        });
    }
}