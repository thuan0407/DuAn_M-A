@extends('layouts.seller')

@section('title', 'Chi tiết offer')
@section('page_title', 'Chi tiết offer')
@section('page_description', 'Thông tin deal, buyer và nội dung offer đã gửi')

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
        height: 100%;
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

.btn-green {
    background: #16a34a;
    border: 1px solid #16a34a;
    color: #ffffff;
    border-radius: 14px;
    padding: 11px 18px;
    font-weight: 800;
    text-decoration: none;
    display: inline-flex;
    justify-content: center;
    align-items: center;
}

.btn-green:hover {
    background: #15803d;
    border-color: #15803d;
    color: #ffffff;
}

.btn-red {
    background: #dc2626;
    border: 1px solid #dc2626;
    color: #ffffff;
    border-radius: 14px;
    padding: 11px 18px;
    font-weight: 800;
    text-decoration: none;
    display: inline-flex;
    justify-content: center;
    align-items: center;
}

.btn-red:hover {
    background: #b91c1c;
    border-color: #b91c1c;
    color: #ffffff;
}
</style>

<div class="mb-4">
    <a href="{{ route('seller.offers.index') }}" class="btn-soft-orange">
        Quay lại danh sách offer
    </a>
</div>

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

{{-- ACTION --}}
@if ($offer->status === 'pending')
<div class="offer-card">
    <h4 class="fw-bold mb-3">Xử lý offer</h4>

    <div class="d-flex gap-2">
        <form method="POST" action="{{ route('seller.offers.accept', $offer) }}">
            @csrf
            <button class="btn-green">Đồng ý</button>
        </form>

        <form method="POST" action="{{ route('seller.offers.reject', $offer) }}">
            @csrf
            <button class="btn-red">Từ chối</button>
        </form>
    </div>
</div>
@endif

{{-- OFFER INFO --}}
<div class="offer-card">
    <h4 class="fw-bold mb-4">Thông tin offer</h4>

    <div class="row g-4">

        <div class="col-md-4">
            <div class="offer-soft-box">
                <p class="offer-label">Số tiền</p>
                <p class="offer-value">
                    {{ number_format($offer->amount) }} {{ $offer->currency }}
                </p>
            </div>
        </div>

        <div class="col-md-4">
            <div class="offer-soft-box">
                <p class="offer-label">Tỷ lệ cổ phần</p>
                <p class="offer-value">
                    {{ $offer->equity_percent ? $offer->equity_percent.'%' : 'Không có' }}
                </p>
            </div>
        </div>

        <div class="col-md-4">
            <div class="offer-soft-box">
                <p class="offer-label">Trạng thái</p>
                <p class="offer-value">
                    {{ $offer->status }}
                </p>
            </div>
        </div>

        <div class="col-12">
            <div class="offer-soft-box">
                <p class="offer-label">Ghi chú</p>
                <p class="offer-value">
                    {{ $offer->note ?? 'Không có' }}
                </p>
            </div>
        </div>

    </div>
</div>

{{-- DEAL INFO --}}
<div class="offer-card">
    <h4 class="fw-bold mb-4">Thông tin deal</h4>

    <div class="row g-4">

        <div class="col-md-6">
            <div class="offer-soft-box">
                <p class="offer-label">Tên deal</p>
                <p class="offer-value">{{ $offer->deal->title }}</p>
            </div>
        </div>

        <div class="col-md-6">
            <div class="offer-soft-box">
                <p class="offer-label">Công ty</p>
                <p class="offer-value">
                    {{ $offer->deal->company->legal_name ?? 'N/A' }}
                </p>
            </div>
        </div>

        <div class="col-md-4">
            <div class="offer-soft-box">
                <p class="offer-label">Loại deal</p>
                <p class="offer-value">{{ $offer->deal->deal_type }}</p>
            </div>
        </div>

        <div class="col-md-4">
            <div class="offer-soft-box">
                <p class="offer-label">Trạng thái</p>
                <p class="offer-value">{{ $offer->deal->status }}</p>
            </div>
        </div>

    </div>
</div>

{{-- BUYER INFO --}}
<div class="offer-card">
    <h4 class="fw-bold mb-4">Thông tin buyer</h4>

    <div class="row g-4">

        <div class="col-md-4">
            <div class="offer-soft-box">
                <p class="offer-label">Tên</p>
                <p class="offer-value">{{ $offer->buyer->name }}</p>
            </div>
        </div>

        <div class="col-md-4">
            <div class="offer-soft-box">
                <p class="offer-label">Email</p>
                <p class="offer-value">{{ $offer->buyer->email }}</p>
            </div>
        </div>

        <div class="col-md-4">
            <div class="offer-soft-box">
                <p class="offer-label">SĐT</p>
                <p class="offer-value">{{ $offer->buyer->phone ?? 'N/A' }}</p>
            </div>
        </div>

    </div>
</div>


@endsection