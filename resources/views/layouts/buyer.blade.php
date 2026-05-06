<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>@yield('title', 'Buyer Panel')</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    {{-- Bootstrap CSS --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    {{-- Laravel Vite --}}
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        :root {
            --buyer-orange: #f97316;
            --buyer-orange-dark: #ea580c;
            --buyer-orange-light: #fff7ed;
            --buyer-orange-soft: #ffedd5;
            --buyer-orange-border: #fed7aa;
            --buyer-dark: #1f2937;
            --buyer-muted: #6b7280;
            --buyer-body: #fff7ed;
            --buyer-white: #ffffff;
            --buyer-sidebar-width: 292px;
        }

        body {
            background:
                radial-gradient(circle at top left, rgba(249, 115, 22, 0.12), transparent 30%),
                linear-gradient(135deg, #fff7ed, #ffffff);
            min-height: 100vh;
            color: var(--buyer-dark);
            font-family: system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", sans-serif;
        }

        .buyer-shell {
            min-height: 100vh;
        }

        .buyer-sidebar {
            width: var(--buyer-sidebar-width);
            min-height: 100vh;
            background: rgba(255, 255, 255, 0.96);
            border-right: 1px solid var(--buyer-orange-border);
            box-shadow: 8px 0 32px rgba(249, 115, 22, 0.08);
            position: fixed;
            top: 0;
            left: 0;
            z-index: 1030;
            display: flex;
            flex-direction: column;
        }

        .buyer-main {
            min-height: 100vh;
            margin-left: var(--buyer-sidebar-width);
            display: flex;
            flex-direction: column;
        }

        .buyer-logo-wrap {
            padding: 26px 24px;
            border-bottom: 1px solid var(--buyer-orange-border);
        }

        .buyer-logo {
            font-size: 25px;
            font-weight: 900;
            color: var(--buyer-orange);
            margin: 0;
            letter-spacing: -0.6px;
        }

        .buyer-logo-subtitle {
            font-size: 13px;
            color: var(--buyer-muted);
            margin: 6px 0 0;
        }

        .buyer-profile-card {
            margin: 18px 18px 8px;
            padding: 16px;
            border-radius: 22px;
            background:
                radial-gradient(circle at top right, rgba(249, 115, 22, 0.18), transparent 40%),
                var(--buyer-orange-light);
            border: 1px solid var(--buyer-orange-border);
        }

        .buyer-avatar {
            width: 48px;
            height: 48px;
            border-radius: 18px;
            background: linear-gradient(135deg, var(--buyer-orange), #fb923c);
            color: #ffffff;
display: inline-flex;
            align-items: center;
            justify-content: center;
            font-weight: 900;
            font-size: 18px;
            flex: 0 0 auto;
            box-shadow: 0 10px 22px rgba(249, 115, 22, 0.24);
        }

        .buyer-avatar-sm {
            width: 42px;
            height: 42px;
            border-radius: 16px;
            background: linear-gradient(135deg, var(--buyer-orange), #fb923c);
            color: #ffffff;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-weight: 900;
            font-size: 16px;
            flex: 0 0 auto;
            box-shadow: 0 10px 20px rgba(249, 115, 22, 0.18);
        }

        .buyer-profile-label {
            font-size: 12px;
            color: var(--buyer-muted);
            margin-bottom: 2px;
        }

        .buyer-profile-name {
            font-size: 15px;
            font-weight: 800;
            color: var(--buyer-dark);
            margin-bottom: 2px;
            max-width: 160px;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .buyer-profile-role {
            display: inline-flex;
            align-items: center;
            width: fit-content;
            padding: 4px 9px;
            border-radius: 999px;
            background: #ffffff;
            color: var(--buyer-orange-dark);
            font-size: 11px;
            font-weight: 800;
            border: 1px solid var(--buyer-orange-border);
        }

        .buyer-nav {
            padding: 16px 14px;
            flex: 1;
            overflow-y: auto;
        }

        .buyer-nav-label {
            padding: 0 10px;
            margin: 12px 0 8px;
            color: #9ca3af;
            font-size: 11px;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 0.06em;
        }

        .buyer-nav-link {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 13px 14px;
            border-radius: 16px;
            color: #4b5563;
            text-decoration: none;
            font-size: 15px;
            font-weight: 700;
            transition: all 0.18s ease;
            margin-bottom: 6px;
        }

        .buyer-nav-link:hover {
            background: var(--buyer-orange-light);
            color: var(--buyer-orange-dark);
            transform: translateX(2px);
        }

        .buyer-nav-link.active {
            background: linear-gradient(135deg, var(--buyer-orange), #fb923c);
            color: #ffffff;
            box-shadow: 0 12px 22px rgba(249, 115, 22, 0.26);
        }

        .buyer-nav-dot {
            width: 9px;
            height: 9px;
            border-radius: 999px;
            background: var(--buyer-orange-border);
            flex: 0 0 auto;
        }

        .buyer-nav-link.active .buyer-nav-dot {
background: #ffffff;
        }

        .buyer-sidebar-footer {
            padding: 16px 18px 22px;
            border-top: 1px solid var(--buyer-orange-border);
        }

        .buyer-logout-btn {
            width: 100%;
            border: none;
            border-radius: 16px;
            background: var(--buyer-orange);
            color: #ffffff;
            font-weight: 800;
            padding: 12px 16px;
            transition: all 0.18s ease;
        }

        .buyer-logout-btn:hover {
            background: var(--buyer-orange-dark);
            transform: translateY(-1px);
            box-shadow: 0 10px 24px rgba(249, 115, 22, 0.22);
        }

        .buyer-topbar {
            background: rgba(255, 255, 255, 0.92);
            border-bottom: 1px solid var(--buyer-orange-border);
            backdrop-filter: blur(14px);
            position: sticky;
            top: 0;
            z-index: 1020;
            padding: 18px 28px;
        }

        .buyer-page-title {
            font-size: 26px;
            line-height: 1.2;
            font-weight: 900;
            color: var(--buyer-dark);
            margin-bottom: 4px;
            letter-spacing: -0.5px;
        }

        .buyer-page-description {
            font-size: 14px;
            color: var(--buyer-muted);
            margin: 0;
        }

        .buyer-search-wrap {
            min-width: 360px;
            max-width: 480px;
        }

        .buyer-search {
            display: flex;
            align-items: center;
            background: var(--buyer-orange-light);
            border: 1px solid var(--buyer-orange-border);
            border-radius: 18px;
            overflow: hidden;
            box-shadow: 0 8px 18px rgba(249, 115, 22, 0.06);
        }

        .buyer-search input {
            border: none;
            outline: none;
            background: transparent;
            min-height: 46px;
            padding: 0 16px;
            font-size: 14px;
            flex: 1;
            color: var(--buyer-dark);
        }

        .buyer-search input:focus {
            box-shadow: none;
        }

        .buyer-search button {
            border: none;
            background: var(--buyer-orange);
            color: #ffffff;
            min-height: 46px;
            padding: 0 18px;
            font-weight: 800;
            transition: all 0.18s ease;
        }

        .buyer-search button:hover {
            background: var(--buyer-orange-dark);
        }

        .buyer-top-profile {
            background: var(--buyer-orange-light);
            border: 1px solid var(--buyer-orange-border);
            border-radius: 18px;
            padding: 8px 12px;
            min-width: 190px;
        }

        .buyer-top-profile-name {
            font-size: 14px;
            font-weight: 800;
            color: var(--buyer-dark);
            margin: 0;
            line-height: 1.2;
        }

        .buyer-top-profile-role {
font-size: 12px;
            color: var(--buyer-muted);
            margin: 2px 0 0;
        }

        .buyer-content {
            padding: 28px;
            flex: 1;
        }

        .buyer-mobile-bar {
            display: none;
            background: rgba(255, 255, 255, 0.96);
            border-bottom: 1px solid var(--buyer-orange-border);
            padding: 12px 16px;
            position: sticky;
            top: 0;
            z-index: 1040;
        }

        .buyer-mobile-menu-btn {
            border: 1px solid var(--buyer-orange-border);
            background: var(--buyer-orange-light);
            color: var(--buyer-orange-dark);
            border-radius: 14px;
            font-weight: 800;
            padding: 9px 12px;
        }

        .buyer-mobile-title {
            font-size: 18px;
            font-weight: 900;
            color: var(--buyer-orange);
            margin: 0;
        }

        .buyer-offcanvas {
            background: #ffffff;
        }

        .buyer-offcanvas .offcanvas-header {
            border-bottom: 1px solid var(--buyer-orange-border);
        }

        .buyer-offcanvas-title {
            color: var(--buyer-orange);
            font-weight: 900;
        }

        @media (max-width: 1199.98px) {
            .buyer-search-wrap {
                min-width: 280px;
                max-width: 100%;
            }
        }

        @media (max-width: 991.98px) {
            .buyer-sidebar {
                display: none;
            }

            .buyer-main {
                margin-left: 0;
            }

            .buyer-mobile-bar {
                display: flex;
            }

            .buyer-topbar {
                padding: 18px 18px;
            }

            .buyer-topbar-inner {
                flex-direction: column;
                align-items: stretch !important;
            }

            .buyer-topbar-actions {
                flex-direction: column;
                align-items: stretch !important;
            }

            .buyer-search-wrap {
                min-width: 100%;
            }

            .buyer-top-profile {
                display: none !important;
            }

            .buyer-content {
                padding: 18px;
            }
        }

        @media (max-width: 575.98px) {
            .buyer-page-title {
                font-size: 22px;
            }

            .buyer-search {
                flex-direction: column;
                align-items: stretch;
            }

            .buyer-search input {
                width: 100%;
            }

            .buyer-search button {
                width: 100%;
            }

        }
    </style>
</head>

<body>
@php
    $buyer = Auth::user();
    $buyerName = $buyer->name ?? 'Người mua';
    $avatarLetter = mb_substr($buyerName, 0, 1, 'UTF-8');

    $menuItems = [
        [
            'label' => 'Trang chủ',
            'route' => 'buyer.home',
'active' => 'buyer.home',
        ],
        [
            'label' => 'Deal quan tâm',
            'route' => 'buyer.interests',
            'active' => 'buyer.interests',
        ],
        [
            'label' => 'Deal thành công',
            'route' => 'buyer.successful_deals.index',
            'active' => 'buyer.successful_deals.*',
        ],
        [
            'label' => 'Giới thiệu',
            'route' => 'buyer.about',
            'active' => 'buyer.about',
        ],
        [
            'label' => 'Chính sách bảo mật',
            'route' => 'buyer.privacy',
            'active' => 'buyer.privacy',
        ],
    ];

    // ✅ THÊM PHẦN THÔNG BÁO Ở ĐÂY (QUAN TRỌNG)
    $notifications = \App\Models\Notification::where('user_id', $buyer->id)
        ->latest()
        ->take(5)
        ->get();

    $unreadCount = \App\Models\Notification::where('user_id', $buyer->id)
        ->where('is_read', false)
        ->count();
@endphp

<div class="buyer-shell">

    {{-- Mobile bar --}}
    <div class="buyer-mobile-bar align-items-center justify-content-between">
        <button class="buyer-mobile-menu-btn" type="button" data-bs-toggle="offcanvas" data-bs-target="#buyerMobileMenu">
            Menu
        </button>

        <h1 class="buyer-mobile-title">
            M&A Platform
        </h1>

        <div class="buyer-avatar-sm">
            {{ $avatarLetter }}
        </div>
    </div>
    

    {{-- Sidebar desktop --}}
    <aside class="buyer-sidebar">
        <div class="buyer-logo-wrap">
            <h1 class="buyer-logo">M&A Platform</h1>
            <p class="buyer-logo-subtitle">Khu vực người mua và nhà đầu tư</p>
        </div>

        @if(!$kyc)
            <div class="buyer-profile-card">
                <div class="d-flex align-items-center gap-3">
                    <div class="buyer-avatar">
                        {{ $avatarLetter }}
                    </div>

                    <div class="min-w-0">
                        <p class="buyer-profile-label">Xin chào</p>
                        <p class="buyer-profile-name">{{ $buyerName }}</p>
                        <span class="buyer-profile-role">Người mua</span>
                    </div>
                </div>
            </div>
        @else
            <a href="{{route('buyer.kyc.show')}}">
                <div class="buyer-profile-card">
                    <div class="d-flex align-items-center gap-3">
                        <div class="buyer-avatar">
                            {{ $avatarLetter }}
                        </div>

                        <div class="min-w-0">
                            <p class="buyer-profile-label">Xin chào</p>
                            <p class="buyer-profile-name">{{ $buyerName }}</p>
                            <span class="buyer-profile-role">Người mua</span>
                        </div>
                    </div>
                </div>
            </a>
        @endif
<div class="hihi" style="text-align:center;">
            @if (!$kyc)
                <a href="{{ route('buyer.kyc.create') }}">🔐 Đi đến xác minh</a>

            @elseif ($kyc->status === 'approved')
                <span class="text-success">✅ Đã xác minh</span>

            @elseif ($kyc->status === 'pending')
                <span class="text-warning">⏳ Đang chờ duyệt</span>

            @elseif ($kyc->status === 'rejected')
                <div>
                    <span class="text-danger">❌ Bị từ chối</span><br>
                    <a href="{{ route('buyer.kyc.create') }}">🔄 Xác minh lại</a>
                </div>
            @endif
        </div>


        <div class="hihi" style="text-align:center;">
            @if (!$kyc)
                <a href="{{ route('buyer.kyc.create') }}">🔐 Đi đến xác minh</a>

            @elseif ($kyc->status === 'approved')
                <span class="text-success">✅ Đã xác minh</span>

            @elseif ($kyc->status === 'pending')
                <span class="text-warning">⏳ Đang chờ duyệt</span>

            @elseif ($kyc->status === 'rejected')
                <div>
                    <span class="text-danger">❌ Bị từ chối</span><br>
                    <a href="{{ route('buyer.kyc.create') }}">🔄 Xác minh lại</a>
                </div>
            @endif
        </div>



        <nav class="buyer-nav">
            <div class="buyer-nav-label">Menu chính</div>

            @foreach ($menuItems as $item)
                <a href="{{ route($item['route']) }}"
                   class="buyer-nav-link {{ request()->routeIs($item['active']) ? 'active' : '' }}">
                    <span class="buyer-nav-dot"></span>
                    <span>{{ $item['label'] }}</span>
                </a>
            @endforeach
        </nav>


        <div class="buyer-sidebar-footer">
            <form method="POST" action="{{ route('logout') }}">
                @csrf

                <button type="submit" class="buyer-logout-btn">
                    Đăng xuất
                </button>
            </form>
        </div>
    </aside>

    {{-- Mobile offcanvas --}}
    <div class="offcanvas offcanvas-start buyer-offcanvas" tabindex="-1" id="buyerMobileMenu">
        <div class="offcanvas-header">
            <h5 class="offcanvas-title buyer-offcanvas-title">M&A Platform</h5>
            <button type="button" class="btn-close" data-bs-dismiss="offcanvas"></button>
        </div>

        <div class="offcanvas-body">
            <div class="buyer-profile-card mx-0 mt-0 mb-3">
                <div class="d-flex align-items-center gap-3">
                    <div class="buyer-avatar">
                        {{ $avatarLetter }}
                    </div>

                    <div>
                        <p class="buyer-profile-label">Xin chào</p>
                        <p class="buyer-profile-name">{{ $buyerName }}</p>
                        <span class="buyer-profile-role">Người mua</span>
                    </div>
                </div>
            </div>

            <nav class="buyer-nav px-0">
                @foreach ($menuItems as $item)
                    <a href="{{ route($item['route']) }}"
                       class="buyer-nav-link {{ request()->routeIs($item['active']) ? 'active' : '' }}">
                        <span class="buyer-nav-dot"></span>
                        <span>{{ $item['label'] }}</span>
                    </a>
                @endforeach
            </nav>

            <form method="POST" action="{{ route('logout') }}" class="mt-4">
                @csrf
<button type="submit" class="buyer-logout-btn">
                    Đăng xuất
                </button>
            </form>
        </div>
    </div>

    {{-- Main --}}
    <main class="buyer-main">
        <header class="buyer-topbar">
            <div class="buyer-topbar-inner d-flex align-items-center justify-content-between gap-4">
                <div>
                    <h2 class="buyer-page-title">
                        @yield('page_title', 'Buyer')
                    </h2>

                    <p class="buyer-page-description">
                        @yield('page_description', 'Quản lý hoạt động tìm kiếm và đầu tư')
                    </p>
                </div>

               {{-- 🔔 Notification --}}
<div class="dropdown">
    <button class="btn position-relative" data-bs-toggle="dropdown">
        🔔
        @if($unreadCount > 0)
            <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                {{ $unreadCount }}
            </span>
        @endif
    </button>

    <div class="dropdown-menu dropdown-menu-end p-0 shadow"
         style="width: 320px; border-radius: 16px; overflow: hidden;">

        <div class="p-3 border-bottom fw-bold">
            Thông báo
        </div>

        <div style="max-height: 300px; overflow-y: auto;">

            @forelse($notifications as $noti)

                <form method="POST"
                      action="{{ route('notifications.read', $noti->id) }}">
                    @csrf

                    <button class="w-100 text-start border-0 bg-transparent p-3
                        {{ !$noti->is_read ? 'bg-light' : '' }}">

                        <div class="fw-bold">
                            {{ $noti->title }}
                        </div>

                        <div class="text-muted small">
                            {{ $noti->message }}
                        </div>

                    </button>
                </form>

            @empty
                <div class="p-3 text-center text-muted">
                    Không có thông báo
                </div>
            @endforelse

        </div>

    </div>
</div>
            </div>
        </header>

        <section class="buyer-content">
            @yield('content')
        </section>
    </main>
</div>

{{-- Bootstrap JS --}}
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
