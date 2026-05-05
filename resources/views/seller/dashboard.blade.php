@extends('layouts.seller')

@section('title', 'Trang chủ người bán')
@section('page_title', 'Trang chủ người bán')
@section('page_description', 'Theo dõi công ty, deal, yêu cầu xem hồ sơ và offer từ buyer')

@section('content')

<style>
    :root {
        --seller-orange: #f97316;
        --seller-orange-dark: #ea580c;
        --seller-orange-light: #fff7ed;
        --seller-orange-border: #fed7aa;
    }

    .seller-card {
        background: #ffffff;
        border: 1px solid var(--seller-orange-border);
        border-radius: 24px;
        box-shadow: 0 10px 26px rgba(249, 115, 22, 0.07);
        transition: all 0.22s ease;
    }

    .seller-card:hover {
        transform: translateY(-3px);
        border-color: var(--seller-orange);
        box-shadow: 0 16px 36px rgba(249, 115, 22, 0.14);
    }

    .seller-stat-primary {
        background: linear-gradient(135deg, #f97316, #fb923c);
        color: #ffffff;
        border: none;
    }

    .seller-stat-number {
        font-size: 38px;
        font-weight: 900;
        line-height: 1;
        margin-top: 12px;
    }

    .seller-soft-box {
        background: var(--seller-orange-light);
        border: 1px solid var(--seller-orange-border);
        border-radius: 18px;
        padding: 16px;
    }

    .seller-btn-orange {
        background: var(--seller-orange);
        border: 1px solid var(--seller-orange);
        color: #ffffff;
        border-radius: 14px;
        padding: 11px 18px;
        font-weight: 800;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        justify-content: center;
    }

    .seller-btn-orange:hover {
        background: var(--seller-orange-dark);
        color: #ffffff;
    }

    .seller-btn-soft {
        background: var(--seller-orange-light);
        border: 1px solid var(--seller-orange-border);
        color: var(--seller-orange-dark);
        border-radius: 14px;
        padding: 11px 18px;
        font-weight: 800;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        justify-content: center;
    }

    .seller-btn-soft:hover {
        background: #ffedd5;
        color: var(--seller-orange-dark);
    }

    .seller-status-badge {
        border-radius: 999px;
        padding: 6px 11px;
        font-size: 12px;
        font-weight: 800;
    }
</style>

<div class="row g-4 row-cols-1 row-cols-md-2 row-cols-xl-4 mb-4">
    <div class="col">
        <div class="seller-card seller-stat-primary p-4 h-100">
            <p class="mb-0 text-white-50">Công ty của tôi</p>
            <div class="seller-stat-number">{{ $stats['companies'] ?? 0 }}</div>
            <p class="mb-0 mt-3 text-white-50">Hồ sơ doanh nghiệp đã tạo</p>
        </div>
    </div>

    <div class="col">
        <div class="seller-card p-4 h-100">
            <p class="mb-0 text-muted">Tổng deal</p>
            <div class="seller-stat-number text-warning">{{ $stats['total_deals'] ?? 0 }}</div>
            <p class="mb-0 mt-3 text-muted">Deal do bạn tạo</p>
        </div>
    </div>

    <div class="col">
        <div class="seller-card p-4 h-100">
            <p class="mb-0 text-muted">Deal đang hiển thị</p>
            <div class="seller-stat-number text-warning">{{ $stats['published_deals'] ?? 0 }}</div>
            <p class="mb-0 mt-3 text-muted">Đang public cho buyer xem</p>
        </div>
    </div>

    <div class="col">
        <div class="seller-card p-4 h-100">
            <p class="mb-0 text-muted">Yêu cầu chờ duyệt</p>
            <div class="seller-stat-number text-warning">{{ $stats['pending_access_requests'] ?? 0 }}</div>
            <p class="mb-0 mt-3 text-muted">Buyer muốn xem hồ sơ</p>
        </div>
    </div>
</div>

<div class="seller-card p-4 mb-4">
    <div class="row align-items-center g-4">
        <div class="col-lg-8">
            <h3 class="fw-bold mb-2">Bắt đầu quản lý hồ sơ bán doanh nghiệp</h3>
            <p class="text-muted mb-0">
                Người bán cần tạo hồ sơ doanh nghiệp, tạo deal và theo dõi các yêu cầu xem hồ sơ từ buyer.
                Khi buyer ký NDA và gửi yêu cầu, bạn có thể duyệt hoặc từ chối quyền truy cập.
            </p>
        </div>

        <div class="col-lg-4 text-lg-end">
            <a href="{{ route('seller.company.show') }}" class="seller-btn-orange me-2 mb-2">
                Hồ sơ doanh nghiệp
            </a>

            <a href="{{ route('seller.deals.index') }}" class="seller-btn-soft mb-2">
                Quản lý deal
            </a>
        </div>
    </div>
</div>

<div class="row g-4">
    <div class="col-xl-7">
        <div class="seller-card p-4 h-100">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h4 class="fw-bold mb-1">Deal gần đây</h4>
                    <p class="text-muted mb-0">Các deal mới nhất bạn đã tạo</p>
                </div>

                <a href="{{ route('seller.deals.index') }}" class="seller-btn-soft">
                    Xem tất cả
                </a>
            </div>

            @if ($deals->count() > 0)
                <div class="table-responsive">
                    <table class="table align-middle">
                        <thead>
                        <tr>
                            <th>Tên deal</th>
                            <th>Công ty</th>
                            <th>Trạng thái</th>
                            <th class="text-end">Định giá</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach ($deals->take(5) as $deal)
                            <tr>
                                <td class="fw-bold">{{ $deal->title }}</td>
                                <td>{{ $deal->company->legal_name ?? 'Chưa có công ty' }}</td>
                                <td>
                                    <span class="seller-status-badge bg-warning-subtle text-warning-emphasis">
                                        {{ $deal->status }}
                                    </span>
                                </td>
                                <td class="text-end">
                                    @if ($deal->valuation)
                                        {{ number_format($deal->valuation, 0, ',', '.') }} {{ $deal->currency ?? 'VND' }}
                                    @else
                                        Chưa công bố
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="seller-soft-box text-center">
                    <h5 class="fw-bold">Bạn chưa có deal nào</h5>
                    <p class="text-muted mb-3">Tạo deal đầu tiên để buyer có thể xem và quan tâm.</p>
                    <a href="{{ route('seller.deals.index') }}" class="seller-btn-orange">
                        Tạo deal
                    </a>
                </div>
            @endif
        </div>
    </div>

    <div class="col-xl-5">
        <div class="seller-card p-4 h-100">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h4 class="fw-bold mb-1">Yêu cầu xem hồ sơ</h4>
                    <p class="text-muted mb-0">Buyer đang chờ bạn duyệt</p>
                </div>

                <a href="{{ route('seller.access_requests.index') }}" class="seller-btn-soft">
                    Xem
                </a>
            </div>

            @if ($accessRequests->count() > 0)
                <div class="d-flex flex-column gap-3">
                    @foreach ($accessRequests as $request)
                        <div class="seller-soft-box">
                            <div class="d-flex justify-content-between gap-3">
                                <div>
                                    <h6 class="fw-bold mb-1">
                                        {{ $request->buyer->name ?? 'Buyer' }}
                                    </h6>

                                    <p class="text-muted small mb-1">
                                        Muốn xem: {{ $request->deal->title ?? 'Deal' }}
                                    </p>

                                    <span class="seller-status-badge bg-warning-subtle text-warning-emphasis">
                                        {{ $request->status }}
                                    </span>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="seller-soft-box text-center">
                    <h5 class="fw-bold">Chưa có yêu cầu nào</h5>
                    <p class="text-muted mb-0">
                        Khi buyer yêu cầu xem hồ sơ, thông tin sẽ xuất hiện ở đây.
                    </p>
                </div>
            @endif
        </div>
    </div>
</div>

@endsection