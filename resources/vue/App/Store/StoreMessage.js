// For flash message
export default {
    namespaced: true,
    state: {
        messages: []
    },
    // Sync
    mutations: {
        remove: function(state, item) {
            var x = item;

            // Remove the element
            state.messages = _.filter(state.messages, function(row) {
                return row != x;
            });
        },
        add: function(state, item) {
            var temp = {
                message: item.message,
                is_error: item.is_error,
                date: new Date()
            };

            state.messages.push(temp);
        },
        addSuccess: function(state, message) {
            var temp = {
                message: message,
                is_error: true,
                date: new Date()
            };

            state.messages.push(temp);
        },
        addError: function(state, message) {
            var temp = {
                message: message,
                is_error: false,
                date: new Date()
            };

            state.messages.push(temp);
        }
    },
    // Async
    actions: {
        remove: function(context, item) {
            context.commit('remove', item);
        },
        // @params object item. See mutation add. { message: 'xxx', is_error: true }
        add: function(context, item) {
            context.commit('add', item);
        },
        // @params string message
        addSuccess: function(context, message) {
            context.commit('addSuccess', message);
        },
        // @params string message
        addError: function(context, message) {
            context.commit('addError', message);
        }
    }
};