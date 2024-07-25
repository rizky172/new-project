<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>@{{env('APP_NAME') }}@{{ env('APP_ENV') != 'production' ? '(dev)' : null }} | Reset Password</title>

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

      <p class="login-box-msg">Reset Password</p>
      @if (session('message'))
        <div class="alert alert-success" role="alert">@{{ session('message') }}</div>
      @endif

      @if ($errors->any())
        <div class="alert alert-danger">
          @{{ $errors->first() }}
        </div>
      @endif

      <form action="/reset-password" method="post">
        {{ csrf_field() }}
        <input type="hidden" name="hash" value="@{{ app('request')->input('hash') }}">
        <div class="input-group mb-3">
          <input type="password" class="form-control" name="password" placeholder="Masukkan Password Baru">
          <div class="input-group-append">
            <div class="input-group-text">
              <span class="fas fa-lock"></span>
            </div>
          </div>
        </div>

        <div class="input-group mb-3">
          <input type="password" class="form-control" name="password_confirmation" placeholder="Konfirmasi Password">
          <div class="input-group-append">
            <div class="input-group-text">
              <span class="fas fa-lock"></span>
            </div>
          </div>
        </div>

        <div class="row">
          <!-- /.col -->
          <div class="col-12">
            <button type="submit" class="btn btn-primary btn-block">Ubah Password</button>
          </div>
          <!-- /.col -->
        </div>
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
