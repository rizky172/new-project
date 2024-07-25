export default function PermissionGroup(vm) {

    this.create = function(params) {
          var url = '/api/permission-group/create';

          return vm.post(url, params);
    };

    this.get = function(id) {
          var params = {};

          var url = '/api/permission-group/detail/' + id;

          return vm.get(url);
    };

    this.delete = function(id){
          var url = '/api/permission-group/' + id;

          return vm.delete(url);
    };
    
    this.permission = function(id) {
        var params = {};

        var url = '/api/permission-group/permission/' + id;

        return vm.get(url);
    };

    this.export = function(params) {
        var url = '/api/permission-group/export?' + $.param(params);

        return vm.get(url);
    };

    this.import = function(params) {
        var url = '/api/permission-group/import';

        return vm.upload(url, params);
    };
};