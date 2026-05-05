@extends('layouts.buyer')

@section('title', 'Deal thành công')
@section('page_title', 'Deal thành công')
@section('page_description', 'Danh sách các deal mà offer của bạn đã được seller chấp nhận')

@section('content')

<style>
    .success-card {
        background: #ffffff;
        border: 1px solid #fed7aa;
        border-radius: 24px;
        box-shadow: 0 10px 26px rgba(249, 115, 22, 0.07);
        padding: 28px;
        margin-bottom: 24px;
    }

    .success-soft-box {
        background: #fff7ed;
        border: 1px solid #fed7aa;
        border-radius: 18px;
        padding: 18px;
        height: 100%;
    }

    .success-label {
        font-size: 13px;
        color: #9ca3af;
        margin-bottom: 4px;
    }

    .success-value {
        font-weight: 800;
        color: #1f2937;
        margin-bottom: 0;
        word-break: break-word;
    }

    .success-table th {
        color: #6b7280;
        font-size: 13px;
        font-weight: 800;
        white-space: nowrap;
    }

    .success-table td {
        vertical-align: middle;
    }

    .btn-orange {
        background: #f97316;
        border: 1px solid #f97316;
        color: #ffffff;
        border-radius: 14px;
        padding: 9px 14px;
        font-weight: 800;
        text-decoration: none;
        display: inline-flex;
        justify-content: center;
        align-items: center;
    }

    .btn-orange:hover {
        background: #ea580c;
        border-color: #ea580c;
        color: #ffffff;
    }

    .badge-orange {
        background: #fff7ed;
        color: #ea580c;
        border: 1px solid #fed7aa;
        border-radius: 999px;
        padding: 6px 10px;
        font-size: 12px;
        font-weight: 800;
    }
</style>

<div class="success-card">
    <div class="d-flex flex-column flex-lg-row justify-content-between align-items-lg-center gap-3 mb-4">
        <div>
            <h4 class="fw-bold mb-1">
                Deal thành công
            </h4>

            <p class="text-muted mb-0">
                Đây là các deal mà seller đã chấp nhận offer của bạn.
            </p>
        </div>

        <span class="badge-orange">
            Tổng: {{ $offers->total() }} deal
        </span>
    </div>

    @if ($offers->count() > 0)
        <div class="table-responsive">
            <table class="table table-hover align-middle success-table">
                <thead class="table-light">
                    <tr>
                        <th>Tên deal</th>
                        <th>Công ty</th>
                        <th>Loại deal</th>
                        <th>Giá tiền bạn offer</th>
                        <th>Tỷ lệ cổ phần</th>
                        <th>Seller</th>
                        <th>Ngày được chấp nhận</th>
                        <th class="text-end">Chi tiết</th>
                    </tr>
                </thead>

                <tbody>
                    @foreach ($offers as $offer)
                        @php
                            $deal = $offer->deal;

                            $dealTypeLabel = match ($deal?->deal_type) {
                                'acquisition' => 'Mua công ty',
                                'share_sale' => 'Mua cổ phần',
                                'fundraising' => 'Góp vốn',
                                default => 'Khác',
                            };
                        @endphp

                        <tr>
                            <td>
                                <strong>
                                    {{ $deal->title ?? 'Deal không tồn tại' }}
                                </strong>

                                @if ($deal?->status)
                                    <div class="text-muted small mt-1">
                                        Trạng thái deal: {{ $deal->status }}
                                    </div>
                                @endif
                            </td>

                            <td>
                                {{ $deal->company->legal_name ?? 'Chưa có thông tin công ty' }}
                            </td>

                            <td>
                                <span class="badge-orange">
                                    {{ $dealTypeLabel }}
                                </span>
                            </td>

                            <td>
                                <strong class="text-dark">
                                    {{ number_format($offer->amount ?? 0, 0, ',', '.') }}
                                    {{ $offer->currency ?? $deal?->currency ?? 'VND' }}
                                </strong>
                            </td>

                            <td>
                                @if (!is_null($offer->equity_percent))
                                    {{ $offer->equity_percent }}%
                                @else
                                    Không áp dụng
                                @endif
                            </td>

                            <td>
                                <div>
                                    <strong>{{ $offer->seller->name ?? 'Không rõ seller' }}</strong>
                                </div>

                                <div class="text-muted small">
                                    {{ $offer->seller->email ?? 'Chưa có email' }}
                                </div>
                            </td>

                            <td>
                                {{ $offer->updated_at?->format('d/m/Y H:i') }}
                            </td>

<td class="text-end">
    @if ($deal)
        <a href="{{ route('buyer.successful_deals.show', $offer) }}" class="btn-orange">
            Xem chi tiết
        </a>
    @else
        <span class="text-muted small">
            Không khả dụng
        </span>
    @endif
</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="mt-4">
            {{ $offers->links() }}
        </div>
    @else
        <div class="success-soft-box text-center">
            <h5 class="fw-bold mb-2">
                Chưa có deal thành công
            </h5>

            <p class="text-muted mb-0">
                Khi seller chấp nhận offer của bạn, deal đó sẽ được hiển thị tại đây.
            </p>
        </div>
    @endif
</div>

@endsection