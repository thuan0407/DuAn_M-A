@extends('layouts.admin')

@section('title', 'Duyệt hồ sơ người dùng')
@section('page_title', 'Duyệt hồ sơ người dùng')

@section('content')

@if(session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
@endif

@if(session('error'))
    <div class="alert alert-danger">
        {{ session('error') }}
    </div>
@endif

<div class="card shadow-sm rounded-4">
    <div class="card-header bg-white d-flex justify-content-between align-items-center">
        <h5 class="mb-0">Hồ sơ KYC đang chờ duyệt</h5>
        <span class="badge bg-warning text-dark">
            {{ $kycs->count() }} hồ sơ
        </span>
    </div>

    <div class="card-body">
        @if($kycs->isEmpty())
            <div class="alert alert-info mb-0">
                Hiện chưa có hồ sơ người dùng nào cần duyệt.
            </div>
        @else
            <div class="table-responsive">
                <table class="table align-middle">
                    <thead>
                        <tr>
                            <th>Người dùng</th>
                            <th>Email</th>
                            <th>Loại giấy tờ</th>
                            <th>Số giấy tờ</th>
                            <th>Số điện thoại</th>
                            <th>Ngày gửi</th>
                            <th class="text-end">Thao tác</th>
                        </tr>
                    </thead>

                    <tbody>
                        @foreach($kycs as $kyc)
                            <tr>
                                <td>{{ $kyc->user->name ?? 'Không rõ' }}</td>
                                <td>{{ $kyc->user->email ?? 'Không rõ' }}</td>
                                <td>{{ strtoupper($kyc->document_type) }}</td>
                                <td>{{ $kyc->document_number }}</td>
                                <td>{{ $kyc->phone ?? 'Chưa cập nhật' }}</td>
                                <td>{{ $kyc->created_at->format('d/m/Y H:i') }}</td>
                                <td class="text-end">
                                    <a href="{{ route('admin.kyc.show', $kyc) }}" class="btn btn-sm btn-primary">
                                        Xem chi tiết
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