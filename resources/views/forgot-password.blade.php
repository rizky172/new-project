<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>@{{env('APP_NAME') }}@{{ env('APP_ENV') != 'production' ? '(dev)' : null }} | Lupa Password</title>

  <!-- Google Font: Source Sans Pro -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="/build/plugins/fontawesome-free/css/all.min.css">
  <!-- icheck bootstrap -->
  <link rel="stylesheet" href="/build/plugins/icheck-bootstrap/icheck-bootstrap.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="/build/dist/css/adminlte.min.css">
</head>
<body class="hold-transition login-page">
<div class="login-box">
  <div class="login-logo">
    <a href="#"><b>@{{ env('APP_NAME') }}</b></a>
  </div>
  <!-- /.login-logo -->
  <div class="card">
    <div class="card-body login-card-body">
      <p class="login-box-msg">Lupa Password</p>
      @if (session('message'))
        <div class="alert alert-success" role="alert">@{{ session('message') }}</div>
      @endif

      @if ($errors->any())
        <div class="alert alert-danger">
          @{{ $errors->first() }}
        </div>
      @endif

      <form action="/forgot-password" method="post">
        @{{ csrf_field() }}
        <div class="input-group mb-3">
          <input type="email" class="form-control" name="email" placeholder="Email">
          <div class="input-group-append">
            <div class="input-group-text">
              <span class="fas fa-envelope"></span>
            </div>
          </div>
        </div>
        <div class="row">
          <!-- /.col -->
          <div class="col-12">
            <button type="submit" class="btn btn-primary btn-block">Kirim Link Ubah Password</button>
          </div>
          <!-- /.col -->
        </div>
        <p class="mt-3 mb-1">
          <a href="/">Login</a>
        </p>
      </form>
    </div>
    <!-- /.login-card-body -->
  </div>
</div>
<!-- /.login-box -->

<!-- jQuery -->
<script src="/build/plugins/jquery/jquery.min.js"></script>
<!-- Bootstrap 4 -->
<script src="/build/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<!-- AdminLTE App -->
<script src="/build/dist/js/adminlte.min.js"></script>
</body>
</html>
