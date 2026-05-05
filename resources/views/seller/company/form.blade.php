@extends('layouts.seller')

@section('title', $mode === 'create' ? 'Tạo hồ sơ doanh nghiệp' : 'Cập nhật hồ sơ doanh nghiệp')
@section('page_title', $mode === 'create' ? 'Tạo hồ sơ doanh nghiệp' : 'Cập nhật hồ sơ doanh nghiệp')
@section('page_description', 'Thông tin này sẽ được admin kiểm tra và duyệt trước khi công ty được xác minh')

@section('content')

<style>
    .company-form-card {
        background: #ffffff;
        border: 1px solid #fed7aa;
        border-radius: 24px;
        padding: 28px;
        box-shadow: 0 10px 26px rgba(249, 115, 22, 0.07);
    }

    .form-label {
        font-weight: 800;
        color: #374151;
    }

    .form-control {
        border-radius: 14px;
        border: 1px solid #fed7aa;
        min-height: 48px;
    }

    .form-control:focus {
        border-color: #f97316;
        box-shadow: 0 0 0 4px rgba(249, 115, 22, 0.14);
    }

    .btn-orange {
        background: #f97316;
        border: 1px solid #f97316;
        color: #ffffff;
        border-radius: 14px;
        padding: 11px 18px;
        font-weight: 800;
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
    }

    .btn-soft-orange:hover {
        background: #ffedd5;
        color: #ea580c;
    }
</style>

<div class="company-form-card">
    @if ($errors->any())
        <div class="alert alert-danger rounded-4 border-0 mb-4">
            Vui lòng kiểm tra lại thông tin. Có trường đang nhập sai hoặc bị thiếu.
        </div>
    @endif

    <form method="POST"
          action="{{ $mode === 'create' ? route('seller.company.store') : route('seller.company.update') }}">
        @csrf

        @if ($mode === 'edit')
            @method('PUT')
        @endif

        <div class="row g-4">
            <div class="col-md-6">
                <label class="form-label">Tên pháp lý công ty</label>
                <input type="text"
                       name="legal_name"
                       class="form-control @error('legal_name') is-invalid @enderror"
                       value="{{ old('legal_name', $company->legal_name) }}"
                       placeholder="Ví dụ: Công ty TNHH ABC"
                       required>

                @error('legal_name')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="col-md-6">
                <label class="form-label">Mã số thuế</label>
                <input type="text"
                       name="tax_code"
                       class="form-control @error('tax_code') is-invalid @enderror"
                       value="{{ old('tax_code', $company->tax_code) }}"
                       placeholder="Ví dụ: 0312345678"
                       required>

                @error('tax_code')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="col-md-6">
                <label class="form-label">Số đăng ký kinh doanh</label>
                <input type="text"
                       name="business_registration_number"
                       class="form-control @error('business_registration_number') is-invalid @enderror"
                       value="{{ old('business_registration_number', $company->business_registration_number) }}"
                       placeholder="Nhập số đăng ký kinh doanh"
                       required>

                @error('business_registration_number')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="col-md-6">
                <label class="form-label">Người đại diện pháp luật</label>
                <input type="text"
                       name="legal_representative_name"
                       class="form-control @error('legal_representative_name') is-invalid @enderror"
                       value="{{ old('legal_representative_name', $company->legal_representative_name) }}"
                       placeholder="Nhập tên người đại diện"
                       required>

                @error('legal_representative_name')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="col-md-6">
                <label class="form-label">Ngành nghề</label>
                <input type="text"
                       name="industry"
                       class="form-control @error('industry') is-invalid @enderror"
                       value="{{ old('industry', $company->industry) }}"
                       placeholder="Ví dụ: Sản xuất nội thất"
                       required>

                @error('industry')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="col-md-6">
                <label class="form-label">Địa chỉ công ty</label>
                <input type="text"
                       name="address"
                       class="form-control @error('address') is-invalid @enderror"
                       value="{{ old('address', $company->address) }}"
                       placeholder="Nhập địa chỉ công ty"
                       required>

                @error('address')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="col-12">
                <label class="form-label">Mô tả doanh nghiệp</label>
                <textarea name="description"
                          class="form-control @error('description') is-invalid @enderror"
                          rows="5"
                          placeholder="Mô tả ngắn về hoạt động kinh doanh, sản phẩm, thị trường, điểm mạnh...">{{ old('description', $company->description) }}</textarea>

                @error('description')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
        </div>

        <div class="d-flex justify-content-end gap-3 mt-4">
            <a href="{{ route('seller.company.show') }}" class="btn-soft-orange">
                Hủy
            </a>

            <button type="submit" class="btn-orange">
                {{ $mode === 'create' ? 'Tạo hồ sơ' : 'Cập nhật hồ sơ' }}
            </button>
        </div>
    </form>
</div>

@endsection