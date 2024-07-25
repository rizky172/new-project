export default function Customer(vm) {
    this.all = function(params) {
        var url = '/api/profile/list?' + $.param(params);

        return vm.get(url);
    };

    this.create = function(params) {
        var url = '/api/profile/create';

        return vm.post(url, params);
    };

    this.get = function(id) {
        var url = '/api/profile/detail/' + id;

        return vm.get(url);
    };

    this.delete = function(params) {
        var url = null;

        if (params.permanent != null)
            url = '/api/profile/' + params.id + '/' + params.permanent;
        else
            url = '/api/profile/' + params.id;

        return vm.delete(url);
    };

    this.restore = function(id){
        var url = '/api/profile/restore/' + id;

        return vm.get(url);
    };
};