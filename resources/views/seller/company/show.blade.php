@extends('layouts.seller')

@section('title', 'Hồ sơ doanh nghiệp')
@section('page_title', 'Hồ sơ doanh nghiệp')
@section('page_description', 'Quản lý thông tin doanh nghiệp, deal và trạng thái xác minh hồ sơ')

@section('content')

<style>
    .company-card {
        background: #ffffff;
        border: 1px solid #fed7aa;
        border-radius: 24px;
        box-shadow: 0 10px 26px rgba(249, 115, 22, 0.07);
    }

    .company-soft-box {
        background: #fff7ed;
        border: 1px solid #fed7aa;
        border-radius: 18px;
        padding: 18px;
    }

    .company-label {
        font-size: 13px;
        color: #9ca3af;
        margin-bottom: 4px;
    }

    .company-value {
        font-weight: 700;
        color: #1f2937;
        margin-bottom: 0;
    }

    .btn-orange {
        background: #f97316;
        border: 1px solid #f97316;
        color: #ffffff;
        border-radius: 14px;
        padding: 11px 18px;
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

    .btn-soft-orange:disabled {
        opacity: 0.65;
        cursor: not-allowed;
    }

    .status-badge {
        display: inline-flex;
        padding: 7px 12px;
        border-radius: 999px;
        font-size: 13px;
        font-weight: 800;
    }

    .status-pending {
        background: #fef3c7;
        color: #b45309;
    }

    .status-verified {
        background: #dcfce7;
        color: #15803d;
    }

    .status-rejected {
        background: #fee2e2;
        color: #b91c1c;
    }

    .status-published {
        background: #dcfce7;
        color: #15803d;
    }

    .status-draft {
        background: #f3f4f6;
        color: #4b5563;
    }

    .deal-card {
        background: #ffffff;
        border: 1px solid #fed7aa;
        border-radius: 22px;
        padding: 20px;
        height: 100%;
        box-shadow: 0 10px 26px rgba(249, 115, 22, 0.06);
        position: relative;
        overflow: hidden;
    }

    .deal-card::before {
        content: "";
        position: absolute;
        inset: 0 0 auto 0;
        height: 5px;
        background: linear-gradient(90deg, #f97316, #fb923c);
    }

    .deal-title {
        font-size: 17px;
        font-weight: 900;
        color: #1f2937;
        margin-bottom: 8px;
    }

    .deal-description {
        color: #6b7280;
        font-size: 14px;
        line-height: 1.6;
        min-height: 68px;
    }

    .deal-price-box {
        background: #fff7ed;
        border: 1px solid #fed7aa;
        border-radius: 16px;
        padding: 14px;
    }
</style>

@if (session('success'))
    <div class="alert alert-success rounded-4 border-0 shadow-sm mb-4">
        {{ session('success') }}
    </div>
@endif

@if (session('info'))
    <div class="alert alert-info rounded-4 border-0 shadow-sm mb-4">
        {{ session('info') }}
    </div>
@endif

@if (session('error'))
    <div class="alert alert-danger rounded-4 border-0 shadow-sm mb-4">
        {{ session('error') }}
    </div>
@endif
@php
    $financialsForChart = $company && $company->financials
        ? $company->financials->sortBy('financial_year')->values()
        : collect();

    $chartLabels = $financialsForChart
        ->pluck('financial_year')
        ->map(fn ($value) => (string) $value)
        ->values()
        ->all();

    $chartRevenue = $financialsForChart
        ->pluck('revenue')
        ->map(fn ($value) => (float) $value)
        ->values()
        ->all();

    $chartEbitda = $financialsForChart
        ->pluck('ebitda')
        ->map(fn ($value) => (float) $value)
        ->values()
        ->all();

    $chartNetProfit = $financialsForChart
        ->pluck('net_profit')
        ->map(fn ($value) => (float) $value)
        ->values()
        ->all();
@endphp

{{-- Sơ đồ tài chính--}}
<div class="company-card p-4 p-lg-5 mb-4">
    <div class="d-flex flex-column flex-lg-row justify-content-between align-items-lg-center gap-3 mb-4">
        <div>
            <h3 class="fw-bold mb-1">
                Biểu đồ tài chính doanh nghiệp
            </h3>
            <p class="text-muted mb-0">
                Biểu đồ cột thể hiện doanh thu, EBITDA và lợi nhuận ròng theo từng năm.
            </p>
        </div>
    </div>

    @if($company && $company->financials && $company->financials->count() > 0)
        <div class="chart-wrapper" style="position: relative; height: 420px;">
            <canvas id="companyFinancialChart"></canvas>
        </div>
    @else
        <div class="company-soft-box text-center">
            <h5 class="fw-bold mb-2">Chưa có dữ liệu để vẽ biểu đồ</h5>
            <p class="text-muted mb-0">
                Bạn cần thêm dữ liệu tài chính doanh nghiệp trước.
            </p>
        </div>
    @endif
</div>


{{--  thông số của công ty --}}
<div class="company-card p-4 p-lg-5 mb-4">
    <div class="d-flex flex-column flex-lg-row justify-content-between align-items-lg-center gap-3 mb-4">
        <div>
            <h3 class="fw-bold mb-1">
                Dữ liệu tài chính doanh nghiệp
            </h3>

            <p class="text-muted mb-0">
                Cập nhật doanh thu, EBITDA và lợi nhuận ròng theo từng năm của công ty.
            </p>
        </div>

        <button type="button"
                class="btn-orange"
                data-bs-toggle="modal"
                data-bs-target="#createCompanyFinancialModal">
            Thêm dữ liệu tài chính
        </button>
    </div>

    @if ($company->financials && $company->financials->count() > 0)
        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead class="table-light">
                    <tr>
                        <th>Năm tài chính</th>
                        <th>Doanh thu</th>
                        <th>EBITDA</th>
                        <th>Lợi nhuận ròng</th>
                        <th>Ngày cập nhật</th>
                        <th class="text-end">Hành động</th>
                    </tr>
                </thead>

                <tbody>
                    @foreach ($company->financials->sortByDesc('financial_year') as $financial)
                        <tr>
                            <td>
                                <strong>{{ $financial->financial_year }}</strong>
                            </td>

                            <td>
                                @if (!is_null($financial->revenue))
                                    {{ number_format($financial->revenue, 0, ',', '.') }}
                                @else
                                    Chưa nhập
                                @endif
                            </td>

                            <td>
                                @if (!is_null($financial->ebitda))
                                    {{ number_format($financial->ebitda, 0, ',', '.') }}
                                @else
                                    Chưa nhập
                                @endif
                            </td>

                            <td>
                                @if (!is_null($financial->net_profit))
                                    {{ number_format($financial->net_profit, 0, ',', '.') }}
                                @else
                                    Chưa nhập
                                @endif
                            </td>

                            <td>
                                {{ $financial->updated_at ? $financial->updated_at->format('d/m/Y H:i') : 'Chưa xác định' }}
                            </td>

                            <td class="text-end">
                                <div class="d-flex justify-content-end gap-2">
                                    <button type="button"
                                            class="btn btn-sm btn-outline-warning"
                                            data-bs-toggle="modal"
                                            data-bs-target="#editCompanyFinancialModal{{ $financial->id }}">
                                        Sửa
                                    </button>

                                    <form action="{{ route('seller.company.financials.destroy', $financial) }}"
                                          method="POST"
                                          onsubmit="return confirm('Bạn có chắc muốn xóa dữ liệu tài chính năm này không?');">
                                        @csrf
                                        @method('DELETE')

                                        <button type="submit" class="btn btn-sm btn-outline-danger">
                                            Xóa
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>

                        <div class="modal fade" id="editCompanyFinancialModal{{ $financial->id }}" tabindex="-1" aria-hidden="true">
                            <div class="modal-dialog">
                                <form action="{{ route('seller.company.financials.update', $financial) }}"
                                      method="POST">
                                    @csrf
                                    @method('PUT')

                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title">
                                                Sửa dữ liệu tài chính
                                            </h5>

                                            <button type="button"
                                                    class="btn-close"
                                                    data-bs-dismiss="modal">
                                            </button>
                                        </div>

                                        <div class="modal-body">
                                            <div class="mb-3">
                                                <label class="form-label fw-bold">
                                                    Năm tài chính
                                                </label>

                                                <input type="number"
                                                       name="financial_year"
                                                       class="form-control"
                                                       value="{{ old('financial_year', $financial->financial_year) }}"
                                                       min="1900"
                                                       max="2100"
                                                       required>
                                            </div>

                                            <div class="mb-3">
                                                <label class="form-label fw-bold">
                                                    Doanh thu
                                                </label>

                                                <input type="number"
                                                       name="revenue"
                                                       class="form-control"
                                                       value="{{ old('revenue', $financial->revenue) }}"
                                                       min="0"
                                                       placeholder="Ví dụ: 5000000000">
                                            </div>

                                            <div class="mb-3">
                                                <label class="form-label fw-bold">
                                                    EBITDA
                                                </label>

                                                <input type="number"
                                                       name="ebitda"
                                                       class="form-control"
                                                       value="{{ old('ebitda', $financial->ebitda) }}"
                                                       placeholder="Ví dụ: 800000000">
                                            </div>

                                            <div class="mb-3">
                                                <label class="form-label fw-bold">
                                                    Lợi nhuận ròng
                                                </label>

                                                <input type="number"
                                                       name="net_profit"
                                                       class="form-control"
                                                       value="{{ old('net_profit', $financial->net_profit) }}"
                                                       placeholder="Ví dụ: 500000000">
                                            </div>
                                        </div>

                                        <div class="modal-footer">
                                            <button type="button"
                                                    class="btn btn-secondary"
                                                    data-bs-dismiss="modal">
                                                Hủy
                                            </button>

                                            <button type="submit" class="btn-orange">
                                                Lưu thay đổi
                                            </button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    @endforeach
                </tbody>
            </table>
        </div>
    @else
        <div class="company-soft-box text-center">
            <h5 class="fw-bold mb-2">
                Chưa có dữ liệu tài chính
            </h5>

            <p class="text-muted mb-3">
                Bạn có thể thêm doanh thu, EBITDA và lợi nhuận ròng theo từng năm để hồ sơ doanh nghiệp đầy đủ hơn.
            </p>

            <button type="button"
                    class="btn-orange"
                    data-bs-toggle="modal"
                    data-bs-target="#createCompanyFinancialModal">
                Thêm dữ liệu tài chính đầu tiên
            </button>
        </div>
    @endif
</div>

@if (!$company)
    <div class="company-card p-4 p-lg-5">
        <div class="row align-items-center g-4">
            <div class="col-lg-8">
                <h3 class="fw-bold mb-2">
                    Bạn chưa có hồ sơ doanh nghiệp
                </h3>

                <p class="text-muted mb-0">
                    Seller cần tạo thông tin công ty trước khi đăng deal. Sau khi tạo, hồ sơ sẽ ở trạng thái chờ admin xác minh.
                </p>
            </div>

            <div class="col-lg-4 text-lg-end">
                <a href="{{ route('seller.company.create') }}" class="btn-orange">
                    Tạo hồ sơ doanh nghiệp
                </a>
            </div>
        </div>
    </div>
@else
    @php
        $companyStatusLabel = match ($company->verification_status) {
            'pending' => 'Chờ duyệt hồ sơ',
            'verified' => 'Đã duyệt hồ sơ',
            'rejected' => 'Hồ sơ bị từ chối',
            default => 'Chưa xác định',
        };

        $companyStatusClass = match ($company->verification_status) {
            'pending' => 'status-pending',
            'verified' => 'status-verified',
            'rejected' => 'status-rejected',
            default => 'status-pending',
        };
    @endphp

    <div class="company-card p-4 p-lg-5 mb-4">
        <div class="d-flex flex-column flex-lg-row justify-content-between align-items-lg-start gap-3 mb-4">
            <div>
                <h3 class="fw-bold mb-2">
                    {{ $company->legal_name }}
                </h3>

                <span class="status-badge {{ $companyStatusClass }}">
                    {{ $companyStatusLabel }}
                </span>
            </div>

            <div class="d-flex flex-wrap gap-2">
                <a href="{{ route('seller.company.edit') }}" class="btn-soft-orange">
                    Cập nhật công ty
                </a>

                @if ($company->verification_status === 'verified')
                    <a href="{{ route('seller.deals.create') }}" class="btn-orange">
                        Tạo deal mới
                    </a>
                @else
                    <button type="button" class="btn-soft-orange" disabled>
                        Chờ admin duyệt hồ sơ
                    </button>
                @endif
            </div>
        </div>

        @if ($company->verification_status === 'pending')
            <div class="alert alert-warning rounded-4 border-0 mb-4">
                Hồ sơ doanh nghiệp đang chờ admin kiểm tra. Bạn chưa thể tạo deal cho đến khi hồ sơ được duyệt.
            </div>
        @endif

        @if ($company->verification_status === 'verified')
            <div class="alert alert-success rounded-4 border-0 mb-4">
                Hồ sơ doanh nghiệp đã được admin duyệt. Bạn có thể tạo deal mới. Mỗi deal tạo hoặc sửa sẽ tiếp tục cần admin duyệt trước khi hiển thị cho buyer.
            </div>
        @endif

        @if ($company->verification_status === 'rejected')
            <div class="alert alert-danger rounded-4 border-0 mb-4">
                Hồ sơ doanh nghiệp bị từ chối.
                @if ($company->rejection_reason)
                    <br>
                    Lý do: {{ $company->rejection_reason }}
                @endif
            </div>
        @endif

        <div class="row g-4">
            <div class="col-md-6">
                <div class="company-soft-box">
                    <p class="company-label">Tên pháp lý công ty</p>
                    <p class="company-value">{{ $company->legal_name }}</p>
                </div>
            </div>

            <div class="col-md-6">
                <div class="company-soft-box">
                    <p class="company-label">Mã số thuế</p>
                    <p class="company-value">{{ $company->tax_code }}</p>
                </div>
            </div>

            <div class="col-md-6">
                <div class="company-soft-box">
                    <p class="company-label">Số đăng ký kinh doanh</p>
                    <p class="company-value">{{ $company->business_registration_number }}</p>
                </div>
            </div>

            <div class="col-md-6">
                <div class="company-soft-box">
                    <p class="company-label">Người đại diện pháp luật</p>
                    <p class="company-value">{{ $company->legal_representative_name }}</p>
                </div>
            </div>

            <div class="col-md-6">
                <div class="company-soft-box">
                    <p class="company-label">Ngành nghề</p>
                    <p class="company-value">{{ $company->industry }}</p>
                </div>
            </div>

            <div class="col-md-6">
                <div class="company-soft-box">
                    <p class="company-label">Địa chỉ</p>
                    <p class="company-value">{{ $company->address }}</p>
                </div>
            </div>

            <div class="col-12">
                <div class="company-soft-box">
                    <p class="company-label">Mô tả doanh nghiệp</p>
                    <p class="company-value">
                        {{ $company->description ?? 'Chưa có mô tả.' }}
                    </p>
                </div>
            </div>
        </div>
    </div>

    {{-- Thông tin giấy tờ doanh nghiệp --}}
    <div class="company-card p-4 p-lg-5 mb-4">
        <div class="d-flex flex-column flex-lg-row justify-content-between align-items-lg-center gap-3 mb-4">
            <div>
                <h3 class="fw-bold mb-1">
                    Giấy tờ hồ sơ doanh nghiệp
                </h3>

                <p class="text-muted mb-0">
                    Danh sách giấy tờ pháp lý đã tải lên cho công ty này.
                </p>
            </div>

            <button type="button"
                    class="btn-orange"
                    data-bs-toggle="modal"
                    data-bs-target="#uploadCompanyDocumentModal">
                Thêm giấy tờ
            </button>
        </div>

        @if ($company->documents && $company->documents->count() > 0)
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>Loại giấy tờ</th>
                            <th>Tên file</th>
                            <th>Ghi chú</th>
                            <th>Ngày tải lên</th>
                            <th class="text-end">Hành động</th>
                        </tr>
                    </thead>

                    <tbody>
                        @foreach ($company->documents as $document)
                            @php
                                $documentTypeLabel = match ($document->document_type) {
                                    'business_license' => 'Giấy đăng ký kinh doanh',
                                    'tax_document' => 'Giấy tờ thuế / mã số thuế',
                                    'financial_statement' => 'Báo cáo tài chính',
                                    'ownership_document' => 'Giấy tờ sở hữu / góp vốn / ủy quyền',
                                    'other' => 'Tài liệu khác',
                                    default => 'Không xác định',
                                };
                            @endphp

                            <tr>
                                <td>
                                    <strong>{{ $documentTypeLabel }}</strong>
                                </td>

                                <td>
                                    {{ $document->file_name }}
                                </td>

                                <td>
                                    {{ $document->note ?? 'Không có ghi chú.' }}
                                </td>

                                <td>
                                    {{ $document->created_at ? $document->created_at->format('d/m/Y H:i') : 'Chưa xác định' }}
                                </td>

                                <td class="text-end">
                                    <div class="d-flex justify-content-end gap-2">
                                        <a href="{{ asset('storage/' . $document->file_path) }}"
                                        target="_blank"
                                        class="btn btn-sm btn-outline-info">
                                            Xem file
                                        </a>

                                        <button type="button"
                                                class="btn btn-sm btn-outline-warning"
                                                data-bs-toggle="modal"
                                                data-bs-target="#editCompanyDocumentModal{{ $document->id }}">
                                            Sửa
                                        </button>
                                    </div>
                                </td>
                            </tr>

                            <div class="modal fade" id="editCompanyDocumentModal{{ $document->id }}" tabindex="-1" aria-hidden="true">
                                <div class="modal-dialog">
                                    <form action="{{ route('seller.company.documents.update', $document) }}"
                                        method="POST"
                                        enctype="multipart/form-data">
                                        @csrf
                                        @method('PUT')

                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title">
                                                    Sửa giấy tờ hồ sơ
                                                </h5>

                                                <button type="button"
                                                        class="btn-close"
                                                        data-bs-dismiss="modal">
                                                </button>
                                            </div>

                                            <div class="modal-body">
                                                <div class="mb-3">
                                                    <label class="form-label fw-bold">
                                                        Loại giấy tờ
                                                    </label>

                                                    <select name="document_type" class="form-select" required>
                                                        <option value="business_license" {{ $document->document_type === 'business_license' ? 'selected' : '' }}>
                                                            Giấy đăng ký kinh doanh
                                                        </option>

                                                        <option value="tax_document" {{ $document->document_type === 'tax_document' ? 'selected' : '' }}>
                                                            Giấy tờ thuế / mã số thuế
                                                        </option>

                                                        <option value="financial_statement" {{ $document->document_type === 'financial_statement' ? 'selected' : '' }}>
                                                            Báo cáo tài chính
                                                        </option>

                                                        <option value="ownership_document" {{ $document->document_type === 'ownership_document' ? 'selected' : '' }}>
                                                            Giấy tờ sở hữu / góp vốn / ủy quyền
                                                        </option>

                                                        <option value="other" {{ $document->document_type === 'other' ? 'selected' : '' }}>
                                                            Tài liệu khác
                                                        </option>
                                                    </select>
                                                </div>

                                                <div class="mb-3">
                                                    <label class="form-label fw-bold">
                                                        File hiện tại
                                                    </label>

                                                    <div>
                                                        <a href="{{ asset('storage/' . $document->file_path) }}"
                                                        target="_blank"
                                                        class="auth-link">
                                                            {{ $document->file_name }}
                                                        </a>
                                                    </div>
                                                </div>

                                                <div class="mb-3">
                                                    <label class="form-label fw-bold">
                                                        Thay file mới nếu cần
                                                    </label>

                                                    <input type="file"
                                                        name="document_file"
                                                        class="form-control"
                                                        accept=".pdf,.jpg,.jpeg,.png,.doc,.docx">

                                                    <div class="text-muted small mt-2">
                                                        Bỏ trống nếu bạn chỉ muốn sửa loại giấy tờ hoặc ghi chú.
                                                    </div>
                                                </div>

                                                <div class="mb-3">
                                                    <label class="form-label fw-bold">
                                                        Ghi chú
                                                    </label>

                                                    <textarea name="note"
                                                            class="form-control"
                                                            rows="3">{{ old('note', $document->note) }}</textarea>
                                                </div>
                                            </div>

                                            <div class="modal-footer">
                                                <button type="button"
                                                        class="btn btn-secondary"
                                                        data-bs-dismiss="modal">
                                                    Hủy
                                                </button>

                                                <button type="submit" class="btn-orange">
                                                    Lưu thay đổi
                                                </button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <div class="company-soft-box text-center">
                <h5 class="fw-bold mb-2">
                    Chưa có giấy tờ hồ sơ nào
                </h5>

                <p class="text-muted mb-3">
                    Bạn nên tải lên giấy đăng ký kinh doanh, giấy tờ thuế hoặc tài liệu pháp lý liên quan để admin kiểm tra hồ sơ.
                </p>

                <button type="button"
                        class="btn-orange"
                        data-bs-toggle="modal"
                        data-bs-target="#uploadCompanyDocumentModal">
                    Thêm giấy tờ đầu tiên
                </button>
            </div>
        @endif
    </div>

    Deal của doanh nghiệp
    <div class="company-card p-4 p-lg-5">
        <div class="d-flex flex-column flex-lg-row justify-content-between align-items-lg-center gap-3 mb-4">
            <div>
                <h3 class="fw-bold mb-1">
                    Deal của doanh nghiệp
                </h3>

                <p class="text-muted mb-0">
                    Danh sách deal bạn đã tạo cho công ty này. Deal chỉ hiển thị cho buyer khi admin duyệt thành published.
                </p>
            </div>

            @if ($company->verification_status === 'verified')
                <a href="{{ route('seller.deals.create') }}" class="btn-orange">
                    Tạo deal mới
                </a>
            @endif
        </div>

            @if ($deals->count() > 0)
                <div class="row g-4 row-cols-1 row-cols-md-2 row-cols-xl-3">
                    @foreach ($deals as $deal)
                        @php
                            $dealTypeLabel = match ($deal->deal_type) {
                                'fundraising' => 'Góp vốn',
                                'share_sale' => 'Mua cổ phần',
                                'acquisition' => 'Mua công ty',
                                default => 'Khác',
                            };

                            $dealStatusLabel = match ($deal->status) {
                                'draft' => 'Bản nháp',
                                'pending_review' => 'Chờ admin duyệt',
                                'published' => 'Đang hiển thị',
                                'rejected' => 'Bị từ chối',
                                'closed' => 'Đã đóng',
                                default => $deal->status,
                            };

                            $dealStatusClass = match ($deal->status) {
                                'pending_review' => 'status-pending',
                                'published' => 'status-published',
                                'rejected' => 'status-rejected',
                                'closed' => 'status-draft',
                                default => 'status-draft',
                            };
                        @endphp

                        <div class="col">
                            <div class="deal-card">
                                <div class="d-flex justify-content-between align-items-start gap-2 mb-3 pt-2">
                                    <span class="status-badge status-pending">
                                        {{ $dealTypeLabel }}
                                    </span>

                                    <span class="status-badge {{ $dealStatusClass }}">
                                        {{ $dealStatusLabel }}
                                    </span>
                                </div>

                                <h4 class="deal-title">
                                    {{ $deal->title }}
                                </h4>

                                <p class="deal-description">
                                    {{ $deal->short_description }}
                                </p>

                                @if ($deal->status === 'rejected')
                                    <div class="alert alert-danger rounded-4 border-0 mb-3">
                                        <strong>Lý do admin từ chối:</strong>

                                        <div class="mt-1">
                                            {{ $deal->rejection_reason ?? 'Admin chưa nhập lý do cụ thể.' }}
                                        </div>
                                    </div>
                                @endif

                            <div class="deal-price-box mb-3">
                                <p class="company-label">Định giá doanh nghiệp</p>

                                <p class="company-value">
                                    @if ($deal->valuation)
                                        {{ number_format($deal->valuation, 0, ',', '.') }} {{ $deal->currency ?? 'VND' }}
                                    @else
                                        Chưa công bố
                                    @endif
                                </p>
                            </div>

                            <div class="row g-3 mb-4">
                                <div class="col-6">
                                    <p class="company-label">Ngành</p>
                                    <p class="company-value">{{ $deal->industry ?? 'Đang cập nhật' }}</p>
                                </div>

                                <div class="col-6">
                                    <p class="company-label">Tỷ lệ</p>

                                    <p class="company-value">
                                        @if ($deal->equity_offered_percent)
                                            {{ $deal->equity_offered_percent }}%
                                        @else
                                            Đang cập nhật
                                        @endif
                                    </p>
                                </div>
                            </div>

                            <a href="{{ route('seller.deals.edit', $deal) }}" class="btn-soft-orange w-100">
                                Sửa deal
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="company-soft-box text-center">
                <h5 class="fw-bold mb-2">
                    Công ty này chưa có deal nào
                </h5>

                <p class="text-muted mb-3">
                    Sau khi hồ sơ công ty được admin duyệt, bạn có thể tạo deal đầu tiên.
                </p>

                @if ($company->verification_status === 'verified')
                    <a href="{{ route('seller.deals.create') }}" class="btn-orange">
                        Tạo deal mới
                    </a>
                @endif
            </div>
        @endif
    </div>
@endif

//model giấy tờ công ty
<div class="modal fade" id="uploadCompanyDocumentModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <form action="{{ route('seller.company.documents.store') }}"
              method="POST"
              enctype="multipart/form-data">
            @csrf

            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">
                        Thêm giấy tờ hồ sơ doanh nghiệp
                    </h5>

                    <button type="button"
                            class="btn-close"
                            data-bs-dismiss="modal">
                    </button>
                </div>

                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label fw-bold">
                            Loại giấy tờ
                        </label>

                        <select name="document_type" class="form-select" required>
                            <option value="">Chọn loại giấy tờ</option>

                            <option value="business_license">
                                Giấy đăng ký kinh doanh
                            </option>

                            <option value="tax_document">
                                Giấy tờ thuế / mã số thuế
                            </option>

                            <option value="financial_statement">
                                Báo cáo tài chính
                            </option>

                            <option value="ownership_document">
                                Giấy tờ sở hữu / góp vốn / ủy quyền
                            </option>

                            <option value="other">
                                Tài liệu khác
                            </option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">
                            File giấy tờ
                        </label>

                        <input type="file"
                               name="document_file"
                               class="form-control"
                               accept=".pdf,.jpg,.jpeg,.png,.doc,.docx"
                               required>

                        <div class="text-muted small mt-2">
                            Hỗ trợ PDF, ảnh, Word. Dung lượng tối đa nên để khoảng 10MB.
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">
                            Ghi chú
                        </label>

                        <textarea name="note"
                                  class="form-control"
                                  rows="3"
                                  placeholder="Ví dụ: Giấy đăng ký kinh doanh bản mới nhất."></textarea>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button"
                            class="btn btn-secondary"
                            data-bs-dismiss="modal">
                        Hủy
                    </button>

                    <button type="submit" class="btn-orange">
                        Tải giấy tờ lên
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>



{{-- Model thêm thông số của công ty --}}
<div class="modal fade" id="createCompanyFinancialModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <form action="{{ route('seller.company.financials.store') }}"
              method="POST">
            @csrf

            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">
                        Thêm dữ liệu tài chính doanh nghiệp
                    </h5>

                    <button type="button"
                            class="btn-close"
                            data-bs-dismiss="modal">
                    </button>
                </div>

                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label fw-bold">
                            Năm tài chính
                        </label>

                        <input type="number"
                               name="financial_year"
                               class="form-control"
                               value="{{ old('financial_year') }}"
                               min="1900"
                               max="2100"
                               placeholder="Ví dụ: 2024"
                               required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">
                            Doanh thu
                        </label>

                        <input type="number"
                               name="revenue"
                               class="form-control"
                               value="{{ old('revenue') }}"
                               min="0"
                               placeholder="Ví dụ: 5000000000">
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">
                            EBITDA
                        </label>

                        <input type="number"
                               name="ebitda"
                               class="form-control"
                               value="{{ old('ebitda') }}"
                               placeholder="Ví dụ: 800000000">
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">
                            Lợi nhuận ròng
                        </label>

                        <input type="number"
                               name="net_profit"
                               class="form-control"
                               value="{{ old('net_profit') }}"
                               placeholder="Ví dụ: 500000000">
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button"
                            class="btn btn-secondary"
                            data-bs-dismiss="modal">
                        Hủy
                    </button>

                    <button type="submit" class="btn-orange">
                        Lưu dữ liệu
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>


{{-- sơ đồ thông số doan nghiệp --}}
@php
    $financials = $company && $company->financials
        ? $company->financials->sortBy('financial_year')->values()
        : collect();

    $chartLabels = $financials->pluck('financial_year')->values();
    $chartRevenue = $financials->pluck('revenue')->map(fn($value) => (float) $value)->values();
    $chartEbitda = $financials->pluck('ebitda')->map(fn($value) => (float) $value)->values();
    $chartNetProfit = $financials->pluck('net_profit')->map(fn($value) => (float) $value)->values();
@endphp

@if (count($chartLabels) > 0)
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const canvas = document.getElementById('companyFinancialChart');

            if (!canvas) {
                return;
            }

            const labels = @json($chartLabels);
            const revenueData = @json($chartRevenue);
            const ebitdaData = @json($chartEbitda);
            const netProfitData = @json($chartNetProfit);

            new Chart(canvas, {
                type: 'bar',
                data: {
                    labels: labels,
                    datasets: [
                        {
                            label: 'Doanh thu',
                            data: revenueData,
                            backgroundColor: 'rgba(249, 115, 22, 0.75)',
                            borderColor: 'rgba(249, 115, 22, 1)',
                            borderWidth: 1,
                            borderRadius: 8
                        },
                        {
                            label: 'EBITDA',
                            data: ebitdaData,
                            backgroundColor: 'rgba(59, 130, 246, 0.75)',
                            borderColor: 'rgba(59, 130, 246, 1)',
                            borderWidth: 1,
                            borderRadius: 8
                        },
                        {
                            label: 'Lợi nhuận ròng',
                            data: netProfitData,
                            backgroundColor: 'rgba(34, 197, 94, 0.75)',
                            borderColor: 'rgba(34, 197, 94, 1)',
                            borderWidth: 1,
                            borderRadius: 8
                        }
                    ]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,

                    plugins: {
                        legend: {
                            position: 'top'
                        },
                        tooltip: {
                            callbacks: {
                                label: function (context) {
                                    const value = context.raw || 0;
                                    return context.dataset.label + ': ' + Number(value).toLocaleString('vi-VN') + ' VND';
                                }
                            }
                        }
                    },

                    scales: {
                        x: {
                            grid: {
                                display: false
                            },
                            title: {
                                display: true,
                                text: 'Năm tài chính'
                            }
                        },
                        y: {
                            beginAtZero: true,
                            ticks: {
                                callback: function (value) {
                                    return Number(value).toLocaleString('vi-VN');
                                }
                            },
                            title: {
                                display: true,
                                text: 'Giá trị'
                            }
                        }
                    }
                }
            });
        });
    </script>
@endif

@endsection