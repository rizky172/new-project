export default function Category(vm) {
    this.all = function(params) {
          var url = '/api/category/list?' + $.param(params);

          return vm.get(url);
    };

    this.create = function(params) {
          var url = '/api/category/create';

          return vm.post(url, params);
    };

    this.get = function(id) {
          var params = {};

          var url = '/api/category/detail/' + id;

          return vm.get(url);
    };

    this.item = function() {
          var params = {};

          var url = '/api/category/item';

          return vm.get(url);
    };

    this.delete = function(id){
          var url = '/api/category/' + id;

          return vm.delete(url);
    };

    this.createDepartment = function(params) {
        var url = '/api/category/department/create';

        return vm.post(url, params);
    };
    
    this.export = function(params) {
      var url = '/api/category/export?' + $.param(params);

      return vm.get(url);
    };

    this.import = function(params) {
    var url = '/api/category/import';

    return vm.upload(url, params);
    };
};