export default {
    data: function() {
        return {
            // Route name that would be used in doSearch()
            routeName: null,
            // For search parameter
            search: {},
            // For list page
            list: {},
            // For pagination
            paginator: {},
            // For order by
            order_by: {},
        }
    },
    watch:{
        // Call when $route.query changed
        '$route.query': function() {
            var vm = this;

            this.load();
        }
    },
    // When element created for first time. This function should be called
    // load() method
    created: function() {
        if(this.$route) {
            this.order_by = this.$route.query.order_by;
            // console.log('created', this.$route.query.order_by);
        }

        // console.log('mixin list created', this.$route.query);

        this.load();
    },
    methods: {
        // =========== ABSTRACT METHOD(START) ===========
        // Call api, this function should overide
        _search: async function(search) {
            return {};
        },

        _delete: async function() {
            return {};
        },
        // Modify anything before doSearch called
        _beforeSearch: function(search) {},
        // =========== ABSTRACT METHOD(END) ===========

        // =========== PRIVATE METHOD(START) ========
        // Method that doesn't need to be modified(probably)
        // Similar with init
        load: function() {
            // console.log('mixin load');
            if(!this.$route) return;

            var orderBy = null;
            // Prepare search query
            this.search = Object.assign({}, this.$route.query);
            // console.log('mixin list loaded', this.search, this.$route.query);

            // Reset order by object
            this.$orderBy.reset(this.search.order_by);
            // Get order by object for API
            orderBy = this.$orderBy.toObject();
            // Delete to clean parameter for API
            delete this.search.order_by;
            // If order by is not empty
            if(orderBy)
                this.search.order_by = orderBy;

            // console.log('mixin load', this.search);

            // console.log('customer.vue load');
            this._doSearch();
        },
        sortBy: function(columnName) {
            this.$orderBy.setColumn(columnName);

            // Generate search params
            this.search.order_by = this.$orderBy.toString();
            // console.log('sort by', this.search);
            this.doSearch();
        },

        // Redirect to correct url
        doSearch: function() {
            if(this.search.order_by)
                this.search.order_by = this.$orderBy.toString();

            // this.$store.dispatch('addSuccess', 'do search');
            this.$router.push({name: this.routeName, query: this.search});
        },

        // Search by submitting correct $search params
        _doSearch: async function() {
            var vm = this;

            // Copy this.search, so whatever you modified would not affected to this.search
            var search = Object.assign({}, this.search);
            search.order_by = this.$orderBy.toObject();
            this._beforeSearch(search);

            vm.$loading.on('loading');
            // vm.search.item_category = 'nrm';
            var resp = await this._search(search);
            var data = null;

            if (!resp.data.is_error) {
                data = resp.data;

                this.list = data.data;

                // Paginator
                this.paginator = data._meta;

                vm.autoRedirect();
            } else {
                vm.$flash.error(resp.data.message);
            }

            vm.$loading.off('loading');
        },

        destroy: async function(id, permanent = null) {
            var vm = this;

            vm.$loading.on('loading-delete-' + id);

            var params = {
                id: id,
                permanent: permanent
            };

            var resp = await this._delete(params);

            if(!resp.data.is_error) {
                vm._doSearch();

                vm.$flash.success(resp.data.message);
            } else {
                vm.$flash.error(resp.data.message);
            }

            vm.$loading.off('loading-delete-' + id);
        },

        // Auto redirect if search page more than last page
        autoRedirect: function() {
            var currPage = this.paginator.current_page;
            var lastPage = this.paginator.last_page;

            // console.log('auto redirect', this.paginator);
            if(parseInt(currPage) > parseInt(lastPage)) {
                this.search.page = lastPage;
                this.doSearch();
            }
        },

        clear: function() {
          var vm = this;
          vm.search = {};

          vm.doSearch();
        },

        goto: function(page) {
            this.search.page = page;
            this.doSearch();
        },

        setTrash: function(deleted) {
            var vm = this;

            vm.search.deleted = deleted;
            vm.doSearch();
        },

        restore: async function(id) {
            var vm = this;

            vm.$loading.on('loading-delete-' + id);
            var resp = await this._restore(id);
            if(!resp.data.is_error) {
                vm._doSearch();

                vm.$flash.success(resp.data.message);
            } else {
                vm.$flash.error(resp.data.message);
            }

            vm.$loading.off('loading-delete-' + id);
        },
        format: function(date) {
            return DateFormatter.short(date);
        },
        // =========== PRIVATE METHOD(END) ========
    }
};