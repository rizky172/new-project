// For flash message
export default {
    namespaced: true,
    state: {
        sidebarShow: false,
        sidebarComponent: null,
        sidebarParams: {},
        counter: 0,
        breadcrumb: [],
        pageTitle: null,
        config: {}, // App config filled from Api.Config.All()
        popupShow: false,
    },
    // Sync
    mutations: {
        setConfig: function(state, params) {
            state.config = params;
        },
        sidebar: function(state, isShow) {
            state.sidebarShow = isShow;
        },
        popup: function(state, isShow) {
            state.popupShow = isShow;
        },
        sidebarComponent: function(state, component) {
            state.sidebarComponent = component;
        },
        sidebarParams: function(state, params) {
            state.sidebarParams = params;
        },
        increaseCounter(state, params) {
            state.counter++;
        },
        updateTitle: function(state, params) {
            state.pageTitle = params;
        },
        updateBreadcrumb: function(state, params) {
            state.breadcrumb = params;
        },
        updateBreadcrumbAt: function(state, params) {
            state.breadcrumb[params.index] = params.breadcrumb;
        },
        // Update breadcrumb
        // @params state
        // @params object {index: xx, params: {}}
        updateBreadcrumbUrl(state, params) {
            // console.log('update breadcrum');
            // Loop from params that would pass to breadcrumb URL
            // Get breadcrumb URL by index
            var url = state.breadcrumb[params.index].url;
            // console.log(url, state, params.index);
            for(var key in params.params) {
                // Get value of parameter
                var value = params.params[key];
                // Replace string :$key with $value
                url = url.replace(':' + key, value);

                // console.log('update bread', key, value, url);
            };

            // Update new URL to breadcrumb
            state.breadcrumb[params.index].url = url;
        }
    },
    // Async
    actions: {
        sidebar: function(context, isShow) {
            context.commit('sidebar', isShow);
        },
        sidebarComponent: function(context, component) {
            context.commit('sidebarComponent', component);
        },
        sidebarParams: function(context, params) {
            context.commit('sidebarParams', params);
        },
        increaseCounter: function(context, params) {
            context.commit('increaseCounter');
        },
        updateTitle: function(context, params) {
            context.commit('updateTitle', params);
        },
        updateBreadcrumb: function(context, params) {
            context.commit('updateBreadcrumb', params);
        },
        // Update breadcrumb url by params
        updateBreadcrumbAt: function(context, params) {
            context.commit('updateBreadcrumbAt', params);
        },
        setConfig: function(context, params) {
            context.commit('setConfig', params);
        },
        popup: function(context, isShow) {
            context.commit('popup', isShow);
        },
    }
};