export default {
    data: function() {
        return {
            // For list page
            list: {
                symbols: []
            }
        };
    },
    methods: {
        loadSymbols: function() {
            var vm = this;

            // Load symbol
            vm.$loading.on('loading-symbol');
            this.$api.Setting.symbols({}).then(function(resp) {
                var data = resp.data;
                vm.list.symbols = data.data;

                vm.$loading.off('loading-symbol');
            });
        }
    },
    computed: {
        symbols: function() {
            var list = _.chain(this.list.symbols).sortBy(function(row) {
                return row;
            }).map(function(row) {
                var label = row.symbol_1 + '/' + row.symbol_2;

                return { id: row.id, text: label };
            }).value();

            // console.log('list', list);
            return list;
        }
    }
};