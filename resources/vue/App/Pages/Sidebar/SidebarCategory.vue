<script>
// import Sidebar from '../../Components/Sidebar.vue';

export default {
    data: function() {
        return {
            data: {},
        };
    },
    props: {
        'title': String,
        'value': Object,
        'success': Function
    },
    components: {
//        Sidebar: Sidebar
    },
    mounted: function() {
      var vm = this;
        // console.log(vm.value);
      // vm.data = Object.assign({}, vm.value);
      vm.load();
      // console.log
    },
    methods: {
        submit: function() {
            // console.log(this.data);
            this.success(this.value);
            this.$sidebar.hide()
        },
        load: function() {
            var vm = this;

            // this.loadCoaInduk();
        },
        clear: function() {
            var vm = this;

            // vm.data = {};
            vm.$sidebar.hide();
        },
    }
}
</script>

<template>
    <sidebar title="Add/Edit">
        <form action="#" role="form" v-on:submit.prevent="submit()">
            <wv-row>
                <wv-text
                    label="Nama"
                    placeholder="Masukan nama"
                    :required="true"
                    v-model="value.label"></wv-text>
                <wv-textarea
                    label="Catatan"
                    v-model="value.notes"></wv-textarea>

                <wv-col>
                    <div class="float-right">
                        <wv-cancel-button v-on:click="clear()"></wv-cancel-button>
                        <wv-submit-button>Simpan</wv-submit-button>
                    </div>
                </wv-col>
            </wv-row>
        </form>
    </sidebar>
</template>