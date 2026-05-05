<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>@yield('title', 'Admin Panel') - M&A System</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <style>
        :root {
            --admin-orange: #ff6600; /* Màu cam chủ đạo của bạn */
            --admin-orange-hover: #e65c00;
            --admin-bg: #f8fafc;
            --text-main: #334155;
        }

        body {
            background: var(--admin-bg);
            font-family: 'Segoe UI', Arial, sans-serif;
            color: var(--text-main);
        }

        /* SIDEBAR */
        .admin-sidebar {
            width: 280px;
            height: 100vh;
            position: fixed;
            background: white;
            border-right: 1px solid #e2e8f0;
            padding: 24px 16px;
            z-index: 1000;
        }

        .sidebar-brand {
            display: flex;
            align-items: center;
            gap: 10px;
            font-size: 1.4rem;
            font-weight: 800;
            color: var(--admin-orange);
            text-decoration: none;
            margin-bottom: 40px;
            padding-left: 10px;
        }

        /* MENU LINK */
        .admin-link {
            display: flex;
            justify-content: space-between; /* Để đẩy số sang bên phải */
            align-items: center;
            padding: 12px 16px;
            border-radius: 12px;
            text-decoration: none;
            color: #64748b;
            margin-bottom: 8px;
            transition: all 0.3s;
            font-weight: 500;
        }

        .admin-link i {
            font-size: 1.2rem;
            margin-right: 12px;
        }

        .admin-link:hover {
            background: #fff5eb;
            color: var(--admin-orange);
        }

        /* TRẠNG THÁI ACTIVE (MÀU CAM) */
        .admin-link.active {
            background: var(--admin-orange);
            color: white !important;
        }

        /* CON SỐ THÔNG BÁO (BADGE) */
        .menu-badge {
            background: #fee2e2;
            color: #ef4444;
            font-size: 0.75rem;
            font-weight: 700;
            padding: 2px 8px;
            border-radius: 20px;
        }

        .admin-link.active .menu-badge {
            background: white;
            color: var(--admin-orange);
        }

        /* MAIN CONTENT */
        .admin-main {
            margin-left: 280px;
            min-height: 100vh;
        }

        .admin-topbar {
            background: white;
            padding: 16px 32px;
            border-bottom: 1px solid #e2e8f0;
            position: sticky;
            top: 0;
            z-index: 999;
        }

        .admin-content {
            padding: 30px;
        }
    </style>
</head>

<body>

@php
    $admin = auth()->user();

    $menu = [
        ['label' => 'Tổng quan', 'route' => 'admin.dashboard', 'icon' => 'bi-grid-1x2'],
        ['label' => 'Quản lý Công ty', 'route' => 'admin.companies.index', 'icon' => 'bi-building'],
        ['label' => 'Duyệt Công ty mới', 'route' => 'admin.companies.pending', 'icon' => 'bi-patch-check'],
        ['label' => 'Duyệt hồ sơ người dùng', 'route' => 'admin.kyc.pending', 'icon' => 'bi-person-vcard'],
        ['label' => 'Duyệt bài đăng Deals', 'route' => 'admin.deals.pending', 'icon' => 'bi-briefcase'],
        ['label' => 'Quản lý Người dùng', 'route' => 'admin.users.index', 'icon' => 'bi-people'],
    ];
@endphp

<div class="admin-sidebar shadow-sm">
    <a href="{{ route('admin.dashboard') }}" class="sidebar-brand">
        <i class="bi bi-shield-lock-fill"></i>
        <span>M&A ADMIN</span>
    </a>

    <div class="nav flex-column">
@foreach($menu as $item)
    <a href="{{ route($item['route']) }}"
       class="admin-link {{ request()->routeIs($item['route']) ? 'active' : '' }}">
        
        <div class="d-flex align-items-center">
            <i class="bi {{ $item['icon'] }}"></i>
            <span>{{ $item['label'] }}</span>
        </div>

        @if($item['route'] === 'admin.companies.pending' && ($pending_companies_count ?? 0) > 0)
            <span class="menu-badge">{{ $pending_companies_count }}</span>
        @endif

        @if($item['route'] === 'admin.kyc.pending' && ($pending_kyc_count ?? 0) > 0)
            <span class="menu-badge">{{ $pending_kyc_count }}</span>
        @endif

        @if($item['route'] === 'admin.deals.pending' && ($pending_deals_count ?? 0) > 0)
            <span class="menu-badge">{{ $pending_deals_count }}</span>
        @endif
    </a>
@endforeach
    </div>
    

    <div style="position: absolute; bottom: 30px; width: calc(100% - 32px);">
        <hr>
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button class="btn w-100 text-start admin-link border-0" style="background: transparent;">
                <i class="bi bi-box-arrow-left"></i> Đăng xuất
            </button>
        </form>
    </div>
</div>

<div class="admin-main">
    <div class="admin-topbar d-flex justify-content-between align-items-center">
        <h5 class="mb-0 fw-bold">@yield('page_title', 'Dashboard')</h5>
        
        <div class="d-flex align-items-center gap-3">
            <span class="small fw-semibold">{{ $admin->name }}</span>
            <div class="rounded-circle bg-secondary text-white d-flex align-items-center justify-content-center" style="width: 35px; height: 35px; background-color: var(--admin-orange) !important;">
                {{ substr($admin->name, 0, 1) }}
            </div>
        </div>
    </div>

    <div class="admin-content">
        @yield('content')
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>