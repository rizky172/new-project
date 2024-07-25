<script>
export default {
    data: function() {
        return {
            user: {}
        };
    }
}
</script>

<template>
  <footer class="main-footer">
    <strong>Copyright &copy; 2024 <a href="/">{{ $appConfig.getData().company_name }}</a>.</strong>
    All rights reserved.
    <div class="float-right d-none d-sm-inline-block">
      <b>Version</b> 1.0-beta
    </div>
  </footer>
</template>