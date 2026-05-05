@extends('layouts.seller')

@section('title', 'Quản lý deal')
@section('page_title', 'Quản lý deal')
@section('page_description', 'Tạo và quản lý các deal bán công ty, bán cổ phần hoặc gọi vốn')

@section('content')

<div class="card border-0 shadow-sm rounded-4">
    <div class="card-body p-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h4 class="fw-bold mb-1">Danh sách deal</h4>
                <p class="text-muted mb-0">
                    Tổng cộng {{ $deals->count() }} deal.
                </p>
            </div>

            @if ($company && $company->verification_status === 'approved')
                <a href="{{ route('seller.deals.create') }}" class="btn btn-warning text-white fw-bold rounded-3">
                    Tạo deal mới
                </a>
            @endif
        </div>

        @if (!$company)
            <div class="alert alert-warning rounded-4 border-0 mb-0">
                Bạn cần tạo hồ sơ doanh nghiệp trước khi tạo deal.
            </div>
        @elseif ($company->verification_status !== 'verified')
            <div class="alert alert-warning rounded-4 border-0 mb-0">
                Hồ sơ doanh nghiệp cần được admin duyệt trước khi tạo deal.
            </div>
        @elseif ($deals->count() === 0)
            <div class="alert alert-info rounded-4 border-0 mb-0">
                Bạn chưa có deal nào.
            </div>
        @else
            <div class="table-responsive">
                <table class="table align-middle">
                    <thead>
                    <tr>
                        <th>Tên deal</th>
                        <th>Loại</th>
                        <th>Trạng thái</th>
                        <th>Định giá</th>
                        <th class="text-end">Thao tác</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach ($deals as $deal)
                        <tr>
                            <td class="fw-bold">{{ $deal->title }}</td>
                            <td>{{ $deal->deal_type }}</td>
                            <td>{{ $deal->status }}</td>
                            <td>
                                @if ($deal->valuation)
                                    {{ number_format($deal->valuation, 0, ',', '.') }} {{ $deal->currency ?? 'VND' }}
                                @else
                                    Chưa công bố
                                @endif
                            </td>
                            <td class="text-end">
                                <a href="{{ route('seller.deals.edit', $deal) }}" class="btn btn-sm btn-outline-warning rounded-3">
                                    Sửa
                                </a>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>
</div>

@endsection