<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Login</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="bg-light d-flex align-items-center justify-content-center vh-100 fw-bold">

    <div class="container d-flex justify-content-center align-items-center vh-100 fw-bold" style="background-color: #ffffff;">
        <div class="card p-5 mx-auto w-75 fw-bold"
            style="max-width: 800px; border-radius: 15px; background-color: #9CDBE2; color: white;">

           <img src="{{ asset('images/HELLO2.png') }}" alt="Hello World" width="252" height="252" class="d-block mx-auto">

            {{-- エラーメッセージ --}}
            @if ($errors->any())
                <div class="alert alert-danger text-start">
                    @foreach ($errors->all() as $e)
                        <div>{{ $e }}</div>
                    @endforeach
                </div>
            @endif

            <form class="text-center fw-bold" method="POST" action="{{ url('/login') }}">
                @csrf
               
                <div class="mb-3 fw-bold">
                    <input type="email" name="email" class="form-control form-control-lg fw-bold"
                        placeholder="Email" style="text-align: center;" value="{{ old('email') }}" required>
                </div>
                <div class="mb-3 fw-bold">
                    <input type="password" name="password" class="form-control form-control-lg fw-bold"
                        placeholder="Password" style="text-align: center;" required>
                </div>
                
                <button type="submit" class="btn w-25 fw-bold rounded-3 text-white mb-3"
                    style="background-color:#05445E;">
                    Login
                </button>
                <br>

                <a href="{{ route('register') }}" class="text-dark fw-bold">Create an Account</a>
            </form>
        </div>
    </div>

</body>

</html>
