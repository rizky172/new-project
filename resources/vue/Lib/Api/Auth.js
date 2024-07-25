export default function Auth(vm) {
    this.login = function(params) {
        var url = '/api/login';

        return vm.post(url, params);
    };

    this.register = function(params) {
        var url = '/api/register';

        return vm.post(url, params);
    };

    this.profile = function() {
        var params = {};

        var url = '/api/profile';

        return vm.get(url);
    };

    this.logout = function(){
        var url = '/api/logout';

        return vm.post(url);
    };

    this.forgotPassword = function(params) {
        var url = '/api/forgot-password';

        return vm.post(url, params);
    }

    this.resetPassword = function(params) {
        var url = '/api/reset-password';

        return vm.post(url, params);
    }

};