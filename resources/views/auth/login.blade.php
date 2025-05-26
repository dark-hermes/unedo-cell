<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - E-Commerce</title>

    @vite(['resources/sass/app.scss', 'resources/js/app.js'])

    <style>
        .login-container {
            min-height: 100vh;
            background-color: #f8f9fa;
        }

        .login-card {
            border-radius: 10px;
            box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
        }
    </style>
</head>

<body class="login-container d-flex align-items-center">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-6 col-lg-5">
                <div class="card login-card p-4">
                    <div class="card-body">
                        <h2 class="text-center mb-4">Sign in to your account</h2>
                        </p>

                        <form action="{{ route('login') }}" method="POST">
                            @csrf
                            <div class="mb-3">
                                <label for="email" class="form-label">Email address</label>
                                <input type="email" class="form-control @error('email') is-invalid @enderror"
                                    id="email" name="email" value="{{ old('email') }}" required>
                                @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="password" class="form-label">Password</label>
                                <input type="password" class="form-control @error('password') is-invalid @enderror"
                                    id="password" name="password" required>
                                @error('password')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <button type="submit" class="btn btn-primary w-100 mb-3">Sign in</button>

                            <div class="position-relative my-4">
                                <hr>
                                <div
                                    class="position-absolute top-50 start-50 translate-middle bg-white px-2 text-muted">
                                    Or continue with
                                </div>
                            </div>

                            <a href="{{ route('auth.google.redirect') }}"
                                class="btn btn-outline-secondary w-100 d-flex align-items-center justify-content-center">
                                <img src="https://www.gstatic.com/images/branding/googleg/1x/googleg_standard_color_32dp.png"
                                    alt="Google" class="me-2" width="20">
                                Google
                            </a>
                        </form>
                    </div>
                </div>

                <div class="text-center mt-4">
                    {{-- <p class="text-muted">Don't have an account? <a href="{{ route('register') }}"
                            class="text-primary">Sign up</a></p> --}}
                    <a href="index.html" class="text-primary">← Back to home</a>
                </div>
            </div>
        </div>
    </div>
</body>

</html>
