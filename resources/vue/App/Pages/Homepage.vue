<script>
import Dashboard from './Dashboard.vue'

export default {
    components: {
        'dashboard': Dashboard
    },
    data: function() {
        return {
            pageTitle: 'Dashboard'
        }
    },
    created: function() {
        this.$routerMetaHandler.updateTitle(this.pageTitle);
    }
}
</script>

<template>
    <dashboard />
</template>