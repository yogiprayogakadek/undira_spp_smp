<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Login &mdash; SI Pembayaran SPP | SMPN 1 MAUPONGGO SATAP</title>
    <link rel="shortcut icon" type="image/png" href="{{ asset('assets/images/logo.png') }}" />
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet" />

    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            min-height: 100vh;
            background: #f0f2f5;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 24px;
        }

        /* ===== CARD ===== */
        .card {
            background: #ffffff;
            border-radius: 20px;
            width: 100%;
            max-width: 420px;
            overflow: hidden;
            box-shadow: 0 4px 32px rgba(0,0,0,0.09);
        }

        /* Top accent bar — bendera merah putih */
        .accent-bar {
            height: 5px;
            display: flex;
        }
        .accent-bar .a-red   { flex: 1; background: #c0392b; }
        .accent-bar .a-white { flex: 1; background: #1e3a5f; }

        /* ===== SCHOOL HEADER ===== */
        .school-header {
            padding: 32px 36px 24px;
            text-align: center;
            border-bottom: 1px solid #f3f4f6;
        }

        .school-header img {
            width: 72px;
            height: 72px;
            object-fit: contain;
            margin-bottom: 14px;
        }

        .school-header h1 {
            font-size: 15px;
            font-weight: 800;
            color: #1e3a5f;
            text-transform: uppercase;
            letter-spacing: 0.4px;
            line-height: 1.35;
            margin-bottom: 4px;
        }

        .school-header p {
            font-size: 12px;
            color: #9ca3af;
            font-weight: 400;
        }

        /* ===== FORM BODY ===== */
        .form-body {
            padding: 28px 36px 32px;
        }

        .form-title {
            margin-bottom: 22px;
        }

        .form-title h2 {
            font-size: 20px;
            font-weight: 700;
            color: #111827;
            margin-bottom: 3px;
        }

        .form-title p {
            font-size: 13px;
            color: #6b7280;
        }

        /* Alert */
        .alert {
            display: flex;
            gap: 8px;
            align-items: flex-start;
            padding: 11px 13px;
            border-radius: 9px;
            font-size: 13px;
            margin-bottom: 18px;
        }

        .alert-error {
            background: #fff5f5;
            border: 1px solid #fecaca;
            color: #991b1b;
        }

        .alert-success {
            background: #f0fdf4;
            border: 1px solid #bbf7d0;
            color: #166534;
        }

        /* Field */
        .field { margin-bottom: 16px; }

        .field label {
            display: block;
            font-size: 13px;
            font-weight: 600;
            color: #374151;
            margin-bottom: 6px;
        }

        .inp-wrap {
            position: relative;
        }

        .inp-wrap input {
            width: 100%;
            height: 46px;
            padding: 0 44px 0 42px;
            border: 1.5px solid #e5e7eb;
            border-radius: 10px;
            font-size: 14px;
            font-family: 'Plus Jakarta Sans', sans-serif;
            color: #111827;
            background: #fafafa;
            outline: none;
            transition: border-color 0.2s, box-shadow 0.2s, background 0.2s;
        }

        .inp-wrap input:focus {
            border-color: #1e3a5f;
            background: #fff;
            box-shadow: 0 0 0 3px rgba(30,58,95,0.08);
        }

        .inp-wrap input.is-invalid {
            border-color: #e74c3c;
        }

        .inp-ico {
            position: absolute;
            left: 13px;
            top: 50%;
            transform: translateY(-50%);
            color: #c0c7cf;
            font-size: 18px;
            pointer-events: none;
            display: flex;
        }

        .inp-toggle {
            position: absolute;
            right: 11px;
            top: 50%;
            transform: translateY(-50%);
            background: none;
            border: none;
            cursor: pointer;
            color: #c0c7cf;
            font-size: 18px;
            display: flex;
            padding: 3px;
            border-radius: 6px;
            transition: color 0.2s;
        }

        .inp-toggle:hover { color: #1e3a5f; }

        .err-msg {
            font-size: 12px;
            color: #c0392b;
            margin-top: 4px;
            display: flex;
            align-items: center;
            gap: 4px;
        }

        /* Row options */
        .opt-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin: 6px 0 22px;
        }

        .opt-row label {
            display: flex;
            align-items: center;
            gap: 7px;
            font-size: 13px;
            color: #6b7280;
            cursor: pointer;
        }

        .opt-row input[type="checkbox"] {
            width: 15px;
            height: 15px;
            accent-color: #1e3a5f;
        }

        .opt-row a {
            font-size: 13px;
            font-weight: 600;
            color: #c0392b;
            text-decoration: none;
        }

        .opt-row a:hover { text-decoration: underline; }

        /* Button */
        .btn-login {
            width: 100%;
            height: 48px;
            background: #1e3a5f;
            color: #fff;
            font-size: 14.5px;
            font-weight: 700;
            font-family: 'Plus Jakarta Sans', sans-serif;
            border: none;
            border-radius: 10px;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            transition: background 0.25s, transform 0.2s, box-shadow 0.25s;
            box-shadow: 0 4px 14px rgba(30,58,95,0.25);
        }

        .btn-login:hover {
            background: #163050;
            transform: translateY(-1px);
            box-shadow: 0 6px 20px rgba(30,58,95,0.32);
        }

        .btn-login:active { transform: translateY(0); }

        /* Footer */
        .card-footer {
            padding: 16px 36px 24px;
            text-align: center;
        }

        .card-footer p {
            font-size: 11.5px;
            color: #d1d5db;
        }
    </style>
</head>
<body>

    <div class="card">
        <div class="accent-bar">
            <div class="a-red"></div>
            <div class="a-white"></div>
        </div>

        {{-- School Header --}}
        <div class="school-header">
            <img src="{{ asset('assets/images/logo.png') }}" alt="Logo SMPN 1 Mauponggo Satap" />
            <h1>SMP Negeri 1 Mauponggo<br>Satu Atap</h1>
            <p>Sistem Informasi Pembayaran SPP · Kab. Nagekeo, NTT</p>
        </div>

        {{-- Form --}}
        <div class="form-body">
            <div class="form-title">
                <h2>Masuk ke Portal</h2>
                <p>Gunakan akun yang telah terdaftar</p>
            </div>

            @if ($errors->any())
                <div class="alert alert-error">
                    <iconify-icon icon="solar:danger-circle-bold" style="font-size:17px;flex-shrink:0"></iconify-icon>
                    <span>{{ $errors->first() }}</span>
                </div>
            @endif

            @if (session('status'))
                <div class="alert alert-success">{{ session('status') }}</div>
            @endif

            <form method="POST" action="{{ route('login') }}">
                @csrf

                <div class="field">
                    <label for="email">Email</label>
                    <div class="inp-wrap">
                        <span class="inp-ico">
                            <iconify-icon icon="solar:letter-bold-duotone"></iconify-icon>
                        </span>
                        <input
                            type="email"
                            id="email"
                            name="email"
                            value="{{ old('email') }}"
                            placeholder="nama@smpn1mauponggo.sch.id"
                            required
                            autofocus
                            class="{{ $errors->has('email') ? 'is-invalid' : '' }}"
                        />
                    </div>
                    @error('email')
                        <div class="err-msg">
                            <iconify-icon icon="solar:info-circle-bold"></iconify-icon>
                            {{ $message }}
                        </div>
                    @enderror
                </div>

                <div class="field">
                    <label for="password">Kata Sandi</label>
                    <div class="inp-wrap">
                        <span class="inp-ico">
                            <iconify-icon icon="solar:lock-password-bold-duotone"></iconify-icon>
                        </span>
                        <input
                            type="password"
                            id="password"
                            name="password"
                            placeholder="••••••••"
                            required
                            class="{{ $errors->has('password') ? 'is-invalid' : '' }}"
                        />
                        <button type="button" class="inp-toggle" id="togglePass">
                            <iconify-icon icon="solar:eye-bold-duotone" id="eyeIco"></iconify-icon>
                        </button>
                    </div>
                    @error('password')
                        <div class="err-msg">
                            <iconify-icon icon="solar:info-circle-bold"></iconify-icon>
                            {{ $message }}
                        </div>
                    @enderror
                </div>

                <div class="opt-row">
                    <label>
                        <input type="checkbox" name="remember" id="remember_me">
                        Tetap masuk
                    </label>
                    @if (Route::has('password.request'))
                        <a href="{{ route('password.request') }}">Lupa Sandi?</a>
                    @endif
                </div>

                <button type="submit" class="btn-login">
                    <iconify-icon icon="solar:login-2-bold-duotone"></iconify-icon>
                    Masuk
                </button>
            </form>
        </div>

        <div class="card-footer">
            <p>&copy; {{ date('Y') }} SI Pembayaran SPP &mdash; SMPN 1 Mauponggo Satap</p>
        </div>
    </div>

    <script src="{{ asset('assets/backend/js/iconify-icon.min.js') }}"></script>
    <script>
        const tog  = document.getElementById('togglePass');
        const inp  = document.getElementById('password');
        const ico  = document.getElementById('eyeIco');

        tog.addEventListener('click', () => {
            if (inp.type === 'password') {
                inp.type = 'text';
                ico.setAttribute('icon', 'solar:eye-closed-bold-duotone');
            } else {
                inp.type = 'password';
                ico.setAttribute('icon', 'solar:eye-bold-duotone');
            }
        });
    </script>
</body>
</html>
