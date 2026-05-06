@extends($layout ?? 'layouts.buyer')

@section('content')
<div class="container py-4">
    <div class="card shadow-sm">
        <div class="card-header bg-white">
            <h4 class="mb-0">Thông tin xác minh KYC</h4>
        </div>

        <div class="card-body">

            {{-- Thông báo --}}
            @if($kyc)
                @if($kyc->status === 'pending')
                    <div class="alert alert-warning">
                        Hồ sơ của bạn đang chờ duyệt.
                    </div>
                @elseif($kyc->status === 'approved')
                    <div class="alert alert-success">
                        Hồ sơ của bạn đã được duyệt.
                    </div>
                @elseif($kyc->status === 'rejected')
                    <div class="alert alert-danger">
                        Hồ sơ của bạn đã bị từ chối.

                        @if($kyc->rejection_reason)
                            <div class="mt-2">
                                <strong>Lý do:</strong> {{ $kyc->rejection_reason }}
                            </div>
                        @endif
                    </div>
                @endif
            @endif

            {{-- Thông tin KYC --}}
            <div class="row">

                <div class="col-md-6 mb-3">
                    <label class="form-label">Loại giấy tờ</label>
                    <input type="text" class="form-control"
                        value="{{ strtoupper($kyc->document_type ?? '') }}" readonly>
                </div>

                <div class="col-md-6 mb-3">
                    <label class="form-label">Số giấy tờ</label>
                    <input type="text" class="form-control"
                        value="{{ $kyc->document_number ?? '' }}" readonly>
                </div>

            </div>

            {{-- Ảnh --}}
            <div class="row">

                <div class="col-md-4 mb-3">
                    <label class="form-label">Ảnh mặt trước</label>
                    @if($kyc && $kyc->document_front)
                        <img src="{{ asset('storage/' . $kyc->document_front) }}"
                             class="img-fluid rounded border">
                    @endif
                </div>

                <div class="col-md-4 mb-3">
                    <label class="form-label">Ảnh mặt sau</label>
                    @if($kyc && $kyc->document_back)
                        <img src="{{ asset('storage/' . $kyc->document_back) }}"
                             class="img-fluid rounded border">
                    @endif
                </div>

                <div class="col-md-4 mb-3">
                    <label class="form-label">Ảnh selfie</label>
                    @if($kyc && $kyc->selfie_image)
                        <img src="{{ asset('storage/' . $kyc->selfie_image) }}"
                             class="img-fluid rounded border">
                    @endif
                </div>

            </div>

            <hr>

            {{-- Thông tin bổ sung --}}
            <div class="mb-3">
                <label class="form-label">Công ty</label>
                <input type="text" class="form-control"
                    value="{{ $kyc->company ?? '' }}" readonly>
            </div>

            <div class="mb-3">
                <label class="form-label">Chức vụ</label>
                <input type="text" class="form-control"
                    value="{{ $kyc->position ?? '' }}" readonly>
            </div>

            <div class="mb-3">
                <label class="form-label">Kinh nghiệm</label>
                <textarea class="form-control" rows="3" readonly>{{ $kyc->experience ?? '' }}</textarea>
            </div>

            <div class="mb-3">
                <label class="form-label">Mục tiêu đầu tư</label>
                <textarea class="form-control" rows="3" readonly>{{ $kyc->goal ?? '' }}</textarea>
            </div>

            <div class="mb-3">
                <label class="form-label">Số điện thoại</label>
                <input type="text" class="form-control"
                    value="{{ $kyc->phone ?? '' }}" readonly>
            </div>

            {{-- Action --}}
            <div class="d-flex gap-2">
                @if($kyc && $kyc->status === 'rejected')
                    <a href="{{ route('buyer.kyc.create') }}" class="btn btn-warning">
                        Gửi lại hồ sơ
                    </a>
                @endif

                <a href="{{ route('buyer.home') }}" class="btn btn-secondary">
                    Quay về trang chủ
                </a>
            </div>

        </div>
    </div>
</div>
@endsection