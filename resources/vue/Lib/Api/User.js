export default function User(vm) {
    this.all = function(params) {
          var url = '/api/user/list?' + $.param(params);

          return vm.get(url);
    };

    this.create = function(params) {
          var url = '/api/user/create';

          return vm.post(url, params);
    };

    this.get = function(id) {
          var params = {};

          var url = '/api/user/detail/' + id;

          return vm.get(url);
    };

    this.delete = function(params) {
      var url = null;

      if (params.permanent != null)
          url = '/api/user/' + params.id + '/' + params.permanent;
      else
          url = '/api/user/' + params.id;

      return vm.delete(url);
  };

    this.restore = function(id){
      var url = '/api/user/restore/' + id;

      return vm.get(url);
  };
};