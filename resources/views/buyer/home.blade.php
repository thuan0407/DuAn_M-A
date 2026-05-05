@extends('layouts.buyer')

@section('title', 'Trang chủ người mua')
@section('page_title', 'Trang chủ')
@section('page_description', 'Khám phá các cơ hội mua bán doanh nghiệp, mua cổ phần và góp vốn')

@section('content')

@php
    $deals = $deals ?? collect();
    $stats = $stats ?? [];
    $accessRequestsByDealId = $accessRequestsByDealId ?? collect();
    $interestedDealIds = $interestedDealIds ?? collect();
@endphp

<style>
    :root {
        --buyer-orange: #f97316;
        --buyer-orange-dark: #ea580c;
        --buyer-orange-light: #fff7ed;
        --buyer-orange-soft: #ffedd5;
        --buyer-orange-border: #fed7aa;
        --buyer-dark: #1f2937;
        --buyer-muted: #6b7280;
        --buyer-light-gray: #f9fafb;
    }

    body {
        overflow-y: scroll;
    }

    body.modal-open {
        overflow-y: scroll !important;
        padding-right: 0 !important;
    }

    .modal-open .navbar,
    .modal-open .container,
    .modal-open .container-fluid {
        padding-right: 0 !important;
    }

    .modal-backdrop {
        z-index: 1050 !important;
    }

    .buyer-nda-modal {
        z-index: 1060 !important;
    }

    .buyer-nda-modal .modal-dialog {
        pointer-events: auto;
    }

    .buyer-home {
        color: var(--buyer-dark);
    }

    .buyer-section {
        margin-bottom: 28px;
    }

    .buyer-card {
        background: #ffffff;
        border: 1px solid var(--buyer-orange-border);
        border-radius: 24px;
        box-shadow: 0 10px 26px rgba(249, 115, 22, 0.07);
        transition: all 0.22s ease;
    }

    .buyer-card:hover {
        transform: translateY(-3px);
        border-color: var(--buyer-orange);
        box-shadow: 0 16px 36px rgba(249, 115, 22, 0.14);
    }

    .buyer-stat-card {
        padding: 24px;
        height: 100%;
        overflow: hidden;
        position: relative;
    }

    .buyer-stat-card::after {
        content: "";
        position: absolute;
        right: -28px;
        bottom: -28px;
        width: 96px;
        height: 96px;
        border-radius: 999px;
        background: rgba(249, 115, 22, 0.08);
    }

    .buyer-stat-primary {
        background: linear-gradient(135deg, #f97316, #fb923c);
        color: #ffffff;
        border: none;
    }

    .buyer-stat-primary::after {
        background: rgba(255, 255, 255, 0.18);
    }

    .buyer-stat-label {
        font-size: 14px;
        color: var(--buyer-muted);
        margin-bottom: 10px;
    }

    .buyer-stat-primary .buyer-stat-label {
        color: #ffedd5;
    }

    .buyer-stat-number {
        font-size: 40px;
        line-height: 1;
        font-weight: 800;
        color: var(--buyer-orange);
        margin-bottom: 10px;
    }

    .buyer-stat-primary .buyer-stat-number {
        color: #ffffff;
    }

    .buyer-stat-note {
        font-size: 12px;
        color: #9ca3af;
        margin-bottom: 0;
    }

    .buyer-stat-primary .buyer-stat-note {
        color: #ffedd5;
    }

    .buyer-stat-symbol {
        width: 48px;
        height: 48px;
        border-radius: 18px;
        background: var(--buyer-orange-light);
        color: var(--buyer-orange);
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 800;
        font-size: 16px;
    }

    .buyer-stat-primary .buyer-stat-symbol {
        background: rgba(255, 255, 255, 0.22);
        color: #ffffff;
    }

    .buyer-hero {
        background:
            radial-gradient(circle at top right, rgba(249, 115, 22, 0.18), transparent 36%),
            linear-gradient(135deg, #ffffff, #fff7ed);
        border: 1px solid var(--buyer-orange-border);
        border-radius: 28px;
        padding: 30px;
        box-shadow: 0 14px 34px rgba(249, 115, 22, 0.09);
    }

    .buyer-hero-title {
        font-size: 26px;
        font-weight: 800;
        color: var(--buyer-dark);
        margin-bottom: 10px;
    }

    .buyer-hero-text {
        color: var(--buyer-muted);
        line-height: 1.75;
        margin-bottom: 0;
    }

    .buyer-btn-orange {
        background: var(--buyer-orange);
        border: 1px solid var(--buyer-orange);
        color: #ffffff;
        border-radius: 14px;
        padding: 11px 18px;
        font-size: 14px;
        font-weight: 700;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        transition: all 0.2s ease;
    }

    .buyer-btn-orange:hover {
        background: var(--buyer-orange-dark);
        border-color: var(--buyer-orange-dark);
        color: #ffffff;
        transform: translateY(-1px);
    }

    .buyer-btn-soft {
        background: var(--buyer-orange-light);
        border: 1px solid var(--buyer-orange-border);
        color: var(--buyer-orange-dark);
        border-radius: 14px;
        padding: 11px 18px;
        font-size: 14px;
        font-weight: 700;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        transition: all 0.2s ease;
    }

    .buyer-btn-soft:hover {
        background: var(--buyer-orange-soft);
        color: var(--buyer-orange-dark);
        transform: translateY(-1px);
    }

    .buyer-filter-card {
        padding: 24px;
        min-height: 220px;
        position: relative;
        overflow: hidden;
        text-decoration: none;
        display: block;
    }

    .buyer-filter-card::after {
        content: "";
        position: absolute;
        right: -34px;
        bottom: -34px;
        width: 100px;
        height: 100px;
        border-radius: 999px;
        background: rgba(249, 115, 22, 0.08);
    }

    .buyer-filter-code {
        width: 54px;
        height: 54px;
        border-radius: 18px;
        background: var(--buyer-orange-light);
        color: var(--buyer-orange);
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 800;
        margin-bottom: 18px;
    }

    .buyer-filter-title {
        font-size: 18px;
        font-weight: 800;
        color: var(--buyer-dark);
        margin-bottom: 10px;
    }

    .buyer-filter-card:hover .buyer-filter-title {
        color: var(--buyer-orange-dark);
    }

    .buyer-filter-text {
        font-size: 14px;
        color: var(--buyer-muted);
        line-height: 1.65;
        margin-bottom: 0;
    }

    .buyer-panel {
        padding: 28px;
    }

    .buyer-panel-header {
        margin-bottom: 24px;
    }

    .buyer-panel-title {
        font-size: 24px;
        font-weight: 800;
        color: var(--buyer-dark);
        margin-bottom: 4px;
    }

    .buyer-panel-text {
        font-size: 14px;
        color: var(--buyer-muted);
        margin-bottom: 0;
    }

    .buyer-deal-card {
        height: 100%;
        display: flex;
        flex-direction: column;
        padding: 22px;
        position: relative;
        overflow: hidden;
    }

    .buyer-deal-card::before {
        content: "";
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 5px;
        background: linear-gradient(90deg, #f97316, #fb923c);
    }

    .buyer-badge-orange {
        background: var(--buyer-orange-light);
        color: var(--buyer-orange-dark);
        border: 1px solid var(--buyer-orange-border);
        font-weight: 700;
        border-radius: 999px;
        padding: 6px 11px;
        font-size: 12px;
        white-space: nowrap;
    }

    .buyer-badge-status {
        font-weight: 700;
        border-radius: 999px;
        padding: 6px 11px;
        font-size: 12px;
        white-space: nowrap;
    }

    .status-published {
        background: #dcfce7;
        color: #15803d;
    }

    .status-negotiating {
        background: #fef3c7;
        color: #b45309;
    }

    .status-closed {
        background: #f3f4f6;
        color: #4b5563;
    }

    .status-danger {
        background: #fee2e2;
        color: #b91c1c;
    }

    .status-default {
        background: var(--buyer-orange-light);
        color: var(--buyer-orange-dark);
    }

    .buyer-deal-title {
        font-size: 17px;
        line-height: 1.45;
        font-weight: 800;
        color: var(--buyer-dark);
        margin-bottom: 0;
        min-height: 50px;
    }

    .buyer-deal-card:hover .buyer-deal-title {
        color: var(--buyer-orange-dark);
    }

    .buyer-label {
        font-size: 12px;
        color: #9ca3af;
        margin-bottom: 4px;
    }

    .buyer-value {
        color: #374151;
        font-size: 14px;
        font-weight: 600;
        margin-bottom: 0;
    }

    .buyer-description {
        color: var(--buyer-muted);
        font-size: 14px;
        line-height: 1.65;
        min-height: 70px;
        margin-bottom: 0;
    }

    .buyer-valuation-box {
        background: var(--buyer-orange-light);
        border: 1px solid var(--buyer-orange-border);
        border-radius: 18px;
        padding: 16px;
    }

    .buyer-valuation-value {
        color: var(--buyer-orange-dark);
        font-size: 19px;
        font-weight: 800;
        margin-bottom: 0;
    }

    .buyer-empty {
        background: var(--buyer-orange-light);
        border: 1px dashed var(--buyer-orange-border);
        border-radius: 22px;
        padding: 44px;
        text-align: center;
    }

    .deal-actions {
        display: flex;
        gap: 10px;
        margin-top: auto;
        padding-top: 24px;
    }

    .deal-action-item {
        flex: 1 1 0;
        min-width: 0;
    }

    .deal-action-item form {
        width: 100%;
    }

    .deal-action-btn {
        width: 100%;
        min-height: 46px;
        border-radius: 14px;
        font-size: 14px;
        font-weight: 800;
        display: flex;
        align-items: center;
        justify-content: center;
        text-align: center;
        white-space: nowrap;
        padding: 10px 12px;
    }

    .buyer-btn-orange.deal-action-btn:hover,
    .buyer-btn-soft.deal-action-btn:hover {
        transform: none;
    }

    @media (max-width: 991.98px) {
        .buyer-hero {
            padding: 24px;
        }

        .buyer-panel {
            padding: 22px;
        }

        .buyer-stat-number {
            font-size: 34px;
        }
    }

    @media (max-width: 575.98px) {
        .deal-actions {
            flex-direction: column;
        }
    }
</style>

<div class="buyer-home">

    @if (session('success'))
        <div class="alert alert-success rounded-4 border-0 shadow-sm mb-4">
            {{ session('success') }}
        </div>
    @endif

    @if (session('info'))
        <div class="alert alert-info rounded-4 border-0 shadow-sm mb-4">
            {{ session('info') }}
        </div>
    @endif

    @if (session('error'))
        <div class="alert alert-danger rounded-4 border-0 shadow-sm mb-4">
            {{ session('error') }}
        </div>
    @endif

    <div class="buyer-section">
        <div class="row g-4 row-cols-1 row-cols-sm-2 row-cols-lg-4">
            <div class="col">
                <div class="buyer-card buyer-stat-card buyer-stat-primary">
                    <div class="d-flex justify-content-between align-items-start position-relative">
                        <div>
                            <p class="buyer-stat-label">Tổng deal đang mở</p>
                            <div class="buyer-stat-number">{{ $stats['total_deals'] ?? 0 }}</div>
                        </div>

                        <div class="buyer-stat-symbol">D</div>
                    </div>

                    <p class="buyer-stat-note position-relative">
                        Các deal đang được hiển thị trên sàn
                    </p>
                </div>
            </div>

            <div class="col">
                <div class="buyer-card buyer-stat-card">
                    <div class="d-flex justify-content-between align-items-start position-relative">
                        <div>
                            <p class="buyer-stat-label">Deal đã quan tâm</p>
                            <div class="buyer-stat-number">{{ $stats['interested_deals'] ?? 0 }}</div>
                        </div>

                        <div class="buyer-stat-symbol">Q</div>
                    </div>

                    <p class="buyer-stat-note position-relative">
                        Deal bạn đã lưu hoặc đánh dấu quan tâm
                    </p>
                </div>
            </div>

            <div class="col">
                <div class="buyer-card buyer-stat-card">
                    <div class="d-flex justify-content-between align-items-start position-relative">
                        <div>
                            <p class="buyer-stat-label">Yêu cầu xem hồ sơ</p>
                            <div class="buyer-stat-number">{{ $stats['access_requests'] ?? 0 }}</div>
                        </div>

                        <div class="buyer-stat-symbol">H</div>
                    </div>

                    <p class="buyer-stat-note position-relative">
                        Yêu cầu truy cập hồ sơ chi tiết
                    </p>
                </div>
            </div>

            <div class="col">
                <div class="buyer-card buyer-stat-card">
                    <div class="d-flex justify-content-between align-items-start position-relative">
                        <div>
                            <p class="buyer-stat-label">Offer đã gửi</p>
                            <div class="buyer-stat-number">{{ $stats['offers'] ?? 0 }}</div>
                        </div>

                        <div class="buyer-stat-symbol">O</div>
                    </div>

                    <p class="buyer-stat-note position-relative">
                        Đề xuất mua hoặc góp vốn đã gửi
                    </p>
                </div>
            </div>
        </div>
    </div>

    <div class="buyer-section">
        <div class="buyer-hero">
            <div class="row align-items-center g-4">
                <div class="col-lg-8">
                    <h3 class="buyer-hero-title">
                        Danh sách cơ hội đầu tư
                    </h3>

                    <p class="buyer-hero-text">
                        Đây là các deal đang được hiển thị trên hệ thống. Bạn có thể xem thông tin cơ bản,
                        theo dõi các cơ hội phù hợp, sau đó yêu cầu xem hồ sơ chi tiết nếu muốn tìm hiểu sâu hơn.
                    </p>
                </div>

                <div class="col-lg-4 text-lg-end">
                    <a href="{{ route('buyer.deals') }}" class="buyer-btn-orange">
                        Xem bộ lọc deal
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="buyer-section">
        <div class="row g-4 row-cols-1 row-cols-md-3">
            <div class="col">
                <a href="{{ route('buyer.deals', ['type' => 'fundraising']) }}"
                   class="buyer-card buyer-filter-card">
                    <div class="buyer-filter-code">GV</div>

                    <h4 class="buyer-filter-title">
                        Góp vốn
                    </h4>

                    <p class="buyer-filter-text">
                        Các doanh nghiệp đang gọi vốn để mở rộng sản xuất, phát triển thị trường hoặc tăng trưởng mô hình kinh doanh.
                    </p>
                </a>
            </div>

            <div class="col">
                <a href="{{ route('buyer.deals', ['type' => 'share_sale']) }}"
                   class="buyer-card buyer-filter-card">
                    <div class="buyer-filter-code">CP</div>

                    <h4 class="buyer-filter-title">
                        Mua cổ phần
                    </h4>

                    <p class="buyer-filter-text">
                        Các deal bán một phần cổ phần hoặc phần vốn góp cho nhà đầu tư chiến lược hoặc nhà đầu tư tài chính.
                    </p>
                </a>
            </div>

            <div class="col">
                <a href="{{ route('buyer.deals', ['type' => 'acquisition']) }}"
                   class="buyer-card buyer-filter-card">
                    <div class="buyer-filter-code">CT</div>

                    <h4 class="buyer-filter-title">
                        Mua công ty
                    </h4>

                    <p class="buyer-filter-text">
                        Các deal chuyển nhượng toàn bộ công ty hoặc quyền kiểm soát doanh nghiệp cho buyer phù hợp.
                    </p>
                </a>
            </div>
        </div>
    </div>

    <div class="buyer-section">
        <div class="buyer-card buyer-panel">
            <div class="buyer-panel-header d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3">
                <div>
                    <h3 class="buyer-panel-title">
                        Toàn bộ deal
                    </h3>

                    <p class="buyer-panel-text">
                        Hiển thị {{ $deals->count() }} deal hiện có trong hệ thống.
                    </p>
                </div>

                <a href="{{ route('buyer.deals') }}" class="buyer-btn-soft">
                    Xem tất cả deal
                </a>
            </div>

            @if ($deals->count() > 0)
                <div class="row g-4 row-cols-1 row-cols-md-2 row-cols-lg-3">
                    @foreach ($deals as $deal)
                        @php
                            $dealTypeLabel = match ($deal->deal_type) {
                                'fundraising' => 'Góp vốn',
                                'share_sale' => 'Mua cổ phần',
                                'acquisition' => 'Mua công ty',
                                default => 'Khác',
                            };

                            $statusLabel = match ($deal->status) {
                                'draft' => 'Bản nháp',
                                'pending_review' => 'Chờ duyệt',
                                'approved' => 'Đã duyệt',
                                'rejected' => 'Từ chối',
                                'published' => 'Đang mở',
                                'negotiating' => 'Đang đàm phán',
                                'closed' => 'Đã đóng',
                                'cancelled' => 'Đã hủy',
                                default => $deal->status,
                            };

                            $statusClass = match ($deal->status) {
                                'published' => 'status-published',
                                'negotiating' => 'status-negotiating',
                                'closed' => 'status-closed',
                                'cancelled', 'rejected' => 'status-danger',
                                default => 'status-default',
                            };

                            $accessRequest = $accessRequestsByDealId->get($deal->id);
                            $modalId = 'ndaModal_' . $deal->id;
                        @endphp

                        <div class="col">
                            <div class="buyer-card buyer-deal-card">
                                <div class="d-flex justify-content-between align-items-start gap-2 mb-4 pt-2">
                                    <span class="buyer-badge-orange">
                                        {{ $dealTypeLabel }}
                                    </span>

                                    <span class="buyer-badge-status {{ $statusClass }}">
                                        {{ $statusLabel }}
                                    </span>
                                </div>

                                <h4 class="buyer-deal-title">
                                    {{ $deal->title }}
                                </h4>

                                <div class="mt-3">
                                    <p class="buyer-label">Công ty</p>
                                    <p class="buyer-value">
                                        {{ $deal->company->legal_name ?? 'Chưa có thông tin công ty' }}
                                    </p>
                                </div>

                                <div class="mt-3">
                                    <p class="buyer-label">Mô tả ngắn</p>
                                    <p class="buyer-description">
                                        {{ $deal->short_description ?? 'Chưa có mô tả ngắn cho deal này.' }}
                                    </p>
                                </div>

                                <div class="buyer-valuation-box mt-4">
                                    <p class="buyer-label mb-1">
                                        Định giá doanh nghiệp
                                    </p>

                                    <p class="buyer-valuation-value">
                                        @if ($deal->valuation)
                                            {{ number_format($deal->valuation, 0, ',', '.') }} {{ $deal->currency ?? 'VND' }}
                                        @else
                                            Chưa công bố
                                        @endif
                                    </p>
                                </div>

                                <div class="row g-3 mt-2">
                                    <div class="col-6">
                                        <p class="buyer-label">Ngành</p>
                                        <p class="buyer-value">
                                            {{ $deal->industry ?? 'Đang cập nhật' }}
                                        </p>
                                    </div>

                                    <div class="col-6">
                                        <p class="buyer-label">Tỷ lệ</p>
                                        <p class="buyer-value">
                                            @if ($deal->equity_offered_percent)
                                                {{ $deal->equity_offered_percent }}%
                                            @else
                                                Đang cập nhật
                                            @endif
                                        </p>
                                    </div>
                                </div>

                                <div class="deal-actions">
                                    <div class="deal-action-item">
                                        @if (!$accessRequest)
                                            <button type="button"
                                                    class="buyer-btn-orange deal-action-btn"
                                                    data-bs-toggle="modal"
                                                    data-bs-target="#{{ $modalId }}">
                                                Yêu cầu xem hồ sơ
                                            </button>
                                        @elseif ($accessRequest->status === 'pending')
                                            <button type="button"
                                                    class="buyer-btn-orange deal-action-btn"
                                                    disabled>
                                                Đang chờ duyệt
                                            </button>
                                        @elseif ($accessRequest->status === 'approved')
                                            <a href="{{ route('buyer.deals.show', $deal) }}"
                                            class="buyer-btn-orange deal-action-btn">
                                                Xem hồ sơ
                                            </a>
                                        @elseif ($accessRequest->status === 'rejected')
                                            <button type="button"
                                                    class="buyer-btn-orange deal-action-btn"
                                                    data-bs-toggle="modal"
                                                    data-bs-target="#{{ $modalId }}">
                                                Gửi lại yêu cầu
                                            </button>
                                        @endif
                                    </div>

                                    <div class="deal-action-item">
                                        @if ($interestedDealIds->contains($deal->id))
                                            <form method="POST" action="{{ route('buyer.deals.interest.destroy', $deal) }}">
                                                @csrf
                                                @method('DELETE')

                                                <button type="submit" class="buyer-btn-soft deal-action-btn">
                                                    Đã quan tâm
                                                </button>
                                            </form>
                                        @else
                                            <form method="POST" action="{{ route('buyer.deals.interest.store', $deal) }}">
                                                @csrf

                                                <button type="submit" class="buyer-btn-soft deal-action-btn">
                                                    Quan tâm
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="buyer-empty">
                    <h4 class="fw-bold text-dark mb-2">
                        Chưa có deal nào
                    </h4>

                    <p class="text-muted mb-0">
                        Hiện tại hệ thống chưa có deal được hiển thị.
                    </p>
                </div>
            @endif
        </div>
    </div>
</div>

@if ($deals->count() > 0)
    @foreach ($deals as $deal)
        @php
            $accessRequest = $accessRequestsByDealId->get($deal->id);
            $modalId = 'ndaModal_' . $deal->id;
            $agreeCheckboxId = 'agreeNda_' . $deal->id;
            $submitButtonId = 'submitNda_' . $deal->id;
        @endphp

        @if (!$accessRequest || $accessRequest->status === 'rejected')
            <div class="modal fade buyer-nda-modal" id="{{ $modalId }}" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
                    <div class="modal-content border-0 rounded-4 shadow-lg">
                        <div class="modal-header border-0 pb-0">
                            <div>
                                <h5 class="modal-title fw-bold text-dark">
                                    {{ $accessRequest && $accessRequest->status === 'rejected' ? 'Xác nhận lại điều khoản bảo mật' : 'Điều khoản bảo mật thông tin' }}
                                </h5>

                                <p class="text-muted small mb-0">
                                    Áp dụng cho deal: <strong>{{ $deal->title }}</strong>
                                </p>
                            </div>

                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>

                        <div class="modal-body">
                            <div class="rounded-4 p-4 mb-4"
                                 style="background: #fff7ed; border: 1px solid #fed7aa;">
                                @if ($accessRequest && $accessRequest->status === 'rejected')
                                    <p class="text-muted small mb-0" style="line-height: 1.8;">
                                        Yêu cầu trước đó của bạn đã bị seller từ chối. Nếu muốn gửi lại, bạn cần xác nhận rằng bạn vẫn đồng ý tuân thủ điều khoản bảo mật, không chia sẻ thông tin cho bên thứ ba và chỉ sử dụng thông tin để đánh giá cơ hội giao dịch.
                                    </p>
                                @else
                                    <h6 class="fw-bold mb-3 text-dark">
                                        Trước khi gửi yêu cầu xem hồ sơ, bạn cần đồng ý các điều khoản sau:
                                    </h6>

                                    <ol class="text-muted small mb-0" style="line-height: 1.8;">
                                        <li>
                                            Bạn chỉ được sử dụng thông tin của deal cho mục đích đánh giá cơ hội đầu tư, mua cổ phần, góp vốn hoặc mua doanh nghiệp.
                                        </li>
                                        <li>
                                            Bạn không được sao chép, chia sẻ, công bố hoặc chuyển tiếp thông tin bảo mật cho bất kỳ bên thứ ba nào nếu chưa có sự đồng ý của seller.
                                        </li>
                                        <li>
                                            Các tài liệu như báo cáo tài chính, hồ sơ pháp lý, hợp đồng, dữ liệu khách hàng, tài liệu nội bộ và Data Room được xem là thông tin bảo mật.
                                        </li>
                                        <li>
                                            Việc gửi yêu cầu không đồng nghĩa với việc bạn được xem hồ sơ ngay. Seller có quyền duyệt hoặc từ chối yêu cầu của bạn.
                                        </li>
                                        <li>
                                            Nếu vi phạm cam kết bảo mật, tài khoản của bạn có thể bị hạn chế quyền truy cập và chịu trách nhiệm theo quy định liên quan.
                                        </li>
                                    </ol>
                                @endif
                            </div>

                            <form method="POST" action="{{ route('buyer.deals.access_requests.store', $deal) }}">
                                @csrf

                                <div class="form-check mb-4">
                                    <input class="form-check-input nda-checkbox"
                                           type="checkbox"
                                           id="{{ $agreeCheckboxId }}"
                                           data-submit-button="{{ $submitButtonId }}">

                                    <label class="form-check-label small text-muted" for="{{ $agreeCheckboxId }}">
                                        Tôi đã đọc, hiểu và đồng ý với điều khoản bảo mật thông tin trước khi gửi yêu cầu xem hồ sơ.
                                    </label>
                                </div>

                                <div class="d-flex justify-content-end gap-2">
                                    <button type="button"
                                            class="btn btn-light rounded-3 px-4"
                                            data-bs-dismiss="modal">
                                        Hủy
                                    </button>

                                    <button type="submit"
                                            id="{{ $submitButtonId }}"
                                            class="btn btn-warning text-white rounded-3 px-4 fw-bold"
                                            disabled>
                                        {{ $accessRequest && $accessRequest->status === 'rejected' ? 'Gửi lại yêu cầu' : 'Đồng ý và gửi yêu cầu' }}
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        @endif
    @endforeach
@endif

<script>
document.addEventListener('DOMContentLoaded', function () {
    document.querySelectorAll('.nda-checkbox').forEach(function (checkbox) {
        checkbox.addEventListener('change', function () {
            const submitButtonId = this.dataset.submitButton;
            const submitButton = document.getElementById(submitButtonId);

            if (submitButton) {
                submitButton.disabled = !this.checked;
            }
        });
    });
});
</script>

@endsection