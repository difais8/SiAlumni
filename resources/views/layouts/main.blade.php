<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Dashboard Alumni</title>

    @vite(['resources/css/main.scss', 'resources/js/app.js'])
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <a class="navbar-brand" href="#">Navbar Bootstrap</a>
    </nav>
    
    <div class="container">
        @yield('content')
    </div>
</body>
</html>