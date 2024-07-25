export default function Log(vm) {
    this.all = function(params) {
          var url = '/api/log/list?' + $.param(params);

          return vm.get(url);
    };
};