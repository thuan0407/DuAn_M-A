@extends('layouts.buyer')

@section('title', 'Hồ sơ xác minh')

@section('content')

<style>
    .kyc-card {
        border-radius: 20px;
        border: 1px solid #fed7aa;
        box-shadow: 0 12px 28px rgba(249, 115, 22, 0.08);
        overflow: hidden;
    }

    .kyc-header {
        background: linear-gradient(135deg, #fff7ed, #ffedd5);
        border-bottom: 1px solid #fed7aa;
        font-weight: 700;
        color: #ea580c;
        padding: 14px 18px;
    }

    .kyc-label {
        font-size: 13px;
        color: #9ca3af;
        margin-bottom: 2px;
    }

    .kyc-value {
        font-size: 15px;
        font-weight: 700;
        color: #1f2937;
    }

    .kyc-row {
        margin-bottom: 14px;
    }

    .kyc-img {
        width: 80%;
        border-radius: 14px;
        border: 1px solid #fed7aa;
        transition: all 0.25s ease;
        cursor: pointer;
    }

    .kyc-img:hover {
        transform: scale(1.04);
        box-shadow: 0 12px 25px rgba(249, 115, 22, 0.2);
    }

    .badge-status {
        padding: 6px 14px;
        border-radius: 999px;
        font-size: 13px;
        font-weight: 700;
        display: inline-block;
    }

    .pending {
        background: #ffedd5;
        color: #ea580c;
    }

    .approved {
        background: #dcfce7;
        color: #16a34a;
    }

    .rejected {
        background: #fee2e2;
        color: #dc2626;
    }

    .kyc-title {
        font-size: 24px;
        font-weight: 900;
        color: #ea580c;
    }

    .kyc-subtitle {
        font-size: 14px;
        color: #6b7280;
    }
</style>

<div class="container py-4">

    <div class="mb-4">
        <div class="kyc-title">Hồ sơ xác minh</div>
        <div class="kyc-subtitle">Thông tin và trạng thái xác minh tài khoản của bạn</div>
    </div>

    <div class="row g-4">

        {{-- THÔNG TIN --}}
        <div class="col-lg-5">
            <div class="card kyc-card">
                <div class="kyc-header">
                    👤 Thông tin cá nhân
                </div>

                <div class="card-body">

                    <div class="kyc-row">
                        <div class="kyc-label">Loại giấy tờ</div>
                        <div class="kyc-value">{{ strtoupper($kyc->document_type) }}</div>
                    </div>

                    <div class="kyc-row">
                        <div class="kyc-label">Số giấy tờ</div>
                        <div class="kyc-value">{{ $kyc->document_number }}</div>
                    </div>

                    <div class="kyc-row">
                        <div class="kyc-label">Số điện thoại</div>
                        <div class="kyc-value">{{ $kyc->phone ?? '---' }}</div>
                    </div>

                    <div class="kyc-row">
                        <div class="kyc-label">Công ty</div>
                        <div class="kyc-value">{{ $kyc->company ?? '---' }}</div>
                    </div>

                    <div class="kyc-row">
                        <div class="kyc-label">Chức vụ</div>
                        <div class="kyc-value">{{ $kyc->position ?? '---' }}</div>
                    </div>

                    <div class="kyc-row">
                        <div class="kyc-label">Kinh nghiệm</div>
                        <div class="kyc-value">{{ $kyc->experience ?? '---' }}</div>
                    </div>

                    <div class="kyc-row">
                        <div class="kyc-label">Mục tiêu đầu tư</div>
                        <div class="kyc-value">{{ $kyc->goal ?? '---' }}</div>
                    </div>

                    <div class="kyc-row mt-3">
                        <div class="kyc-label">Trạng thái</div>

                        @if($kyc->status == 'pending')
                            <span class="badge-status pending">⏳ Đang chờ duyệt</span>
                        @elseif($kyc->status == 'approved')
                            <span class="badge-status approved">✅ Đã xác minh</span>
                        @else
                            <span class="badge-status rejected">❌ Bị từ chối</span>
                        @endif
                    </div>

                    @if($kyc->rejection_reason)
                        <div class="alert alert-danger mt-3" style="border-radius: 14px;">
                            <strong>Lý do từ chối:</strong><br>
                            {{ $kyc->rejection_reason }}
                        </div>
                    @endif

                </div>
            </div>
        </div>

        {{-- ẢNH --}}
        <div class="col-lg-7">
            <div class="card kyc-card">
                <div class="kyc-header">
                    🖼 Ảnh xác minh
                </div>

                <div class="card-body">

                    <div class="mb-4">
                        <h6>Mặt trước</h6>
                        @if($kyc->document_front)
                            <img src="{{ asset('storage/' . $kyc->document_front) }}" class="kyc-img">
                        @else
                            <div class="alert alert-warning">Chưa có ảnh</div>
                        @endif
                    </div>

                    <div class="mb-4">
                        <h6>Mặt sau</h6>
                        @if($kyc->document_back)
                            <img src="{{ asset('storage/' . $kyc->document_back) }}" class="kyc-img">
                        @else
                            <div class="alert alert-warning">Chưa có ảnh</div>
                        @endif
                    </div>

                    <div class="mb-4">
                        <h6>Selfie</h6>
                        @if($kyc->selfie_image)
                            <img src="{{ asset('storage/' . $kyc->selfie_image) }}" class="kyc-img">
                        @else
                            <div class="alert alert-warning">Chưa có ảnh</div>
                        @endif
                    </div>

                </div>
            </div>
        </div>

    </div>

</div>
@endsection