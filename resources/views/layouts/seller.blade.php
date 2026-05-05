<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>@yield('title', 'Seller Panel')</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    {{-- Bootstrap CSS --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        :root {
            --seller-orange: #f97316;
            --seller-orange-dark: #ea580c;
            --seller-orange-light: #fff7ed;
            --seller-orange-soft: #ffedd5;
            --seller-orange-border: #fed7aa;
            --seller-dark: #1f2937;
            --seller-muted: #6b7280;
            --seller-sidebar-width: 292px;
        }

        body {
            min-height: 100vh;
            background:
                radial-gradient(circle at top left, rgba(249, 115, 22, 0.12), transparent 30%),
                linear-gradient(135deg, #fff7ed, #ffffff);
            font-family: system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", sans-serif;
            color: var(--seller-dark);
        }

        .seller-sidebar {
            width: var(--seller-sidebar-width);
            min-height: 100vh;
            background: rgba(255, 255, 255, 0.96);
            border-right: 1px solid var(--seller-orange-border);
            box-shadow: 8px 0 32px rgba(249, 115, 22, 0.08);
            position: fixed;
            top: 0;
            left: 0;
            z-index: 1030;
            display: flex;
            flex-direction: column;
        }

        .seller-main {
            min-height: 100vh;
            margin-left: var(--seller-sidebar-width);
            display: flex;
            flex-direction: column;
        }

        .seller-logo-wrap {
            padding: 26px 24px;
            border-bottom: 1px solid var(--seller-orange-border);
        }

        .seller-logo {
            font-size: 25px;
            font-weight: 900;
            color: var(--seller-orange);
            margin: 0;
        }

        .seller-logo-subtitle {
            font-size: 13px;
            color: var(--seller-muted);
            margin: 6px 0 0;
        }

        .seller-profile-card {
            margin: 18px;
            padding: 16px;
            border-radius: 22px;
            background:
                radial-gradient(circle at top right, rgba(249, 115, 22, 0.18), transparent 40%),
                var(--seller-orange-light);
            border: 1px solid var(--seller-orange-border);
        }

        .seller-avatar {
            width: 48px;
            height: 48px;
            border-radius: 18px;
            background: linear-gradient(135deg, var(--seller-orange), #fb923c);
            color: #ffffff;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-weight: 900;
            font-size: 18px;
            flex: 0 0 auto;
            box-shadow: 0 10px 22px rgba(249, 115, 22, 0.24);
        }

        .seller-avatar-sm {
            width: 42px;
            height: 42px;
            border-radius: 16px;
            background: linear-gradient(135deg, var(--seller-orange), #fb923c);
            color: #ffffff;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-weight: 900;
            font-size: 16px;
            flex: 0 0 auto;
        }

        .seller-profile-label {
            font-size: 12px;
            color: var(--seller-muted);
            margin-bottom: 2px;
        }

        .seller-profile-name {
            font-size: 15px;
            font-weight: 800;
            color: var(--seller-dark);
            margin-bottom: 2px;
            max-width: 160px;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .seller-profile-role {
            display: inline-flex;
            padding: 4px 9px;
            border-radius: 999px;
            background: #ffffff;
            color: var(--seller-orange-dark);
            font-size: 11px;
            font-weight: 800;
            border: 1px solid var(--seller-orange-border);
        }

        .seller-nav {
            padding: 16px 14px;
            flex: 1;
            overflow-y: auto;
        }

        .seller-nav-label {
            padding: 0 10px;
            margin: 12px 0 8px;
            color: #9ca3af;
            font-size: 11px;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 0.06em;
        }

        .seller-nav-link {
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

        .seller-nav-link:hover {
            background: var(--seller-orange-light);
            color: var(--seller-orange-dark);
            transform: translateX(2px);
        }

        .seller-nav-link.active {
            background: linear-gradient(135deg, var(--seller-orange), #fb923c);
            color: #ffffff;
            box-shadow: 0 12px 22px rgba(249, 115, 22, 0.26);
        }

        .seller-nav-dot {
            width: 9px;
            height: 9px;
            border-radius: 999px;
            background: var(--seller-orange-border);
            flex: 0 0 auto;
        }

        .seller-nav-link.active .seller-nav-dot {
            background: #ffffff;
        }

        .seller-sidebar-footer {
            padding: 16px 18px 22px;
            border-top: 1px solid var(--seller-orange-border);
        }

        .seller-logout-btn {
            width: 100%;
            border: none;
            border-radius: 16px;
            background: var(--seller-orange);
            color: #ffffff;
            font-weight: 800;
            padding: 12px 16px;
            transition: all 0.18s ease;
        }

        .seller-logout-btn:hover {
            background: var(--seller-orange-dark);
            transform: translateY(-1px);
            box-shadow: 0 10px 24px rgba(249, 115, 22, 0.22);
        }

        .seller-topbar {
            background: rgba(255, 255, 255, 0.92);
            border-bottom: 1px solid var(--seller-orange-border);
            backdrop-filter: blur(14px);
            position: sticky;
            top: 0;
            z-index: 1020;
            padding: 18px 28px;
        }

        .seller-page-title {
            font-size: 26px;
            line-height: 1.2;
            font-weight: 900;
            color: var(--seller-dark);
            margin-bottom: 4px;
        }

        .seller-page-description {
            font-size: 14px;
            color: var(--seller-muted);
            margin: 0;
        }

        .seller-top-profile {
            background: var(--seller-orange-light);
            border: 1px solid var(--seller-orange-border);
            border-radius: 18px;
            padding: 8px 12px;
            min-width: 190px;
        }

        .seller-top-profile-name {
            font-size: 14px;
            font-weight: 800;
            color: var(--seller-dark);
            margin: 0;
            line-height: 1.2;
        }

        .seller-top-profile-role {
            font-size: 12px;
            color: var(--seller-muted);
            margin: 2px 0 0;
        }

        .seller-content {
            padding: 28px;
            flex: 1;
        }

        .seller-mobile-bar {
            display: none;
            background: rgba(255, 255, 255, 0.96);
            border-bottom: 1px solid var(--seller-orange-border);
            padding: 12px 16px;
            position: sticky;
            top: 0;
            z-index: 1040;
        }

        .seller-mobile-menu-btn {
            border: 1px solid var(--seller-orange-border);
            background: var(--seller-orange-light);
            color: var(--seller-orange-dark);
            border-radius: 14px;
            font-weight: 800;
            padding: 9px 12px;
        }

        .seller-mobile-title {
            font-size: 18px;
            font-weight: 900;
            color: var(--seller-orange);
            margin: 0;
        }

        @media (max-width: 991.98px) {
            .seller-sidebar {
                display: none;
            }

            .seller-main {
                margin-left: 0;
            }

            .seller-mobile-bar {
                display: flex;
            }

            .seller-topbar {
                padding: 18px;
            }

            .seller-topbar-inner {
                flex-direction: column;
                align-items: stretch !important;
            }

            .seller-top-profile {
                display: none !important;
            }

            .seller-content {
                padding: 18px;
            }
        }

.seller-nav-text {
    flex: 1;
}

.seller-nav-badge {
    min-width: 24px;
    height: 24px;
    padding: 0 8px;
    border-radius: 999px;
    background: #ef4444;
    color: #ffffff;
    font-size: 12px;
    font-weight: 900;
    display: inline-flex;
    align-items: center;
    justify-content: center;
}

.seller-nav-link.active .seller-nav-badge {
    background: #ffffff;
    color: var(--seller-orange-dark);
}
    </style>
</head>

<body>
@php
    $seller = Auth::user();
    $sellerName = $seller->name ?? 'Người bán';
    $avatarLetter = mb_substr($sellerName, 0, 1, 'UTF-8');

    $pendingOfferCount = \App\Models\Offer::where('seller_id', Auth::id())
        ->where('status', 'pending')
        ->count();

    $pendingAccessCount = \App\Models\DealAccessRequest::whereHas('deal', function ($q) {
        $q->where('seller_id', Auth::id());
    })
    ->where('status', 'pending')
    ->count();

    $menuItems = [
        [
            'label' => 'Trang chủ',
            'route' => 'seller.dashboard',
            'active' => 'seller.dashboard',
        ],
        [
            'label' => 'Hồ sơ doanh nghiệp',
            'route' => 'seller.company.show',
            'active' => 'seller.company.*',
        ],
        [
            'label' => 'Quản lý deal',
            'route' => 'seller.deals.index',
            'active' => 'seller.deals.*',
        ],
        [
            'label' => 'Quản lý offer',
            'route' => 'seller.offers.index',
            'active' => 'seller.offers.*',
            'badge' => $pendingOfferCount,
        ],
        [
            'label' => 'Yêu cầu xem hồ sơ',
            'route' => 'seller.access_requests.index',
            'active' => 'seller.access_requests.*',
            'badge' => $pendingAccessCount,
        ],
    ];
@endphp

<div class="seller-shell">

    <div class="seller-mobile-bar align-items-center justify-content-between">
        <button class="seller-mobile-menu-btn" type="button" data-bs-toggle="offcanvas" data-bs-target="#sellerMobileMenu">
            Menu
        </button>

        <h1 class="seller-mobile-title">
            Seller Panel
        </h1>

        <div class="seller-avatar-sm">
            {{ $avatarLetter }}
        </div>
    </div>

    <aside class="seller-sidebar">
        <div class="seller-logo-wrap">
            <h1 class="seller-logo">M&A Platform</h1>
            <p class="seller-logo-subtitle">Khu vực người bán doanh nghiệp</p>
        </div>

        <div class="seller-profile-card">
            <div class="d-flex align-items-center gap-3">
                <div class="seller-avatar">
                    {{ $avatarLetter }}
                </div>

                <div>
                    <p class="seller-profile-label">Xin chào</p>
                    <p class="seller-profile-name">{{ $sellerName }}</p>
                    <span class="seller-profile-role">Người bán</span>
                </div>
            </div>
        </div>

        <nav class="seller-nav">
            <div class="seller-nav-label">Menu chính</div>

        @foreach ($menuItems as $item)
            <a href="{{ route($item['route']) }}"
            class="seller-nav-link {{ request()->routeIs($item['active']) ? 'active' : '' }}">
                <span class="seller-nav-dot"></span>

                <span class="seller-nav-text">
                    {{ $item['label'] }}
                </span>

                @if (($item['badge'] ?? 0) > 0)
                    <span class="seller-nav-badge">
                        {{ $item['badge'] > 99 ? '99+' : $item['badge'] }}
                    </span>
                @endif
            </a>
        @endforeach
        
        </nav>

        <div class="seller-sidebar-footer">
            <form method="POST" action="{{ route('logout') }}">
                @csrf

                <button type="submit" class="seller-logout-btn">
                    Đăng xuất
                </button>
            </form>
        </div>
    </aside>

    <div class="offcanvas offcanvas-start" tabindex="-1" id="sellerMobileMenu">
        <div class="offcanvas-header">
            <h5 class="offcanvas-title fw-bold text-warning">M&A Platform</h5>
            <button type="button" class="btn-close" data-bs-dismiss="offcanvas"></button>
        </div>

        <div class="offcanvas-body">
            <div class="seller-profile-card mx-0 mt-0 mb-3">
                <div class="d-flex align-items-center gap-3">
                    <div class="seller-avatar">
                        {{ $avatarLetter }}
                    </div>

                    <div>
                        <p class="seller-profile-label">Xin chào</p>
                        <p class="seller-profile-name">{{ $sellerName }}</p>
                        <span class="seller-profile-role">Người bán</span>
                    </div>
                </div>
            </div>

            <nav class="seller-nav px-0">
                @foreach ($menuItems as $item)
                    <a href="{{ route($item['route']) }}"
                       class="seller-nav-link {{ request()->routeIs($item['active']) ? 'active' : '' }}">
                        <span class="seller-nav-dot"></span>
                        <span>{{ $item['label'] }}</span>
                    </a>
                @endforeach
            </nav>
        </div>
    </div>

    <main class="seller-main">
        <header class="seller-topbar">
            <div class="seller-topbar-inner d-flex align-items-center justify-content-between gap-4">
                <div>
                    <h2 class="seller-page-title">
                        @yield('page_title', 'Seller')
                    </h2>

                    <p class="seller-page-description">
                        @yield('page_description', 'Quản lý hồ sơ doanh nghiệp, deal và yêu cầu từ buyer')
                    </p>
                </div>

                <div class="seller-top-profile d-flex align-items-center gap-3">
                    <div class="seller-avatar-sm">
                        {{ $avatarLetter }}
                    </div>

                    <div>
                        <p class="seller-top-profile-name">{{ $sellerName }}</p>
                        <p class="seller-top-profile-role">Seller</p>
                    </div>
                </div>
            </div>
        </header>

        <section class="seller-content">
            @yield('content')
        </section>
    </main>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>