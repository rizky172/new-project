<!DOCTYPE html
  PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
  "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
    <!-- Help to test using HTML view -->
    <style><?php include(resource_path('/css/pdf.css')); ?></style>
    {{-- Set your paper type. Default is a4 --}}
    @section('paper')
        @include('theme.partials.paper')
    @show
</head>
<body>
{{-- include header letter you want --}}
@section('header')
    @include('theme.partials.header')
@show

{{-- Only if title has set, then print this line --}}
@hasSection('title')
<h1 class="title">@yield('title')</h1>
@endif

@section('content')
    This is the master content.
@show
</body>
</html>