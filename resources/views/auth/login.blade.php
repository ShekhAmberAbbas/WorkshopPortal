<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="theme-color" content="#005495">
    <title>Admin Login | Quaere Tech Hub</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">

    <style>
        :root {
            --brand: #005495;
            --brand-dark: #00385f;
            --brand-soft: #edf6fd;
            --ink: #192f42;
            --muted: #718196;
        }

        * { box-sizing: border-box; }

        body {
            min-height: 100vh;
            margin: 0;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 28px 16px;
            font-family: 'Inter', sans-serif;
            color: var(--ink);
            background:
                radial-gradient(circle at 12% 12%, rgba(0, 84, 149, .11), transparent 27%),
                radial-gradient(circle at 90% 88%, rgba(49, 174, 225, .13), transparent 28%),
                #f3f7fb;
        }

        .login-shell {
            width: min(100%, 1000px);
            overflow: hidden;
            display: grid;
            grid-template-columns: 0.95fr 1.05fr;
            border: 1px solid rgba(255,255,255,.8);
            border-radius: 26px;
            background: #fff;
            box-shadow: 0 28px 80px rgba(24, 55, 81, .13), 0 4px 14px rgba(24, 55, 81, .04);
            animation: reveal .55s ease both;
        }

        .brand-panel {
            position: relative;
            isolation: isolate;
            min-height: 590px;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            overflow: hidden;
            padding: 42px 38px 32px;
            color: #fff;
            background: linear-gradient(145deg, #0067ab 0%, #005495 44%, #003453 100%);
        }

        .brand-panel::before,
        .brand-panel::after {
            position: absolute;
            z-index: -1;
            content: "";
            border: 1px solid rgba(255,255,255,.12);
            border-radius: 50%;
        }

        .brand-panel::before { width: 370px; height: 370px; top: 90px; right: -190px; }
        .brand-panel::after { width: 280px; height: 280px; bottom: -100px; left: -115px; }

        .brand-top { display: flex; align-items: center; gap: 12px; position: relative; z-index: 1; }
        .brand-mark {
            width: 45px; height: 45px; flex: 0 0 45px;
            display: grid; place-items: center;
            border: 1px solid rgba(255,255,255,.25);
            border-radius: 13px;
            background: rgba(255,255,255,.13);
            font-size: 23px;
        }
        .brand-name { font-size: 17px; font-weight: 800; letter-spacing: -.3px; }
        .brand-subtitle { margin-top: 3px; color: rgba(255,255,255,.68); font-size: 10px; font-weight: 700; letter-spacing: 1.8px; text-transform: uppercase; }

        .brand-message { position: relative; z-index: 1; margin: 52px 0; }
        .eyebrow {
            display: inline-flex; align-items: center; gap: 8px;
            padding: 8px 12px;
            border: 1px solid rgba(255,255,255,.2);
            border-radius: 50px;
            background: rgba(255,255,255,.1);
            color: rgba(255,255,255,.9);
            font-size: 10px; font-weight: 800; letter-spacing: 1.2px; text-transform: uppercase;
        }
        .brand-message h1 { max-width: 360px; margin: 23px 0 16px; font-size: clamp(31px, 4vw, 43px); font-weight: 800; letter-spacing: -1.7px; line-height: 1.12; }
        .brand-message p { max-width: 340px; margin: 0; color: rgba(255,255,255,.73); font-size: 13px; line-height: 1.9; }

        .feature-list { display: grid; gap: 14px; margin-top: 31px; }
        .feature { display: flex; align-items: center; gap: 11px; color: rgba(255,255,255,.9); font-size: 12px; font-weight: 500; }
        .feature i { width: 30px; height: 30px; display: grid; place-items: center; border-radius: 9px; background: rgba(255,255,255,.13); font-size: 14px; }
        .brand-foot { position: relative; z-index: 1; color: rgba(255,255,255,.56); font-size: 10px; letter-spacing: .2px; }

        .form-panel { display: flex; flex-direction: column; justify-content: center; padding: 54px clamp(25px, 5vw, 58px); }
        .form-heading { margin-bottom: 31px; }
        .form-icon { width: 48px; height: 48px; display: grid; place-items: center; margin-bottom: 22px; border-radius: 15px; background: var(--brand-soft); color: var(--brand); font-size: 22px; }
        .form-heading h2 { margin: 0 0 9px; font-size: 27px; font-weight: 800; letter-spacing: -.9px; }
        .form-heading p { margin: 0; color: var(--muted); font-size: 12px; line-height: 1.8; }

        .form-label { margin-bottom: 8px; color: #33495d; font-size: 11px; font-weight: 700; }
        .input-wrap { position: relative; }
        .input-wrap > .input-icon { position: absolute; top: 50%; left: 15px; transform: translateY(-50%); color: #8495a6; font-size: 16px; pointer-events: none; }
        .form-control {
            min-height: 49px;
            padding: 12px 14px 12px 43px;
            border: 1px solid #dfe7ef;
            border-radius: 12px;
            color: var(--ink);
            background: #fbfdff;
            font-size: 12px;
            transition: border-color .2s, box-shadow .2s, background .2s;
        }
        .form-control::placeholder { color: #a5b1bd; }
        .form-control:focus { border-color: #72afd9; background: #fff; box-shadow: 0 0 0 4px rgba(0,84,149,.09); }
        .password-input { padding-right: 46px; }
        .password-toggle { position: absolute; top: 50%; right: 8px; transform: translateY(-50%); width: 34px; height: 34px; display: grid; place-items: center; border: 0; border-radius: 8px; background: transparent; color: #7b8b9b; }
        .password-toggle:hover { background: #edf4f9; color: var(--brand); }

        .login-button {
            min-height: 49px;
            display: flex; justify-content: center; align-items: center; gap: 10px;
            border: 0; border-radius: 12px;
            background: linear-gradient(110deg, #0068aa, #005495 58%, #004575);
            box-shadow: 0 8px 18px rgba(0,84,149,.2);
            color: #fff; font-size: 12px; font-weight: 700;
            transition: transform .2s, box-shadow .2s;
        }
        .login-button:hover { color: #fff; transform: translateY(-1px); box-shadow: 0 11px 24px rgba(0,84,149,.26); }
        .login-button:active { transform: translateY(0); }

        .security-note { display: flex; align-items: flex-start; justify-content: center; gap: 8px; margin-top: 23px; color: #8a99a8; font-size: 10px; line-height: 1.7; text-align: center; }
        .security-note i { color: #16936c; font-size: 13px; }
        .login-footer { margin-top: 34px; padding-top: 19px; border-top: 1px solid #edf1f5; color: #93a0ad; font-size: 10px; text-align: center; }
        .login-footer strong { color: #617588; }

        .alert { border: 0; border-radius: 11px; font-size: 12px; }
        .alert-danger { border-left: 3px solid #dc3545; background: #fff3f3; color: #9b2934; }

        @keyframes reveal { from { opacity: 0; transform: translateY(12px); } to { opacity: 1; transform: translateY(0); } }

        @media (max-width: 760px) {
            .login-shell { max-width: 480px; grid-template-columns: 1fr; border-radius: 22px; }
            .brand-panel { min-height: auto; padding: 27px 27px 25px; }
            .brand-message { margin: 32px 0 20px; }
            .brand-message h1 { margin-top: 17px; font-size: 31px; }
            .brand-message p { font-size: 12px; }
            .feature-list { grid-template-columns: 1fr 1fr; gap: 10px; margin-top: 20px; }
            .feature { align-items: flex-start; font-size: 10px; line-height: 1.6; }
            .feature i { flex: 0 0 28px; width: 28px; height: 28px; }
            .brand-foot { margin-top: 23px; }
            .form-panel { padding: 34px 27px 28px; }
            .form-heading { margin-bottom: 25px; }
        }

        @media (prefers-reduced-motion: reduce) {
            *, *::before, *::after { animation-duration: .01ms !important; animation-iteration-count: 1 !important; transition-duration: .01ms !important; }
        }
    </style>
</head>
<body>
    <main class="login-shell">
        <aside class="brand-panel" aria-label="Quaere Tech Hub">
            <div class="brand-top">
                <div class="brand-mark"><i class="bi bi-mortarboard-fill" aria-hidden="true"></i></div>
                <div>
                    <div class="brand-name">Quaere Tech Hub</div>
                    <div class="brand-subtitle">Learn · Build · Innovate</div>
                </div>
            </div>

            <div class="brand-message">
                <span class="eyebrow"><i class="bi bi-stars" aria-hidden="true"></i> Admin Workspace</span>
                <h1>Everything you need to manage workshops.</h1>
                <p>Access your administration workspace to organize workshops, manage participants, and keep certificates moving smoothly.</p>

                <div class="feature-list">
                    <div class="feature"><i class="bi bi-calendar2-check" aria-hidden="true"></i><span>Workshop management</span></div>
                    <div class="feature"><i class="bi bi-people" aria-hidden="true"></i><span>Participant records</span></div>
                    <div class="feature"><i class="bi bi-award" aria-hidden="true"></i><span>Certificate workflows</span></div>
                </div>
            </div>

            <div class="brand-foot">A smarter workspace for learning and development.</div>
        </aside>

        <section class="form-panel" aria-labelledby="login-title">
            <div class="form-heading">
                <div class="form-icon"><i class="bi bi-shield-lock-fill" aria-hidden="true"></i></div>
                <h2 id="login-title">Welcome back</h2>
                <p>Sign in with your administrator credentials to continue.</p>
            </div>

            @if ($errors->any())
                <div class="alert alert-danger d-flex align-items-start gap-2 mb-4" role="alert">
                    <i class="bi bi-exclamation-circle-fill mt-1" aria-hidden="true"></i>
                    <div>{{ $errors->first() }}</div>
                </div>
            @endif

            <form method="POST" action="{{ route('login') }}">
                @csrf

                <div class="mb-3">
                    <label for="email" class="form-label">Email address</label>
                    <div class="input-wrap">
                        <i class="bi bi-envelope input-icon" aria-hidden="true"></i>
                        <input
                            type="email"
                            id="email"
                            name="email"
                            class="form-control @error('email') is-invalid @enderror"
                            value="{{ old('email') }}"
                            placeholder="admin@example.com"
                            autocomplete="username"
                            inputmode="email"
                            required
                            autofocus
                        >
                    </div>
                    @error('email')
                        <div class="text-danger small mt-2">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-4">
                    <label for="password" class="form-label">Password</label>
                    <div class="input-wrap">
                        <i class="bi bi-key input-icon" aria-hidden="true"></i>
                        <input
                            type="password"
                            id="password"
                            name="password"
                            class="form-control password-input @error('password') is-invalid @enderror"
                            placeholder="Enter your password"
                            autocomplete="current-password"
                            required
                        >
                        <button
                            type="button"
                            class="password-toggle"
                            id="togglePassword"
                            aria-label="Show password"
                            aria-pressed="false"
                        >
                            <i class="bi bi-eye" aria-hidden="true"></i>
                        </button>
                    </div>
                    @error('password')
                        <div class="text-danger small mt-2">{{ $message }}</div>
                    @enderror
                </div>

                <button type="submit" class="btn login-button w-100">
                    <span>Sign in to dashboard</span>
                    <i class="bi bi-arrow-right" aria-hidden="true"></i>
                </button>
            </form>

            <div class="security-note">
                <i class="bi bi-shield-check" aria-hidden="true"></i>
                <span>Your session is protected. Please keep your administrator credentials private.</span>
            </div>

            <footer class="login-footer">
                &copy; {{ date('Y') }} <strong>Quaere Etechnologies Pvt. Ltd.</strong><br>
                All rights reserved.
            </footer>
        </section>
    </main>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const passwordInput = document.getElementById('password');
            const toggleButton = document.getElementById('togglePassword');

            if (!passwordInput || !toggleButton) return;

            toggleButton.addEventListener('click', function () {
                const showPassword = passwordInput.type === 'password';
                passwordInput.type = showPassword ? 'text' : 'password';
                toggleButton.setAttribute('aria-label', showPassword ? 'Hide password' : 'Show password');
                toggleButton.setAttribute('aria-pressed', String(showPassword));
                toggleButton.innerHTML = showPassword
                    ? '<i class="bi bi-eye-slash" aria-hidden="true"></i>'
                    : '<i class="bi bi-eye" aria-hidden="true"></i>';
            });
        });
    </script>
</body>
</html>
