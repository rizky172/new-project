<script>
export default {
    name: 'wv-download',
    props: {
        download: {
            required: true,
            type: Function
        },
        icon: {
            required: false,
            type: String
        }
    },
    computed: {
        button: function(){
            var vm = this;
            var label = null;

            if(vm.filename){
                label = 'btn btn-success';
            }else{
                label = 'btn btn-primary';
            }

            return label
        },
        classIcon: function(){
            var vm = this;
            var label = null;
            var icon = vm.icon;

            if(vm.filename){
                label = 'fas fa-download';
            }else{
                label = icon ? 'fas ' + icon : 'fas fa-sync-alt';
            }

            return label
        },
    },
    data: function() {
        return {
            filename: null
        };
    },
    mounted: function() {
        var vm = this;
    },
    methods: {
        init: async function() {
            var vm = this;

            if(!vm.filename){
                vm.$loading.on('loading-download');

                    var resp = await vm.download();
                    var data = resp.data.data;

                    if(!resp.data.is_error) {
                        vm.filename = data.url;
                    } else {
                        vm.$flash.error(resp.data.message);
                    }

                vm.$loading.off('loading-download');
            }else{
                window.open(vm.filename, '_blank');
                
                vm.filename = null;
            }
        },
    }
}
</script>

<template>
    <button
        type="button"
        @click="init()"
        :class="button"
        :disabled="$loading.get('loading-download')">
        <i :class="classIcon"></i>
        <slot></slot>
        <spin v-if="$loading.get('loading-download')"></spin>
    </button>
</template>