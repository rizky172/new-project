<script>
export default {
  data: function() {
      return {
          notification: {},
          total_notification: 0,
          isShowNotification: false,

          isTimeOutRunning: true,
          refreshTime: 5000
      };
  },
  mounted: function() {
      var vm = this;

      vm.load();
  },
  watch: {
      'isShowNotification': function(){
        var vm = this;
        if(vm.isShowNotification) {
            vm.setReadNotification();
        }

        if(!vm.isShowNotification) {
          // vm.refreshNotification();
        }
      },
      $route (to, from){
        var vm = this;

        vm.load();
      },
  },
  methods: {
      // Hide sidebar
      show: function() {
        this.$store.dispatch('app/sidebar', true);
      },
      load: function() {
        var vm = this;

        // vm.refreshNotification();
      },
      refreshNotification: async function() {
        var vm = this;
      },
      toggleNotification: function() {
          var vm = this;
          vm.isShowNotification = !vm.isShowNotification;

          // untuk class show pada ref
          // vm.$refs.show.classList.contains('show')
      },
      setReadNotification: async function() {
        var vm = this;

        var unReadNotification = _.where(vm.notification, {is_read: "0"});
        var read = _.pluck(unReadNotification, 'id')

        var params = {
          'read' : read,
        }

        await vm.$api.Notification.read(params);
      },
      textTruncate: function (text) {
        if (text.length > 11) {
            return text.substring(0, 35) + '...';
        }

        return text;
      },
  },
  destroyed: function() {
    var vm = this;
    vm.isTimeOutRunning = false;
  }
}
</script>

<template>
  <!-- Navbar -->
  <nav class="main-header navbar navbar-expand navbar-white navbar-light">
    <!-- Left navbar links -->
    <ul class="navbar-nav">
      <li class="nav-item">
        <a class="nav-link" data-widget="pushmenu" href="#"><i class="fas fa-bars"></i></a>
      </li>
    </ul>

    <!-- Right navbar links -->
    <ul class="navbar-nav ml-auto">
      <li class="nav-item dropdown show">
        <a v-on:click.stop="toggleNotification()" class="nav-link" data-toggle="dropdown" href="#">
          <i class="far fa-bell"></i>
          <span class="badge badge-warning navbar-badge" v-if="total_notification > 0">{{ total_notification }}</span>
        </a>
        <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right show" v-show="isShowNotification" ref="show">
          <span class="dropdown-item dropdown-header disabled" v-if="total_notification > 0">{{ total_notification }} Notifikasi</span>
          <span class="dropdown-item dropdown-header disabled" v-else>Tidak ada notifikasi baru</span>
          <template v-for="(x, $index) in notification">
            <div class="dropdown-divider"></div>
            <router-link :to="x.to">
              <span class="dropdown-item small" :class="x.is_read == 0 ? 'bg-info' : ''">
                  {{ textTruncate(x.notes) }}
              </span>
            </router-link>
            <div class="dropdown-divider"></div>
          </template>
          <router-link class="dropdown-item dropdown-footer" :to="{name: 'notification'}">Lihat Semua</router-link>
        </div>
      </li>
    </ul>
  </nav>
  <!-- /.navbar -->
</template>