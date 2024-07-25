// For flash message
export default {
    namespaced: true,
    state: {
        AppActiveUser: {
            id: 0,
            name: 'John Doe',
            about: 'Dessert chocolate cake lemon drops jujubes. Biscuit cupcake ice cream bear claw brownie brownie marshmallow.',
            img: 'avatar-s-11.png',
            status: 'online',
        },
        userRole: null,
        user: []
    },
    // Sync
    mutations: {
        SET_USER(state, user) {
            //save permission for logged user
            state.user = user;
        }
    },
    // Async
    actions: {
        setUser({ commit }, user) {
          commit('SET_USER', user);
        }
    }
};