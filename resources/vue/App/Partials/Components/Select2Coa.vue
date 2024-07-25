<script>
export default {
    name: "select2-coa",
    data: function() {
        return {
            options: {
              coa: undefined
            },
            data: undefined
        };
    },
    props: {
        value: {
            required: true
        },
        withTrashed: {
            required: false
        },
        isHeader: {
            required: false
        },
        selected: {
            required: false,
            type: Function
        }
    },
    mounted: function() {
      var vm = this;

      vm.data = vm.value;

    //   vm.loadCoas();
    },
    methods: {
        // format: function() {
        //     var vm = this;

        //     var coa = _.find(vm.options.coa, function(num) {
        //                 return num.id == vm.value.coa_id;
        //             });

        //     vm.value.coa = coa.name + ' - ' + coa.label;
        // },
        loadCoas: async function() {
            var vm = this;

            // var coas = {
            //     options: []
            // };

            // if (vm.data !== undefined)
            var coas = await vm.loadMoreCoas(vm.data, 1);

            vm.options.coa = coas.options;
        },
        loadMoreCoas: async function(keyword, page) {
            // console.log('loadmoreCoa', keyword, page);
            var vm = this;

            var withTrashed = vm.withTrashed;
            var isHeader = vm.isHeader;

            var resp = await vm.$api.Coa.all({
                'keyword': keyword,
                'page': page,
                'with_trashed': withTrashed,
                'is_header': isHeader
            });
            var coas = resp.data.data;

            // Load list coas
            var list = _.map(coas, function(row) {
                return { id: row.id, text: row.name + ' - ' + row.label, model: row };
            });

            var meta = resp.data._meta;

            return {
                options: list,
                more: meta.current_page < meta.last_page
            };
        }
    },
    // computed: {
    //     parameter: {
    //         get: function() {
    //             console.log(this.value);

    //             return this.value;
    //         },
    //         set: function(val) {
    //             this.$emit('input', val);

    //             if(val == null){
    //                 this.$emit('input', this.value);
    //             }

    //             console.log('computedvalue', val, this.value);
    //         },
    //     }
    // },
    watch:{
        value: function(value) {
            var vm = this;

            // vm.$emit('input', value);
            vm.data = vm.value;
        },
        data: function (val) {
            var vm = this;

            // var coa = _.find(vm.options.coa, function(x) {
            //     return (x.id == val);
            // });

            // if (coa) {
            //     this.$emit('selected', coa);
            // }

            vm.$emit('input', val);
            vm.loadCoas();
        }
    }
}
</script>

<template>
    <select2 v-bind:options="options.coa"
             v-model="data"
             placeholder="Pilih COA"
             v-bind:loadMore="loadMoreCoas"
             v-bind:selected="selected">
        <slot></slot>
    </select2>
</template>