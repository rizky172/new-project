export default {
    setupAxios: function(axios, $router, $cookie, $flash) {
        // console.log('setup axios', $router);

        var timerId = null;
        axios.interceptors.response.use(function (resp) {
            var data = resp.data;

            // Redirect when token invalid
            if(data.error_code === 100 && !$flash.isLock) {
                // console.log('Auth Failed: API Token missmatch', resp);

                // Set timer
                // Fix when async request made and call this function more than once
                // at the same time
                if(!timerId)
                    timerId = setTimeout(function() {
                        // Force logout
                        // console.log('Error: Force to go to login page', $flash.isLock);
                        $flash.error(data.message);
                        $flash.lock(); // Ignore all error message
                        $cookie.set('api_token', "");
                        $router.push({ name: 'login' });
                        // window.location = '/logout';
                    }, 1000);
            }

            return resp;
        }, function (error) {
            // console.log('when error', error.response.data);
            // if (error.response.data.error.statusCode === 401) {
              // store.dispatch('logout')
              // router.push('/login')
            // }
            // return Promise.reject(error)
        })
    }
};