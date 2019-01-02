<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Fake a User</title>
        <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" rel="stylesheet"
              integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
    </head>
    <body>
        <div class="container">
            @if (session()->has('success'))
                <p class="alert alert-success">{{ session('success') }}</p>
            @endif
            @if (session()->has('error'))
                <p class="alert alert-danger">{{ session('error') }}</p>
            @endif

            @yield('content')
        </div>
    </body>
</html>
