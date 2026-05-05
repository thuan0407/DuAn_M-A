<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Đăng nhập | M&A Platform</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    {{-- Bootstrap CSS --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        :root {
            --orange-main: #f97316;
            --orange-dark: #ea580c;
            --orange-light: #fff7ed;
            --orange-soft: #ffedd5;
            --orange-border: #fed7aa;
            --text-dark: #1f2937;
            --text-muted: #6b7280;
        }

        body {
            min-height: 100vh;
            background:
                radial-gradient(circle at top left, rgba(249, 115, 22, 0.22), transparent 34%),
                radial-gradient(circle at bottom right, rgba(251, 146, 60, 0.20), transparent 30%),
                linear-gradient(135deg, #fff7ed, #ffffff);
            font-family: system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", sans-serif;
            color: var(--text-dark);
        }

        .login-wrapper {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 32px 16px;
        }

        .login-shell {
            width: 100%;
            max-width: 1050px;
            background: rgba(255, 255, 255, 0.92);
            border: 1px solid var(--orange-border);
            border-radius: 32px;
            overflow: hidden;
            box-shadow: 0 28px 80px rgba(249, 115, 22, 0.18);
        }

        .login-brand-panel {
            min-height: 100%;
            background:
                radial-gradient(circle at top right, rgba(255, 255, 255, 0.25), transparent 34%),
                linear-gradient(135deg, #f97316, #fb923c);
            color: #ffffff;
            padding: 48px;
            position: relative;
            overflow: hidden;
        }

        .login-brand-panel::after {
            content: "";
            position: absolute;
            width: 220px;
            height: 220px;
            border-radius: 999px;
            background: rgba(255, 255, 255, 0.13);
            right: -70px;
            bottom: -70px;
        }

        .brand-badge {
            display: inline-flex;
            align-items: center;
            padding: 8px 14px;
            border-radius: 999px;
            background: rgba(255, 255, 255, 0.18);
            font-size: 13px;
            font-weight: 700;
            margin-bottom: 26px;
        }

        .brand-title {
            font-size: 38px;
            line-height: 1.12;
            font-weight: 900;
            letter-spacing: -1px;
            margin-bottom: 18px;
        }

        .brand-desc {
            color: #ffedd5;
            font-size: 15px;
            line-height: 1.8;
            max-width: 420px;
            margin-bottom: 34px;
        }

        .brand-feature {
            display: flex;
            align-items: flex-start;
            gap: 12px;
            margin-bottom: 18px;
            position: relative;
            z-index: 1;
        }

        .brand-feature-dot {
            width: 10px;
            height: 10px;
            border-radius: 999px;
            background: #ffffff;
            margin-top: 7px;
            flex: 0 0 auto;
        }

        .brand-feature-title {
            font-weight: 800;
            margin-bottom: 2px;
        }

        .brand-feature-text {
            color: #ffedd5;
            font-size: 13px;
            margin: 0;
        }

        .login-form-panel {
            padding: 48px;
            background: #ffffff;
        }

        .form-title {
            font-size: 28px;
            font-weight: 900;
            color: var(--text-dark);
            margin-bottom: 8px;
        }

        .form-subtitle {
            color: var(--text-muted);
            font-size: 14px;
            margin-bottom: 28px;
        }

        .form-label {
            font-weight: 700;
            color: #374151;
            margin-bottom: 8px;
        }

        .form-control {
            border-radius: 16px;
            min-height: 48px;
            border: 1px solid #e5e7eb;
            padding: 12px 16px;
            font-size: 14px;
        }

        .form-control:focus {
            border-color: var(--orange-main);
            box-shadow: 0 0 0 4px rgba(249, 115, 22, 0.14);
        }

        .form-check-input:checked {
            background-color: var(--orange-main);
            border-color: var(--orange-main);
        }

        .btn-orange {
            min-height: 50px;
            border-radius: 16px;
            background: var(--orange-main);
            border: 1px solid var(--orange-main);
            color: #ffffff;
            font-weight: 800;
            transition: all 0.2s ease;
        }

        .btn-orange:hover {
            background: var(--orange-dark);
            border-color: var(--orange-dark);
            color: #ffffff;
            transform: translateY(-1px);
            box-shadow: 0 12px 26px rgba(249, 115, 22, 0.24);
        }

        .auth-link {
            color: var(--orange-dark);
            text-decoration: none;
            font-weight: 700;
        }

        .auth-link:hover {
            color: var(--orange-main);
            text-decoration: underline;
        }

        .demo-box {
            background: var(--orange-light);
            border: 1px solid var(--orange-border);
            border-radius: 20px;
            padding: 16px;
            margin-top: 24px;
        }

        .demo-title {
            font-weight: 800;
            color: var(--text-dark);
            font-size: 14px;
            margin-bottom: 10px;
        }

        .demo-line {
            color: var(--text-muted);
            font-size: 14px;
            margin-bottom: 4px;
        }

        .demo-line span {
            color: var(--text-dark);
            font-weight: 800;
        }

        .alert {
            border-radius: 16px;
            font-size: 14px;
        }

        @media (max-width: 991.98px) {
            .login-brand-panel {
                padding: 34px;
            }

            .login-form-panel {
                padding: 34px;
            }

            .brand-title {
                font-size: 30px;
            }
        }

        @media (max-width: 767.98px) {
            .login-wrapper {
                padding: 18px 12px;
            }

            .login-shell {
                border-radius: 24px;
            }

            .login-brand-panel {
                display: none;
            }

            .login-form-panel {
                padding: 28px 22px;
            }

            .form-title {
                font-size: 24px;
            }
        }
    </style>
</head>

<body>
<div class="login-wrapper">
    <div class="login-shell">
        <div class="row g-0">

            {{-- Left brand panel --}}
            <div class="col-lg-6">
                <div class="login-brand-panel h-100">
                    <div class="brand-badge">
                        Nền tảng kết nối M&A và gọi vốn
                    </div>

                    <h1 class="brand-title">
                        M&A Platform
                    </h1>

                    <p class="brand-desc">
                        Kết nối người mua, nhà đầu tư và doanh nghiệp đang có nhu cầu bán công ty,
                        bán cổ phần hoặc gọi vốn một cách bảo mật và có kiểm soát.
                    </p>

                    <div class="brand-feature">
                        <div class="brand-feature-dot"></div>
                        <div>
                            <div class="brand-feature-title">Deal được kiểm duyệt</div>
                            <p class="brand-feature-text">
                                Các deal được xác minh trước khi hiển thị trên nền tảng.
                            </p>
                        </div>
                    </div>

                    <div class="brand-feature">
                        <div class="brand-feature-dot"></div>
                        <div>
                            <div class="brand-feature-title">Bảo mật hồ sơ</div>
                            <p class="brand-feature-text">
                                Buyer cần đồng ý NDA và được seller duyệt trước khi xem Data Room.
                            </p>
                        </div>
                    </div>

                    <div class="brand-feature">
                        <div class="brand-feature-dot"></div>
                        <div>
                            <div class="brand-feature-title">Trao đổi trên sàn</div>
                            <p class="brand-feature-text">
                                Buyer và seller có thể trò chuyện, gửi offer và theo dõi tiến trình giao dịch.
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Right form panel --}}
            <div class="col-lg-6">
                <div class="login-form-panel">

                    <div class="mb-4">
                        <h2 class="form-title">
                            Đăng nhập
                        </h2>

                        <p class="form-subtitle">
                            Vui lòng nhập email và mật khẩu để tiếp tục.
                        </p>
                    </div>

                    @if (session('status'))
                        <div class="alert alert-success mb-4">
                            {{ session('status') }}
                        </div>
                    @endif

                    @if ($errors->any())
                        <div class="alert alert-danger mb-4">
                            {{ $errors->first() }}
                        </div>
                    @endif

                    <form method="POST" action="{{ route('login') }}">
                        @csrf

                        <div class="mb-3">
                            <label for="email" class="form-label">
                                Email
                            </label>

                            <input
                                id="email"
                                type="email"
                                name="email"
                                value="{{ old('email') }}"
                                class="form-control"
                                placeholder="Ví dụ: buyer1@ma-platform.test"
                                required
                                autofocus
                                autocomplete="username"
                            >
                        </div>

                        <div class="mb-3">
                            <label for="password" class="form-label">
                                Mật khẩu
                            </label>

                            <input
                                id="password"
                                type="password"
                                name="password"
                                class="form-control"
                                placeholder="Nhập mật khẩu"
                                required
                                autocomplete="current-password"
                            >
                        </div>

                        <div class="d-flex justify-content-between align-items-center gap-3 mb-4">
                            <div class="form-check">
                                <input
                                    id="remember_me"
                                    type="checkbox"
                                    class="form-check-input"
                                    name="remember"
                                >

                                <label class="form-check-label text-muted" for="remember_me">
                                    Ghi nhớ đăng nhập
                                </label>
                            </div>

                            @if (Route::has('password.request'))
                                <a href="{{ route('password.request') }}" class="auth-link small">
                                    Quên mật khẩu?
                                </a>
                            @endif
                        </div>

                        <button type="submit" class="btn btn-orange w-100">
                            Đăng nhập
                        </button>
                    </form>

                    <div class="demo-box">
                        <div class="demo-title">
                            Tài khoản test người mua
                        </div>

                        <p class="demo-line">
                            Email:
                            <span>buyer1@ma-platform.test</span>
                        </p>

                        <p class="demo-line mb-0">
                            Mật khẩu:
                            <span>12345678</span>
                        </p>
                    </div>


                    <div class="demo-box">
                        <div class="demo-title">
                            Tài khoản test người bán
                        </div>

                        <p class="demo-line">
                            Email:
                            <span>seller1@ma-platform.test</span>
                        </p>

                        <p class="demo-line mb-0">
                            Mật khẩu:
                            <span>12345678</span>
                        </p>
                    </div>


                    <div class="demo-box">
                        <div class="demo-title">
                            Tài khoản test admin
                        </div>

                        <p class="demo-line">
                            Email:
                            <span>admin@ma-platform.test</span>
                        </p>

                        <p class="demo-line mb-0">
                            Mật khẩu:
                            <span>12345678</span>
                        </p>
                    </div>

                    <div class="text-center mt-4 text-muted small">
                        Chưa có tài khoản?

                        @if (Route::has('register'))
                            <a href="{{ route('register') }}" class="auth-link">
                                Đăng ký ngay
                            </a>
                        @endif
                    </div>

                </div>
            </div>

        </div>
    </div>
</div>

{{-- Bootstrap JS --}}
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>