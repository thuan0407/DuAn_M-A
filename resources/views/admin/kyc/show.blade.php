@extends('layouts.admin')

@section('title', 'Chi tiết hồ sơ KYC')
@section('page_title', 'Chi tiết hồ sơ KYC')

@section('content')

<style>
    .kyc-card {
        border-radius: 18px;
        border: 1px solid #fed7aa;
        box-shadow: 0 10px 25px rgba(249, 115, 22, 0.08);
    }

    .kyc-header {
        background: #fff7ed;
        border-bottom: 1px solid #fed7aa;
        border-radius: 18px 18px 0 0;
    }

    .kyc-badge {
        padding: 6px 12px;
        border-radius: 999px;
        font-weight: 600;
        font-size: 13px;
    }

    .kyc-badge.pending {
        background: #ffedd5;
        color: #ea580c;
    }

    .kyc-badge.approved {
        background: #dcfce7;
        color: #16a34a;
    }

    .kyc-badge.rejected {
        background: #fee2e2;
        color: #dc2626;
    }

    .kyc-btn-orange {
        background: #f97316;
        border: none;
        color: #fff;
        font-weight: 600;
    }

    .kyc-btn-orange:hover {
        background: #ea580c;
    }

    .kyc-btn-outline-orange {
        border: 1px solid #f97316;
        color: #f97316;
        background: transparent;
    }

    .kyc-btn-outline-orange:hover {
        background: #fff7ed;
    }

    .kyc-img {
        border-radius: 14px;
        border: 1px solid #fed7aa;
        transition: 0.2s;
    }

    .kyc-img:hover {
        transform: scale(1.02);
    }
</style>

@if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
@endif

@if(session('error'))
    <div class="alert alert-danger">{{ session('error') }}</div>
@endif

@php
    $statusClass = match($kyc->status) {
        'approved' => 'approved',
        'rejected' => 'rejected',
        default => 'pending'
    };
@endphp

<div class="row g-4">

    {{-- LEFT --}}
    <div class="col-lg-5">

        <div class="card kyc-card">
            <div class="card-header kyc-header">
                <h5 class="mb-0">👤 Thông tin người dùng</h5>
            </div>

            <div class="card-body">
                <p><strong>Họ tên:</strong> {{ $kyc->user->name ?? 'Không rõ' }}</p>
                <p><strong>Email:</strong> {{ $kyc->user->email ?? 'Không rõ' }}</p>
                <p><strong>Vai trò:</strong> {{ $kyc->user->role ?? 'Không rõ' }}</p>
                <p><strong>SĐT:</strong> {{ $kyc->phone ?? 'Chưa cập nhật' }}</p>
                <p><strong>Công ty:</strong> {{ $kyc->company ?? 'Chưa cập nhật' }}</p>
                <p><strong>Chức vụ:</strong> {{ $kyc->position ?? 'Chưa cập nhật' }}</p>
                <p><strong>Kinh nghiệm:</strong> {{ $kyc->experience ?? 'Chưa cập nhật' }}</p>
                <p><strong>Mục tiêu:</strong> {{ $kyc->goal ?? 'Chưa cập nhật' }}</p>

                <p class="mt-3">
                    <strong>Trạng thái:</strong><br>
                    <span class="kyc-badge {{ $statusClass }}">
                        {{ strtoupper($kyc->status) }}
                    </span>
                </p>
            </div>
        </div>

        <div class="card kyc-card mt-4">
            <div class="card-header kyc-header">
                <h5 class="mb-0">📄 Giấy tờ</h5>
            </div>

            <div class="card-body">
                <p><strong>Loại:</strong> {{ strtoupper($kyc->document_type) }}</p>
                <p><strong>Số:</strong> {{ $kyc->document_number }}</p>
                <p><strong>Ngày gửi:</strong> {{ $kyc->created_at->format('d/m/Y H:i') }}</p>
            </div>
        </div>
    </div>

    {{-- RIGHT --}}
    <div class="col-lg-7">
        <div class="card kyc-card">
            <div class="card-header kyc-header">
                <h5 class="mb-0">🖼 Ảnh xác minh</h5>
            </div>

            <div class="card-body">

                <div class="mb-4">
                    <h6>Mặt trước</h6>
                    @if($kyc->document_front)
                        <img src="{{ asset('storage/' . $kyc->document_front) }}" class="img-fluid kyc-img">
                    @else
                        <div class="alert alert-warning">Chưa có ảnh</div>
                    @endif
                </div>

                <div class="mb-4">
                    <h6>Mặt sau</h6>
                    @if($kyc->document_back)
                        <img src="{{ asset('storage/' . $kyc->document_back) }}" class="img-fluid kyc-img">
                    @else
                        <div class="alert alert-warning">Chưa có ảnh</div>
                    @endif
                </div>

                <div class="mb-4">
                    <h6>Selfie</h6>
                    @if($kyc->selfie_image)
                        <img src="{{ asset('storage/' . $kyc->selfie_image) }}" class="img-fluid kyc-img">
                    @else
                        <div class="alert alert-warning">Chưa có ảnh</div>
                    @endif
                </div>

            </div>
        </div>
    </div>
</div>

{{-- ACTION --}}
@if($kyc->status === 'pending')
<div class="card kyc-card mt-4">
    <div class="card-header kyc-header">
        <h5 class="mb-0">⚡ Duyệt hồ sơ</h5>
    </div>

    <div class="card-body">

        <form method="POST" action="{{ route('admin.kyc.approve', $kyc) }}" class="d-inline">
            @csrf
            <button class="btn kyc-btn-orange" onclick="return confirm('Duyệt hồ sơ này?')">
                ✔ Duyệt
            </button>
        </form>

        <hr>

        <form method="POST" action="{{ route('admin.kyc.reject', $kyc) }}">
            @csrf

            <div class="mb-3">
                <label class="form-label">Lý do từ chối</label>
                <textarea name="reason" class="form-control" rows="3"></textarea>

                @error('reason')
                    <div class="text-danger small mt-1">{{ $message }}</div>
                @enderror
            </div>

            <button class="btn btn-outline-danger" onclick="return confirm('Từ chối hồ sơ này?')">
                ✖ Từ chối
            </button>
        </form>

    </div>
</div>
@endif

@endsection