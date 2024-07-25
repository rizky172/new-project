import Vue from 'vue';

// For flash message
export default {
    namespaced: true,
    state: {
        list: {}
    },
    getters: {
        getx: function(state) {
            return function(key) {
                var list = state.list;
                var bol = list[key];
                if(!bol) {
                    bol = true;
                    // Make object reactive. Every add new property,
                    // That property is not reactive
                    // So, we need to create using Vue.set() method
                    Vue.set(list, key, true);
                }

                return function() { return bol; };
            }
        }
    },
    mutations: {
        /*
         * @params Object
         * @params Object { key: String, val: Boolean }
         */
        set: function(state, obj) {
            var list = state.list;
            var bol = list[obj.key];
            if(!bol) {
                bol = obj.val;
                Vue.set(list, obj.key, obj.val);
            } else {
                list[obj.key] = obj.val;
            }
        },
        get: function(state) {
            return function(key) {
                var list = state.list;
                var bol = list[key];
                if(!bol) {
                    bol = true;
                    Vue.set(list, key, true);
                    // list[key] = new _boolean();
                    // bol = list[key];
                }

                return bol;
            }
        }
    }
};