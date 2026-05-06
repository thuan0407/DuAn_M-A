@extends('layouts.buyer')

@section('title', $currentTypeLabel ?? 'Danh sách deal')
@section('page_title', $currentTypeLabel ?? 'Danh sách deal')
@section('page_description', 'Danh sách các deal phù hợp với bộ lọc của bạn')

@section('content')

@php
    $deals = $deals ?? collect();
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

    .buyer-page-card {
        border: 1px solid var(--buyer-orange-border);
        border-radius: 24px;
        box-shadow: 0 10px 26px rgba(249, 115, 22, 0.07);
    }

    .buyer-deal-card {
        height: 100%;
        border: 1px solid var(--buyer-orange-border);
        border-radius: 24px;
        overflow: hidden;
        position: relative;
        box-shadow: 0 10px 26px rgba(249, 115, 22, 0.07);
        transition: all 0.22s ease;
    }

    .buyer-deal-card:hover {
        transform: translateY(-3px);
        border-color: var(--buyer-orange);
        box-shadow: 0 16px 36px rgba(249, 115, 22, 0.14);
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

    .buyer-btn-orange {
        background: var(--buyer-orange);
        border: 1px solid var(--buyer-orange);
        color: #ffffff;
        border-radius: 14px;
        padding: 10px 14px;
        font-size: 14px;
        font-weight: 800;
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
    }

    .buyer-btn-orange:disabled {
        opacity: 0.75;
        cursor: not-allowed;
    }

    .buyer-btn-soft {
        background: var(--buyer-orange-light);
        border: 1px solid var(--buyer-orange-border);
        color: var(--buyer-orange-dark);
        border-radius: 14px;
        padding: 10px 14px;
        font-size: 14px;
        font-weight: 800;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        transition: all 0.2s ease;
    }

    .buyer-btn-soft:hover {
        background: var(--buyer-orange-soft);
        color: var(--buyer-orange-dark);
    }

    .buyer-action-btn {
        width: 100%;
        min-height: 44px;
        text-align: center;
        white-space: nowrap;
    }

    .buyer-valuation-box {
        background: var(--buyer-orange-light);
        border: 1px solid var(--buyer-orange-border);
        border-radius: 16px;
        padding: 16px;
    }

    .buyer-empty-card {
        border: 1px dashed var(--buyer-orange-border);
        border-radius: 24px;
        background: var(--buyer-orange-light);
    }

    @media (max-width: 575.98px) {
        .buyer-action-group {
            flex-direction: column;
        }
    }
</style>

<div class="container-fluid px-0">
    <div class="card border-0 buyer-page-card mb-4">
        <div class="card-body p-4">
            <h4 class="fw-bold mb-2">
                {{ $currentTypeLabel ?? 'Tất cả deal' }}
            </h4>

            <p class="text-muted mb-0">
                Tìm thấy {{ $deals->count() }} deal phù hợp.
            </p>
        </div>
    </div>

    @if ($deals->count() > 0)
        <div class="row g-4 row-cols-1 row-cols-md-2 row-cols-lg-3">
            @foreach ($deals as $deal)
                @php
                    $dealTypeLabel = match ($deal->deal_type) {
                        'fundraising' => 'Góp vốn',
                        'share_sale' => 'Mua cổ phần',
                        'acquisition' => 'Mua công ty',
                        default => $deal->deal_type ?? 'Khác',
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
                        default => $deal->status ?? 'Đang cập nhật',
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
                    <div class="card buyer-deal-card">
                        <div class="card-body p-4 d-flex flex-column h-100">
                            <div class="d-flex justify-content-between align-items-start gap-2 mb-3 pt-2">
                                <span class="buyer-badge-orange">
                                    {{ $dealTypeLabel }}
                                </span>

                                <span class="buyer-badge-status {{ $statusClass }}">
                                    {{ $statusLabel }}
                                </span>
                            </div>

                            <h5 class="fw-bold mb-2">
                                {{ $deal->title }}
                            </h5>

                            <p class="text-muted mb-2">
                                {{ $deal->company->legal_name ?? 'Chưa có công ty' }}
                            </p>

                            <p class="text-secondary small mb-0" style="line-height: 1.65; min-height: 66px;">
                                {{ $deal->short_description ?? 'Chưa có mô tả ngắn.' }}
                            </p>

                            <div class="buyer-valuation-box mt-3">
                                <small class="text-muted">
                                    Định giá doanh nghiệp
                                </small>

                                <div class="fw-bold text-warning-emphasis">
                                    @if ($deal->valuation)
                                        {{ number_format($deal->valuation, 0, ',', '.') }} {{ $deal->currency ?? 'VND' }}
                                    @else
                                        Chưa công bố
                                    @endif
                                </div>
                            </div>

                            <div class="d-flex gap-2 mt-4 buyer-action-group">
                                @if(!$kyc)
                                    <a href="{{route('buyer.kyc.create')}}"
                                            class="buyer-btn-orange deal-action-btn"
                                            data-bs-target="#{{ $modalId }}"
                                            style="text-align: center;">
                                        Bạn cần xác minh tài khoản trước khi xem
                                    </a>
                                    @else

                                    <div class="flex-fill">
                                        @if (!$accessRequest)
                                            <button type="button"
                                                    class="buyer-btn-orange buyer-action-btn"
                                                    data-bs-toggle="modal"
                                                    data-bs-target="#{{ $modalId }}">
                                                Yêu cầu xem hồ sơ
                                            </button>
                                        @elseif ($accessRequest->status === 'pending')
                                            <button type="button"
                                                    class="buyer-btn-orange buyer-action-btn"
                                                    disabled>
                                                Đang chờ duyệt
                                            </button>
                                        @elseif ($accessRequest->status === 'approved')
                                            <a href="#"
                                            class="buyer-btn-orange buyer-action-btn">
                                                Xem hồ sơ
                                            </a>
                                        @elseif ($accessRequest->status === 'rejected')
                                            <button type="button"
                                                    class="buyer-btn-orange buyer-action-btn"
                                                    data-bs-toggle="modal"
                                                    data-bs-target="#{{ $modalId }}">
                                                Gửi lại yêu cầu
                                            </button>
                                        @else
                                            <button type="button"
                                                    class="buyer-btn-orange buyer-action-btn"
                                                    data-bs-toggle="modal"
                                                    data-bs-target="#{{ $modalId }}">
                                                Yêu cầu xem hồ sơ
                                            </button>
                                        @endif
                                    </div>

                                    <div class="flex-fill">
                                        @if ($interestedDealIds->contains($deal->id))
                                            <form method="POST" action="{{ route('buyer.deals.interest.destroy', $deal) }}">
                                                @csrf
                                                @method('DELETE')

                                                <button type="submit" class="buyer-btn-soft buyer-action-btn">
                                                    Đã quan tâm
                                                </button>
                                            </form>
                                        @else
                                            <form method="POST" action="{{ route('buyer.deals.interest.store', $deal) }}">
                                                @csrf

                                                <button type="submit" class="buyer-btn-soft buyer-action-btn">
                                                    Quan tâm
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @else
        <div class="card border-0 shadow-sm buyer-empty-card">
            <div class="card-body p-5 text-center">
                <h5 class="fw-bold">
                    Không tìm thấy deal phù hợp
                </h5>

                <p class="text-muted mb-0">
                    Thử đổi loại deal hoặc nhập từ khóa khác.
                </p>
            </div>
        </div>
    @endif
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