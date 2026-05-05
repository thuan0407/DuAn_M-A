@extends('layouts.buyer')

@section('title', 'Deal quan tâm')
@section('page_title', 'Deal quan tâm')
@section('page_description', 'Danh sách các công ty và deal bạn đã đánh dấu quan tâm')

@section('content')

@php
    $interests = $interests ?? collect();
    $accessRequestsByDealId = $accessRequestsByDealId ?? collect();
@endphp

<style>
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

    .interest-panel {
        background: #ffffff;
        border: 1px solid #fed7aa;
        border-radius: 24px;
        padding: 28px;
        box-shadow: 0 10px 26px rgba(249, 115, 22, 0.07);
    }

    .interest-card {
        background: #ffffff;
        border: 1px solid #fed7aa;
        border-radius: 24px;
        padding: 22px;
        height: 100%;
        box-shadow: 0 10px 26px rgba(249, 115, 22, 0.07);
        transition: all 0.22s ease;
        position: relative;
        overflow: hidden;
        display: flex;
        flex-direction: column;
    }

    .interest-card::before {
        content: "";
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 5px;
        background: linear-gradient(90deg, #f97316, #fb923c);
    }

    .interest-card:hover {
        transform: translateY(-3px);
        border-color: #f97316;
        box-shadow: 0 16px 36px rgba(249, 115, 22, 0.14);
    }

    .interest-badge {
        display: inline-flex;
        padding: 6px 11px;
        border-radius: 999px;
        background: #fff7ed;
        color: #ea580c;
        border: 1px solid #fed7aa;
        font-size: 12px;
        font-weight: 800;
        white-space: nowrap;
    }

    .interest-badge-success {
        background: #dcfce7;
        color: #15803d;
        border-color: #bbf7d0;
    }

    .interest-badge-warning {
        background: #fef3c7;
        color: #b45309;
        border-color: #fde68a;
    }

    .interest-badge-danger {
        background: #fee2e2;
        color: #b91c1c;
        border-color: #fecaca;
    }

    .interest-title {
        font-size: 18px;
        font-weight: 900;
        color: #1f2937;
        margin-bottom: 8px;
    }

    .interest-company {
        color: #ea580c;
        font-weight: 800;
        margin-bottom: 8px;
    }

    .interest-desc {
        color: #6b7280;
        font-size: 14px;
        line-height: 1.65;
        min-height: 68px;
        margin-bottom: 0;
    }

    .interest-valuation {
        background: #fff7ed;
        border: 1px solid #fed7aa;
        border-radius: 18px;
        padding: 16px;
        margin-top: 16px;
    }

    .interest-label {
        font-size: 12px;
        color: #9ca3af;
        margin-bottom: 4px;
    }

    .interest-value {
        color: #ea580c;
        font-size: 18px;
        font-weight: 900;
        margin-bottom: 0;
    }

    .interest-actions {
        display: flex;
        gap: 10px;
        margin-top: auto;
        padding-top: 24px;
    }

    .interest-action-item {
        flex: 1 1 0;
        min-width: 0;
    }

    .interest-action-item form {
        width: 100%;
    }

    .btn-orange-custom {
        background: #f97316;
        border: 1px solid #f97316;
        color: #ffffff;
        border-radius: 14px;
        padding: 10px 16px;
        font-size: 14px;
        font-weight: 800;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        justify-content: center;
    }

    .btn-orange-custom:hover {
        background: #ea580c;
        border-color: #ea580c;
        color: #ffffff;
    }

    .btn-orange-custom:disabled {
        opacity: 0.75;
        cursor: not-allowed;
    }

    .btn-soft-orange {
        background: #fff7ed;
        border: 1px solid #fed7aa;
        color: #ea580c;
        border-radius: 14px;
        padding: 10px 16px;
        font-size: 14px;
        font-weight: 800;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        justify-content: center;
    }

    .btn-soft-orange:hover {
        background: #ffedd5;
        color: #ea580c;
    }

    .interest-action-btn {
        width: 100%;
        min-height: 44px;
        text-align: center;
        white-space: nowrap;
    }

    .empty-box {
        background: #fff7ed;
        border: 1px dashed #fed7aa;
        border-radius: 24px;
        padding: 44px;
        text-align: center;
    }

    @media (max-width: 575.98px) {
        .interest-panel {
            padding: 22px;
        }

        .interest-actions {
            flex-direction: column;
        }
    }
</style>

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

<div class="interest-panel">
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4">
        <div>
            <h3 class="fw-bold text-dark mb-1">
                Các deal bạn đang quan tâm
            </h3>

            <p class="text-muted mb-0">
                Hiển thị {{ $interests->count() }} deal bạn đã đánh dấu quan tâm.
            </p>
        </div>

        <a href="{{ route('buyer.deals') }}" class="btn-orange-custom">
            Tìm thêm deal
        </a>
    </div>

    @if ($interests->count() > 0)
        <div class="row g-4 row-cols-1 row-cols-md-2 row-cols-lg-3">
            @foreach ($interests as $interest)
                @php
                    $deal = $interest->deal;
                @endphp

                @if ($deal)
                    @php
                        $dealTypeLabel = match ($deal->deal_type ?? null) {
                            'fundraising' => 'Góp vốn',
                            'share_sale' => 'Mua cổ phần',
                            'acquisition' => 'Mua công ty',
                            default => 'Khác',
                        };

                        $accessRequest = $accessRequestsByDealId->get($deal->id);
                        $modalId = 'ndaModal_' . $deal->id;

                        $accessStatusLabel = match ($accessRequest->status ?? null) {
                            'pending' => 'Chờ duyệt hồ sơ',
                            'approved' => 'Đã được duyệt',
                            'rejected' => 'Bị từ chối',
                            default => 'Chưa yêu cầu hồ sơ',
                        };

                        $accessStatusClass = match ($accessRequest->status ?? null) {
                            'pending' => 'interest-badge-warning',
                            'approved' => 'interest-badge-success',
                            'rejected' => 'interest-badge-danger',
                            default => '',
                        };
                    @endphp

                    <div class="col">
                        <div class="interest-card">
                            <div class="d-flex justify-content-between align-items-start gap-2 mb-4 pt-2">
                                <span class="interest-badge">
                                    {{ $dealTypeLabel }}
                                </span>

                                <span class="interest-badge">
                                    Đã quan tâm
                                </span>
                            </div>

                            <h4 class="interest-title">
                                {{ $deal->title }}
                            </h4>

                            <p class="interest-company">
                                {{ $deal->company->legal_name ?? 'Chưa có thông tin công ty' }}
                            </p>

                            <p class="interest-desc">
                                {{ $deal->short_description ?? 'Chưa có mô tả ngắn cho deal này.' }}
                            </p>

                            <div class="interest-valuation">
                                <p class="interest-label">
                                    Định giá doanh nghiệp
                                </p>

                                <p class="interest-value">
                                    @if ($deal->valuation)
                                        {{ number_format($deal->valuation, 0, ',', '.') }} {{ $deal->currency ?? 'VND' }}
                                    @else
                                        Chưa công bố
                                    @endif
                                </p>
                            </div>

                            <div class="mt-3">
                                <span class="interest-badge {{ $accessStatusClass }}">
                                    {{ $accessStatusLabel }}
                                </span>
                            </div>

                            <div class="interest-actions">
<div class="interest-action-item">
    @if (!$accessRequest)
        <button type="button"
                class="btn-orange-custom interest-action-btn"
                data-bs-toggle="modal"
                data-bs-target="#{{ $modalId }}">
            Yêu cầu xem hồ sơ
        </button>

    @elseif ($accessRequest->status === 'pending')
        <button type="button"
                class="btn-orange-custom interest-action-btn"
                disabled>
            Đang chờ duyệt
        </button>

    @elseif ($accessRequest->status === 'approved')
        <a href="{{ route('buyer.deals.show', $deal) }}"
           class="btn-orange-custom interest-action-btn">
            Xem hồ sơ
        </a>

    @elseif ($accessRequest->status === 'rejected')
        <button type="button"
                class="btn-orange-custom interest-action-btn"
                data-bs-toggle="modal"
                data-bs-target="#{{ $modalId }}">
            Gửi lại yêu cầu
        </button>

    @else
        <button type="button"
                class="btn-orange-custom interest-action-btn"
                data-bs-toggle="modal"
                data-bs-target="#{{ $modalId }}">
            Yêu cầu xem hồ sơ
        </button>
    @endif
</div>

                                <div class="interest-action-item">
                                    <form method="POST" action="{{ route('buyer.deals.interest.destroy', $deal) }}">
                                        @csrf
                                        @method('DELETE')

                                        <button type="submit" class="btn-soft-orange interest-action-btn">
                                            Bỏ quan tâm
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
            @endforeach
        </div>
    @else
        <div class="empty-box">
            <h4 class="fw-bold text-dark mb-2">
                Bạn chưa quan tâm deal nào
            </h4>

            <p class="text-muted mb-4">
                Khi bạn bấm nút Quan tâm ở một deal, deal đó sẽ xuất hiện tại đây.
            </p>

            <a href="{{ route('buyer.deals') }}" class="btn-orange-custom">
                Khám phá deal
            </a>
        </div>
    @endif
</div>

@if ($interests->count() > 0)
    @foreach ($interests as $interest)
        @php
            $deal = $interest->deal;
        @endphp

        @if ($deal)
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

    document.querySelectorAll('.buyer-nda-modal').forEach(function (modal) {
        modal.addEventListener('hidden.bs.modal', function () {
            const checkbox = modal.querySelector('.nda-checkbox');

            if (!checkbox) {
                return;
            }

            checkbox.checked = false;

            const submitButtonId = checkbox.dataset.submitButton;
            const submitButton = document.getElementById(submitButtonId);

            if (submitButton) {
                submitButton.disabled = true;
            }
        });
    });
});
</script>

@endsection