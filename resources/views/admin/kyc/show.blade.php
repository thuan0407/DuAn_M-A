@extends('layouts.admin')

@section('title', 'Chi tiết hồ sơ KYC')
@section('page_title', 'Chi tiết hồ sơ KYC')

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

<div class="row g-4">
    <div class="col-lg-5">
        <div class="card shadow-sm rounded-4">
            <div class="card-header bg-white">
                <h5 class="mb-0">Thông tin người dùng</h5>
            </div>

            <div class="card-body">
                <p><strong>Họ tên:</strong> {{ $kyc->user->name ?? 'Không rõ' }}</p>
                <p><strong>Email:</strong> {{ $kyc->user->email ?? 'Không rõ' }}</p>
                <p><strong>Vai trò:</strong> {{ $kyc->user->role ?? 'Không rõ' }}</p>
                <p><strong>Số điện thoại:</strong> {{ $kyc->phone ?? 'Chưa cập nhật' }}</p>
                <p><strong>Công ty:</strong> {{ $kyc->company ?? 'Chưa cập nhật' }}</p>
                <p><strong>Chức vụ:</strong> {{ $kyc->position ?? 'Chưa cập nhật' }}</p>
                <p><strong>Kinh nghiệm:</strong> {{ $kyc->experience ?? 'Chưa cập nhật' }}</p>
                <p><strong>Mục tiêu đầu tư:</strong> {{ $kyc->goal ?? 'Chưa cập nhật' }}</p>
                <p><strong>Trạng thái:</strong> 
                    <span class="badge bg-warning text-dark">
                        {{ $kyc->status }}
                    </span>
                </p>
            </div>
        </div>

        <div class="card shadow-sm rounded-4 mt-4">
            <div class="card-header bg-white">
                <h5 class="mb-0">Thông tin giấy tờ</h5>
            </div>

            <div class="card-body">
                <p><strong>Loại giấy tờ:</strong> {{ strtoupper($kyc->document_type) }}</p>
                <p><strong>Số giấy tờ:</strong> {{ $kyc->document_number }}</p>
                <p><strong>Ngày gửi:</strong> {{ $kyc->created_at->format('d/m/Y H:i') }}</p>
            </div>
        </div>
    </div>

    <div class="col-lg-7">
        <div class="card shadow-sm rounded-4">
            <div class="card-header bg-white">
                <h5 class="mb-0">Ảnh xác minh</h5>
            </div>

            <div class="card-body">
                <div class="mb-4">
                    <h6>Mặt trước giấy tờ</h6>

                    @if($kyc->document_front)
                        <img src="{{ asset('storage/' . $kyc->document_front) }}" 
                             class="img-fluid rounded border"
                             alt="Mặt trước giấy tờ">
                    @else
                        <div class="alert alert-warning">Chưa có ảnh mặt trước.</div>
                    @endif
                </div>

                <div class="mb-4">
                    <h6>Mặt sau giấy tờ</h6>

                    @if($kyc->document_back)
                        <img src="{{ asset('storage/' . $kyc->document_back) }}" 
                             class="img-fluid rounded border"
                             alt="Mặt sau giấy tờ">
                    @else
                        <div class="alert alert-warning">Chưa có ảnh mặt sau.</div>
                    @endif
                </div>

                <div class="mb-4">
                    <h6>Ảnh selfie xác minh</h6>

                    @if($kyc->selfie_image)
                        <img src="{{ asset('storage/' . $kyc->selfie_image) }}" 
                             class="img-fluid rounded border"
                             alt="Ảnh selfie xác minh">
                    @else
                        <div class="alert alert-warning">Chưa có ảnh selfie.</div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

@if($kyc->status === 'pending')
    <div class="card shadow-sm rounded-4 mt-4">
        <div class="card-header bg-white">
            <h5 class="mb-0">Thao tác duyệt hồ sơ</h5>
        </div>

        <div class="card-body">
            <form method="POST" action="{{ route('admin.kyc.approve', $kyc) }}" class="d-inline">
                @csrf
                <button class="btn btn-success" onclick="return confirm('Duyệt hồ sơ này?')">
                    Duyệt hồ sơ
                </button>
            </form>

            <hr>

            <form method="POST" action="{{ route('admin.kyc.reject', $kyc) }}">
                @csrf

                <div class="mb-3">
                    <label class="form-label">Lý do từ chối</label>
                    <textarea name="reason" class="form-control" rows="3" placeholder="Nhập lý do từ chối hồ sơ..."></textarea>

                    @error('reason')
                        <div class="text-danger small mt-1">{{ $message }}</div>
                    @enderror
                </div>

                <button class="btn btn-danger" onclick="return confirm('Từ chối hồ sơ này?')">
                    Từ chối hồ sơ
                </button>
            </form>
        </div>
    </div>
@endif

@endsection