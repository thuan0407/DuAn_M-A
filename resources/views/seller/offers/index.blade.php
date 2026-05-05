@extends('layouts.seller')

@section('title', 'Quản lý offer')
@section('page_title', 'Quản lý offer')
@section('page_description', 'Danh sách offer buyer đã gửi cho các deal của bạn')

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
</style>

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

<div class="offer-card">
    <h4 class="fw-bold mb-4">
        Danh sách offer
    </h4>

    @if ($offers->count() > 0)
        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead class="table-light">
                    <tr>
                        <th>Buyer</th>
                        <th>Deal</th>
                        <th>Số tiền offer</th>
                        <th>Tỷ lệ cổ phần</th>
                        <th>Trạng thái</th>
                        <th>Ngày gửi</th>
                        <th class="text-end">Hành động</th>
                    </tr>
                </thead>

                <tbody>
                    @foreach ($offers as $offer)
                        <tr>
                            <td>
                                <strong>{{ $offer->buyer->name ?? 'Không rõ buyer' }}</strong>
                                <div class="text-muted small">
                                    {{ $offer->buyer->email ?? 'Chưa có email' }}
                                </div>
                            </td>

                            <td>
                                <strong>{{ $offer->deal->title ?? 'Deal không tồn tại' }}</strong>
                                <div class="text-muted small">
                                    {{ $offer->deal->company->legal_name ?? 'Chưa có công ty' }}
                                </div>
                            </td>

                            <td>
                                <strong>
                                    {{ number_format($offer->amount, 0, ',', '.') }}
                                    {{ $offer->currency ?? 'VND' }}
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
                                @if ($offer->status === 'pending')
                                    <span class="badge bg-warning text-dark">Đang chờ</span>
                                @elseif ($offer->status === 'accepted')
                                    <span class="badge bg-success">Đã chấp nhận</span>
                                @elseif ($offer->status === 'rejected')
                                    <span class="badge bg-danger">Đã từ chối</span>
                                @else
                                    <span class="badge bg-secondary">{{ $offer->status }}</span>
                                @endif
                            </td>

                            <td>
                                {{ $offer->created_at?->format('d/m/Y H:i') }}
                            </td>

                            <td class="text-end">
                                <a href="{{ route('seller.offers.show', $offer) }}" class="btn-orange">
                                    Xem chi tiết
                                </a>
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
        <div class="alert alert-info rounded-4 border-0 mb-0">
            Chưa có buyer nào gửi offer cho deal của bạn.
        </div>
    @endif
</div>

@endsection