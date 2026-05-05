<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Đăng ký | M&A Platform</title>
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

        .register-wrapper {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 32px 16px;
        }

        .register-shell {
            width: 100%;
            max-width: 1080px;
            background: rgba(255, 255, 255, 0.94);
            border: 1px solid var(--orange-border);
            border-radius: 32px;
            overflow: hidden;
            box-shadow: 0 28px 80px rgba(249, 115, 22, 0.18);
        }

        .register-brand-panel {
            min-height: 100%;
            background:
                radial-gradient(circle at top right, rgba(255, 255, 255, 0.25), transparent 34%),
                linear-gradient(135deg, #f97316, #fb923c);
            color: #ffffff;
            padding: 48px;
            position: relative;
            overflow: hidden;
        }

        .register-brand-panel::after {
            content: "";
            position: absolute;
            width: 240px;
            height: 240px;
            border-radius: 999px;
            background: rgba(255, 255, 255, 0.13);
            right: -80px;
            bottom: -80px;
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

        .register-form-panel {
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

        .note-box {
            background: var(--orange-light);
            border: 1px solid var(--orange-border);
            border-radius: 20px;
            padding: 16px;
            margin-top: 24px;
        }

        .note-title {
            font-weight: 800;
            color: var(--text-dark);
            font-size: 14px;
            margin-bottom: 8px;
        }

        .note-text {
            color: var(--text-muted);
            font-size: 14px;
            margin-bottom: 0;
            line-height: 1.6;
        }

        .alert {
            border-radius: 16px;
            font-size: 14px;
        }

        .invalid-feedback {
            display: block;
            font-size: 13px;
        }

        @media (max-width: 991.98px) {
            .register-brand-panel {
                padding: 34px;
            }

            .register-form-panel {
                padding: 34px;
            }

            .brand-title {
                font-size: 30px;
            }
        }

        @media (max-width: 767.98px) {
            .register-wrapper {
                padding: 18px 12px;
            }

            .register-shell {
                border-radius: 24px;
            }

            .register-brand-panel {
                display: none;
            }

            .register-form-panel {
                padding: 28px 22px;
            }

            .form-title {
                font-size: 24px;
            }
        }
    </style>
</head>

<body>
<div class="register-wrapper">
    <div class="register-shell">
        <div class="row g-0">

            {{-- Left brand panel --}}
            <div class="col-lg-6">
                <div class="register-brand-panel h-100">
                    <div class="brand-badge">
                        Tạo tài khoản nhà đầu tư
                    </div>

                    <h1 class="brand-title">
                        Bắt đầu tìm kiếm cơ hội đầu tư phù hợp
                    </h1>

                    <p class="brand-desc">
                        Đăng ký tài khoản để xem các deal mua bán doanh nghiệp, mua cổ phần hoặc góp vốn.
                        Các hồ sơ chi tiết chỉ được truy cập sau khi đồng ý bảo mật và được seller duyệt.
                    </p>

                    <div class="brand-feature">
                        <div class="brand-feature-dot"></div>
                        <div>
                            <div class="brand-feature-title">Xem deal công khai</div>
                            <p class="brand-feature-text">
                                Tìm kiếm các cơ hội theo ngành nghề, loại giao dịch và quy mô đầu tư.
                            </p>
                        </div>
                    </div>

                    <div class="brand-feature">
                        <div class="brand-feature-dot"></div>
                        <div>
                            <div class="brand-feature-title">Lưu deal quan tâm</div>
                            <p class="brand-feature-text">
                                Theo dõi các deal phù hợp trước khi yêu cầu xem hồ sơ chi tiết.
                            </p>
                        </div>
                    </div>

                    <div class="brand-feature">
                        <div class="brand-feature-dot"></div>
                        <div>
                            <div class="brand-feature-title">Gửi offer trên nền tảng</div>
                            <p class="brand-feature-text">
                                Trao đổi với seller và gửi đề xuất mua cổ phần, mua công ty hoặc góp vốn.
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Right form panel --}}
            <div class="col-lg-6">
                <div class="register-form-panel">

                    <div class="mb-4">
                        <h2 class="form-title">
                            Đăng ký tài khoản
                        </h2>

                        <p class="form-subtitle">
                            Tạo tài khoản để bắt đầu sử dụng nền tảng.
                        </p>
                    </div>

                    @if ($errors->any())
                        <div class="alert alert-danger mb-4">
                            <strong>Vui lòng kiểm tra lại thông tin.</strong>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('register') }}">
                        @csrf

                        {{-- Name --}}
                        <div class="mb-3">
                            <label for="name" class="form-label">
                                Họ và tên
                            </label>

                            <input
                                id="name"
                                type="text"
                                name="name"
                                value="{{ old('name') }}"
                                class="form-control @error('name') is-invalid @enderror"
                                placeholder="Nhập họ và tên"
                                required
                                autofocus
                                autocomplete="name"
                            >

                            @error('name')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        {{-- Email --}}
                        <div class="mb-3">
                            <label for="email" class="form-label">
                                Email
                            </label>

                            <input
                                id="email"
                                type="email"
                                name="email"
                                value="{{ old('email') }}"
                                class="form-control @error('email') is-invalid @enderror"
                                placeholder="Ví dụ: investor@example.com"
                                required
                                autocomplete="username"
                            >

                            @error('email')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
{{-- Role --}}
<div class="mb-3">
    <label for="role" class="form-label">
        Bạn muốn đăng ký với vai trò
    </label>

    <select
        id="role"
        name="role"
        class="form-control @error('role') is-invalid @enderror"
        required
    >
        <option value="">-- Chọn vai trò --</option>

        <option value="buyer" {{ old('role') === 'buyer' ? 'selected' : '' }}>
            Người mua / Nhà đầu tư
        </option>

        <option value="seller" {{ old('role') === 'seller' ? 'selected' : '' }}>
            Người bán / Gọi vốn
        </option>

        <option value="support" {{ old('role') === 'support' ? 'selected' : '' }}>
            Nhân viên hỗ trợ
        </option>
    </select>

    @error('role')
        <div class="invalid-feedback">
            {{ $message }}
        </div>
    @enderror
</div>

                        {{-- Password --}}
                        <div class="mb-3">
                            <label for="password" class="form-label">
                                Mật khẩu
                            </label>

                            <input
                                id="password"
                                type="password"
                                name="password"
                                class="form-control @error('password') is-invalid @enderror"
                                placeholder="Tối thiểu 8 ký tự"
                                required
                                autocomplete="new-password"
                            >

                            @error('password')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        {{-- Confirm Password --}}
                        <div class="mb-4">
                            <label for="password_confirmation" class="form-label">
                                Xác nhận mật khẩu
                            </label>

                            <input
                                id="password_confirmation"
                                type="password"
                                name="password_confirmation"
                                class="form-control @error('password_confirmation') is-invalid @enderror"
                                placeholder="Nhập lại mật khẩu"
                                required
                                autocomplete="new-password"
                            >

                            @error('password_confirmation')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        <button type="submit" class="btn btn-orange w-100">
                            Đăng ký
                        </button>
                    </form>

                    <div class="note-box">
                        <div class="note-title">
                            Lưu ý
                        </div>

                        <p class="note-text">
                            Sau khi đăng ký, tài khoản mặc định sẽ là người mua / nhà đầu tư.
                            Một số chức năng như xem Data Room hoặc gửi offer có thể yêu cầu xác minh thêm.
                        </p>
                    </div>

                    <div class="text-center mt-4 text-muted small">
                        Đã có tài khoản?

                        <a href="{{ route('login') }}" class="auth-link">
                            Đăng nhập ngay
                        </a>
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