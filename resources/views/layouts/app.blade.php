<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <title>@yield('title', 'Admin')</title>
</head>

<body>
    <div class="container-fluid p-0">
        <div class="row g-0">
          
            {{-- メイン --}}
            <main class="col bg-light p-4">
                <div class="bg-white p-4 rounded shadow-sm">
                    @yield('content')
                </div>
            </main>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
