<script>
import AdminLTE from '../../AdminLte/AdminLTE.js';

export default {
    data: function() {
        return {
            isShow: true
        };
    },
    mounted: function() {
        this.init();

        AdminLTE.callOnload();
    },
    methods: {
        init: function() {
            var vm = this;

            document.body.className = 'hold-transition login-page';
            document.body.removeAttribute('style');
        }
    }
}
</script>

<template>
    <router-view></router-view>
</template>