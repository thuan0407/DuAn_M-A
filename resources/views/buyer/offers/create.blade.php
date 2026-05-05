@extends('layouts.buyer')

@section('title', 'Gửi offer')
@section('page_title', 'Gửi offer')
@section('page_description', 'Gửi đề nghị giao dịch cho seller')

@section('content')

<style>
    .offer-card {
        background: #ffffff;
        border: 1px solid #fed7aa;
        border-radius: 24px;
        box-shadow: 0 10px 26px rgba(249, 115, 22, 0.07);
        padding: 28px;
        margin-bottom: 24px;
    }

    .offer-soft-box {
        background: #fff7ed;
        border: 1px solid #fed7aa;
        border-radius: 18px;
        padding: 18px;
    }

    .offer-label {
        font-size: 13px;
        color: #9ca3af;
        margin-bottom: 4px;
    }

    .offer-value {
        font-weight: 800;
        color: #1f2937;
        margin-bottom: 0;
    }

    .btn-orange {
        background: #f97316;
        border: 1px solid #f97316;
        color: #ffffff;
        border-radius: 14px;
        padding: 11px 18px;
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

    .btn-soft-orange {
        background: #fff7ed;
        border: 1px solid #fed7aa;
        color: #ea580c;
        border-radius: 14px;
        padding: 11px 18px;
        font-weight: 800;
        text-decoration: none;
        display: inline-flex;
        justify-content: center;
        align-items: center;
    }

    .btn-soft-orange:hover {
        background: #ffedd5;
        color: #ea580c;
    }
</style>

@php
    $dealTypeLabel = match ($deal->deal_type) {
        'acquisition' => 'Mua toàn bộ công ty',
        'share_sale' => 'Mua cổ phần',
        'fundraising' => 'Góp vốn',
        default => 'Khác',
    };

    $amountLabel = match ($deal->deal_type) {
        'acquisition' => 'Số tiền đề nghị mua toàn bộ công ty',
        'share_sale' => 'Số tiền đề nghị mua cổ phần',
        'fundraising' => 'Số tiền góp vốn đề nghị',
        default => 'Số tiền offer',
    };

    $equityLabel = match ($deal->deal_type) {
        'share_sale' => 'Tỷ lệ cổ phần muốn mua (%)',
        'fundraising' => 'Tỷ lệ cổ phần mong muốn nhận (%)',
        default => null,
    };
@endphp

@if (session('success'))
    <div class="alert alert-success rounded-4 border-0 shadow-sm mb-4">
        {{ session('success') }}
    </div>
@endif

@if (session('error'))
    <div class="alert alert-danger rounded-4 border-0 shadow-sm mb-4">
        {{ session('error') }}
    </div>
@endif

<div class="mb-4">
    <a href="{{ route('buyer.deals.show', $deal) }}" class="btn-soft-orange">
        Quay lại hồ sơ deal
    </a>
</div>

<div class="offer-card">
    <div class="mb-4">
        <h3 class="fw-bold mb-2">
            Gửi offer cho seller
        </h3>

        <p class="text-muted mb-0">
            Form này sẽ thay đổi theo loại deal. Mua toàn bộ công ty thì không cần nhập tỷ lệ cổ phần.
        </p>
    </div>

    <div class="offer-soft-box mb-4">
        <div class="row g-4">
            <div class="col-md-6">
                <p class="offer-label">Tên deal</p>
                <p class="offer-value">{{ $deal->title }}</p>
            </div>

            <div class="col-md-6">
                <p class="offer-label">Công ty</p>
                <p class="offer-value">
                    {{ $deal->company->legal_name ?? 'Chưa có thông tin công ty' }}
                </p>
            </div>

            <div class="col-md-4">
                <p class="offer-label">Loại deal</p>
                <p class="offer-value">{{ $dealTypeLabel }}</p>
            </div>

            <div class="col-md-4">
                <p class="offer-label">Số tiền tối thiểu</p>
                <p class="offer-value">
                    @if ($deal->min_ticket)
                        {{ number_format($deal->min_ticket, 0, ',', '.') }} {{ $deal->currency ?? 'VND' }}
                    @else
                        Không yêu cầu
                    @endif
                </p>
            </div>

            <div class="col-md-4">
                <p class="offer-label">Tỷ lệ cổ phần seller chào bán</p>
                <p class="offer-value">
                    @if ($deal->equity_offered_percent)
                        {{ $deal->equity_offered_percent }}%
                    @else
                        Không áp dụng
                    @endif
                </p>
            </div>
        </div>
    </div>

    <form action="{{ route('buyer.deals.offers.store', $deal) }}" method="POST">
        @csrf

        <div class="mb-3">
            <label class="form-label fw-bold">
                {{ $amountLabel }}
            </label>

            <input type="number"
                   name="amount"
                   class="form-control @error('amount') is-invalid @enderror"
                   value="{{ old('amount') }}"
                   min="{{ $deal->min_ticket ? (int) $deal->min_ticket : 1 }}"
                   placeholder="Ví dụ: 500000000"
                   required>

            @if ($deal->min_ticket)
                <div class="text-muted small mt-2">
                    Số tiền offer tối thiểu:
                    {{ number_format($deal->min_ticket, 0, ',', '.') }} {{ $deal->currency ?? 'VND' }}
                </div>
            @endif

            @error('amount')
                <div class="invalid-feedback">
                    {{ $message }}
                </div>
            @enderror
        </div>

        @if (in_array($deal->deal_type, ['share_sale', 'fundraising']))
            <div class="mb-3">
                <label class="form-label fw-bold">
                    {{ $equityLabel }}
                </label>

                <input type="number"
                       name="equity_percent"
                       class="form-control @error('equity_percent') is-invalid @enderror"
                       value="{{ old('equity_percent') }}"
                       step="0.01"
                       min="0.01"
                       max="100"
                       placeholder="Ví dụ: 5"
                       required>

                <div class="text-muted small mt-2">
                    Nhập tỷ lệ cổ phần buyer mong muốn mua hoặc nhận trong giao dịch này.
                </div>

                @error('equity_percent')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                @enderror
            </div>
        @else
            <div class="alert alert-info rounded-4 border-0">
                Deal này là mua toàn bộ công ty, nên bạn chỉ cần nhập số tiền đề nghị. Không cần nhập tỷ lệ cổ phần.
            </div>
        @endif

        <div class="mb-3">
            <label class="form-label fw-bold">
                Loại tiền
            </label>

            <input type="text"
                   class="form-control"
                   value="{{ $deal->currency ?? 'VND' }}"
                   readonly>
        </div>

        <div class="mb-4">
            <label class="form-label fw-bold">
                Ghi chú gửi seller
            </label>

            <textarea name="note"
                      class="form-control @error('note') is-invalid @enderror"
                      rows="5"
                      placeholder="Ví dụ: Tôi quan tâm đến deal này và muốn trao đổi thêm về báo cáo tài chính, pháp lý và lộ trình giao dịch.">{{ old('note') }}</textarea>

            @error('note')
                <div class="invalid-feedback">
                    {{ $message }}
                </div>
            @enderror
        </div>

        <div class="d-flex justify-content-end gap-2">
            <a href="{{ route('buyer.deals.show', $deal) }}" class="btn-soft-orange">
                Hủy
            </a>

            <button type="submit" class="btn-orange">
                Gửi offer
            </button>
        </div>
    </form>
</div>

@endsection