<!DOCTYPE html>
<html lang="en">
  <head>
      <meta charset="utf-8">
      <meta name="csrf-token" content="{{ csrf_token() }}">
      <meta name="viewport" content="width=device-width, initial-scale=1.0">
      <title>The Conveyancing Partnership</title>
      <link rel="stylesheet" href="{{asset('css/buttons.dataTables.min.css')}}">
      <link rel="stylesheet" href="{{asset('css/jquery-ui.css')}}">
      <link rel="stylesheet" href="{{ asset('css/jquery-confirm.min.css') }}">
      <link rel="stylesheet" href="{{ asset('css/app.css') }}">
      <link rel="stylesheet" href="https://pro.fontawesome.com/releases/v5.3.1/css/all.css" integrity="sha384-9ralMzdK1QYsk4yBY680hmsb4/hJ98xK3w0TIaJ3ll4POWpWUYaA2bRjGGujGT8w" crossorigin="anonymous">
      @stack('styles')
  </head>

  <body>

    @include ('layouts.header')
    <div class="row align-items-stretch">

      @include('navigation')

      @yield('content')

    </div>

    <script type="text/javascript" src="{{ mix('js/app.js') }}"></script>
    <script type="text/javascript" src="{{ mix('js/jquery-confirm.min.js') }}"></script>
    <script type="text/javascript" src="{{ mix('js/all.js') }}"></script>
    <script type='text/javascript' src="/js/general.js"></script>
    <script type='text/javascript' src="{{ asset('js/jquery-ui.js') }}"></script>
    <script type='text/javascript' src="{{ asset('js/jszip.min.js') }}"></script>
    <script type='text/javascript' src="{{ asset('js/pdfmake.min.js') }}"></script>
    <script type='text/javascript' src="{{ asset('js/vfs_fonts.js') }}"></script>
    <script type='text/javascript' src="{{ asset('js/dataTables.buttons.min.js') }}"></script>
    <script type='text/javascript' src="{{ asset('js/buttons.html5.min.js') }}"></script>

    @stack('scripts')

  </body>

</html>
