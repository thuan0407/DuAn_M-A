@extends($layout ?? 'layouts.buyer')

@section('content')
<div class="container py-4">
    <div class="card shadow-sm">
        <div class="card-header bg-white">
            <h4 class="mb-0">Xác minh tài khoản KYC</h4>
        </div>

        <div class="card-body">
            @if(session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
            @endif

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

            <form method="POST" action="{{ route($formAction ?? 'buyer.kyc.store') }}" enctype="multipart/form-data">
                @csrf

                <div class="mb-3">
                    <label class="form-label">Loại giấy tờ <span class="text-danger">*</span></label>
                    <select name="document_type" class="form-select">
                        <option value="cccd" {{ old('document_type', $kyc->document_type ?? '') === 'cccd' ? 'selected' : '' }}>
                            Căn cước công dân
                        </option>
                        <option value="passport" {{ old('document_type', $kyc->document_type ?? '') === 'passport' ? 'selected' : '' }}>
                            Hộ chiếu
                        </option>
                    </select>

                    @error('document_type')
                        <div class="text-danger small mt-1">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label class="form-label">Số giấy tờ <span class="text-danger">*</span></label>
                    <input 
                        type="text" 
                        name="document_number" 
                        class="form-control" 
                        value="{{ old('document_number', $kyc->document_number ?? '') }}"
                    >

                    @error('document_number')
                        <div class="text-danger small mt-1">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label class="form-label">
                        Ảnh mặt trước giấy tờ 
                        @if(!$kyc || !$kyc->document_front)
                            <span class="text-danger">*</span>
                        @endif
                    </label>

                    <input type="file" name="document_front" class="form-control" accept="image/*">

                    @if($kyc && $kyc->document_front)
                        <div class="mt-2">
                            <p class="mb-1 text-muted">Ảnh hiện tại:</p>
                            <img 
                                src="{{ asset('storage/' . $kyc->document_front) }}" 
                                alt="Ảnh mặt trước giấy tờ" 
                                class="img-thumbnail" 
                                style="max-width: 220px;"
                            >
                        </div>
                    @endif

                    @error('document_front')
                        <div class="text-danger small mt-1">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label class="form-label">
                        Ảnh mặt sau giấy tờ 
                        @if(!$kyc || !$kyc->document_back)
                            <span class="text-danger">*</span>
                        @endif
                    </label>

                    <input type="file" name="document_back" class="form-control" accept="image/*">

                    @if($kyc && $kyc->document_back)
                        <div class="mt-2">
                            <p class="mb-1 text-muted">Ảnh hiện tại:</p>
                            <img 
                                src="{{ asset('storage/' . $kyc->document_back) }}" 
                                alt="Ảnh mặt sau giấy tờ" 
                                class="img-thumbnail" 
                                style="max-width: 220px;"
                            >
                        </div>
                    @endif

                    @error('document_back')
                        <div class="text-danger small mt-1">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label class="form-label">
                        Ảnh selfie xác minh 
                        @if(!$kyc || !$kyc->selfie_image)
                            <span class="text-danger">*</span>
                        @endif
                    </label>

                    <input type="file" name="selfie_image" class="form-control" accept="image/*">

                    @if($kyc && $kyc->selfie_image)
                        <div class="mt-2">
                            <p class="mb-1 text-muted">Ảnh hiện tại:</p>
                            <img 
                                src="{{ asset('storage/' . $kyc->selfie_image) }}" 
                                alt="Ảnh selfie xác minh" 
                                class="img-thumbnail" 
                                style="max-width: 220px;"
                            >
                        </div>
                    @endif

                    @error('selfie_image')
                        <div class="text-danger small mt-1">{{ $message }}</div>
                    @enderror
                </div>

                <hr>

                <div class="mb-3">
                    <label class="form-label">Công ty đang công tác</label>
                    <input 
                        type="text" 
                        name="company" 
                        class="form-control" 
                        value="{{ old('company', $kyc->company ?? '') }}"
                    >

                    @error('company')
                        <div class="text-danger small mt-1">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label class="form-label">Chức vụ</label>
                    <input 
                        type="text" 
                        name="position" 
                        class="form-control" 
                        value="{{ old('position', $kyc->position ?? '') }}"
                    >

                    @error('position')
                        <div class="text-danger small mt-1">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label class="form-label">Kinh nghiệm</label>
                    <textarea 
                        name="experience" 
                        class="form-control" 
                        rows="3"
                    >{{ old('experience', $kyc->experience ?? '') }}</textarea>

                    @error('experience')
                        <div class="text-danger small mt-1">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label class="form-label">Mục tiêu đầu tư</label>
                    <textarea 
                        name="goal" 
                        class="form-control" 
                        rows="3"
                    >{{ old('goal', $kyc->goal ?? '') }}</textarea>

                    @error('goal')
                        <div class="text-danger small mt-1">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label class="form-label">Số điện thoại</label>
                    <input 
                        type="text" 
                        name="phone" 
                        class="form-control" 
                        value="{{ old('phone', $kyc->phone ?? '') }}"
                    >

                    @error('phone')
                        <div class="text-danger small mt-1">{{ $message }}</div>
                    @enderror
                </div>

                <button type="submit" class="btn btn-primary">
                    Gửi hồ sơ xác minh
                </button>
            </form>
        </div>
    </div>
</div>
@endsection