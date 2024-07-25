<!DOCTYPE html>
<html lang="id">
    <head>
        <meta charset="utf-8">
    </head>
    <body>
        <div>
            <h1>Hi {{ $user->username }}.</h1>
            <div><br></div>
            <div>anda baru saja melakukan permintaan reset password, silakan masuk ke link berikut untuk melakukan perubahan password.</div>
            <div><br></div>
            <div><a href="{{ url('/reset-password?hash='.$hash) }}">{{ url('/reset-password?hash='.$hash) }}</a></div>
            <div><br></div>
            <div>"jika anda tidak menyadari aktifitas ini, kemungkinan seseorang telah menyalah gunakan akun anda. segera hubungi admin untuk bantuan."</div>
            <div><br></div>
            <div>Terima Kasih</div>
        </div>
    </body>
</html>