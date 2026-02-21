<!DOCTYPE html>
<html lang="en" dir="ltr" data-bs-theme="light" data-color-theme="Blue_Theme" data-layout="vertical">
<head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="shortcut icon" type="image/png" href="{{ asset('assets/images/logo.png') }}" />
    <link rel="stylesheet" href="{{ asset('assets/backend/css/styles.css') }}" />
    
    <title>{{ config('app.name', 'Laravel') }} | Verify Email</title>
</head>

<body>
    <div class="preloader">
        <img src="{{ asset('assets/images/logo.png') }}" alt="loader" class="lds-ripple img-fluid" />
    </div>

    <div id="main-wrapper">
        <div class="position-relative overflow-hidden radial-gradient min-vh-100 w-100 d-flex align-items-center justify-content-center">
            <div class="d-flex align-items-center justify-content-center w-100">
                <div class="row justify-content-center w-100">
                    <div class="col-md-8 col-lg-6 col-xxl-3">
                        <div class="card mb-0">
                            <div class="card-body">
                                <a href="/" class="text-nowrap logo-img text-center d-block mb-5 w-100">
                                    <img src="{{ asset('assets/images/logo.png') }}" width="120" alt="">
                                </a>
                                <div class="text-center mb-4">
                                    <h4 class="fw-bold">Verify Your Email</h4>
                                    <p class="text-muted small">We've sent a verification link to your email address.</p>
                                </div>

                                @if (session('status') == 'verification-link-sent')
                                    <div class="alert alert-success border-0 mb-4" role="alert">
                                        A new verification link has been sent to your email.
                                    </div>
                                @endif

                                <div class="mb-4">
                                    <form method="POST" action="{{ route('verification.send') }}">
                                        @csrf
                                        <button type="submit" class="btn btn-primary w-100 py-8 mb-3 rounded-2">
                                            Resend Verification Email
                                        </button>
                                    </form>

                                    <form method="POST" action="{{ route('logout') }}">
                                        @csrf
                                        <button type="submit" class="btn btn-outline-secondary w-100 py-8 rounded-2">
                                            Log Out
                                        </button>
                                    </form>
                                </div>
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
</body>
</html>
