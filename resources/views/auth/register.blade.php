<!DOCTYPE html>
<html lang="en" dir="ltr" data-bs-theme="light" data-color-theme="Blue_Theme" data-layout="vertical">
<head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="shortcut icon" type="image/png" href="{{ asset('assets/images/logo.png') }}" />
    <link rel="stylesheet" href="{{ asset('assets/backend/css/styles.css') }}" />
    
    <title>{{ config('app.name', 'Laravel') }} | Register</title>
</head>

<body>
    <div class="preloader">
        <img src="{{ asset('assets/images/logo.png') }}" alt="loader" class="lds-ripple img-fluid" />
    </div>

    <div id="main-wrapper">
        <div class="position-relative overflow-hidden radial-gradient min-vh-100 w-100 d-flex align-items-center justify-content-center">
            <div class="d-flex align-items-center justify-content-center w-100">
                <div class="row justify-content-center w-100">
                    <div class="col-md-8 col-lg-6 col-xxl-4">
                        <div class="card mb-0">
                            <div class="card-body">
                                <a href="/" class="text-nowrap logo-img text-center d-block mb-5 w-100">
                                    <img src="{{ asset('assets/images/logo.png') }}" width="120" alt="">
                                </a>
                                <div class="text-center mb-4">
                                    <h4 class="fw-bold">Create Account</h4>
                                    <p class="text-muted small">Join our educational community</p>
                                </div>

                                <form method="POST" action="{{ route('register') }}">
                                    @csrf

                                    <div class="mb-3">
                                        <label for="name" class="form-label fw-semibold">Full Name</label>
                                        <div class="input-group">
                                            <span class="input-group-text bg-transparent border-end-0">
                                                <iconify-icon icon="solar:user-circle-line-duotone" class="fs-5"></iconify-icon>
                                            </span>
                                            <input type="text" name="name" id="name" 
                                                class="form-control border-start-0 ps-0 @error('name') is-invalid @enderror" 
                                                value="{{ old('name') }}" required autofocus placeholder="John Doe">
                                            @error('name')
                                                <div class="invalid-feedback d-block">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="mb-3">
                                        <label for="email" class="form-label fw-semibold">Email Address</label>
                                        <div class="input-group">
                                            <span class="input-group-text bg-transparent border-end-0">
                                                <iconify-icon icon="solar:letter-line-duotone" class="fs-5"></iconify-icon>
                                            </span>
                                            <input type="email" name="email" id="email" 
                                                class="form-control border-start-0 ps-0 @error('email') is-invalid @enderror" 
                                                value="{{ old('email') }}" required placeholder="name@example.com">
                                            @error('email')
                                                <div class="invalid-feedback d-block">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="mb-3">
                                        <label for="password" class="form-label fw-semibold">Password</label>
                                        <div class="input-group">
                                            <span class="input-group-text bg-transparent border-end-0">
                                                <iconify-icon icon="solar:lock-password-line-duotone" class="fs-5"></iconify-icon>
                                            </span>
                                            <input type="password" name="password" id="password" 
                                                class="form-control border-start-0 ps-0 border-end-0 @error('password') is-invalid @enderror" 
                                                required placeholder="••••••••">
                                            <button class="btn border border-start-0 password-toggle" type="button" data-target="password">
                                                <iconify-icon icon="solar:eye-line-duotone" class="fs-5"></iconify-icon>
                                            </button>
                                            @error('password')
                                                <div class="invalid-feedback d-block">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="mb-4">
                                        <label for="password_confirmation" class="form-label fw-semibold">Confirm Password</label>
                                        <div class="input-group">
                                            <span class="input-group-text bg-transparent border-end-0">
                                                <iconify-icon icon="solar:lock-keyhole-minimalistic-line-duotone" class="fs-5"></iconify-icon>
                                            </span>
                                            <input type="password" name="password_confirmation" id="password_confirmation" 
                                                class="form-control border-start-0 ps-0 border-end-0 @error('password_confirmation') is-invalid @enderror" 
                                                required placeholder="••••••••">
                                            <button class="btn border border-start-0 password-toggle" type="button" data-target="password_confirmation">
                                                <iconify-icon icon="solar:eye-line-duotone" class="fs-5"></iconify-icon>
                                            </button>
                                            @error('password_confirmation')
                                                <div class="invalid-feedback d-block">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    <button type="submit" class="btn btn-primary w-100 py-8 mb-4 rounded-2">
                                        Register
                                    </button>
                                    
                                    <div class="d-flex align-items-center justify-content-center">
                                        <p class="fs-4 mb-0 fw-medium">Already have an account?</p>
                                        <a class="text-primary fw-bold ms-2" href="{{ route('login') }}">Sign In</a>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="{{ asset('assets/backend/js/vendor.min.js') }}"></script>
    <script src="{{ asset('assets/backend/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('assets/backend/js/simplebar.min.js') }}"></script>
    <script src="{{ asset('assets/backend/js/app.init.js') }}"></script>
    <script src="{{ asset('assets/backend/js/theme.js') }}"></script>
    <script src="{{ asset('assets/backend/js/app.min.js') }}"></script>
    <script src="{{ asset('assets/backend/js/iconify-icon.min.js') }}"></script>

    <script>
        document.querySelectorAll('.password-toggle').forEach(button => {
            button.addEventListener('click', function() {
                const targetId = this.getAttribute('data-target');
                const input = document.getElementById(targetId);
                const icon = this.querySelector('iconify-icon');
                
                if (input.type === 'password') {
                    input.type = 'text';
                    icon.setAttribute('icon', 'solar:eye-closed-line-duotone');
                } else {
                    input.type = 'password';
                    icon.setAttribute('icon', 'solar:eye-line-duotone');
                }
            });
        });
    </script>
</body>
</html>
