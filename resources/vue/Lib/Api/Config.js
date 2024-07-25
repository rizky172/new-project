export default function Config(vm) {
    this.all = function(params) {
          var url = '/api/config/list?' + $.param(params);

          return vm.get(url);
    };

    this.create = function(params) {
          var url = '/api/config/create';

          return vm.multipleV2(url, params);
    };
};