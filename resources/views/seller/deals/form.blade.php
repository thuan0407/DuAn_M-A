@extends('layouts.seller')

@section('title', $mode === 'create' ? 'Tạo deal mới' : 'Sửa deal')
@section('page_title', $mode === 'create' ? 'Tạo deal mới' : 'Sửa deal')
@section('page_description', 'Deal tạo mới hoặc cập nhật đều cần admin duyệt trước khi hiển thị cho buyer')

@section('content')

<style>
    .deal-form-card {
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

    .form-control,
    .form-select {
        border-radius: 14px;
        border: 1px solid #fed7aa;
        min-height: 48px;
    }

    .form-control:focus,
    .form-select:focus {
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

    .deal-type-section {
        display: none;
        margin-top: 24px;
        padding: 22px;
        background: #fff7ed;
        border: 1px solid #fed7aa;
        border-radius: 20px;
    }

    .deal-type-title {
        font-size: 18px;
        font-weight: 900;
        color: #1f2937;
        margin-bottom: 6px;
    }

    .deal-type-desc {
        color: #6b7280;
        font-size: 14px;
        margin-bottom: 20px;
    }

    .form-help {
        font-size: 13px;
        color: #6b7280;
        margin-top: 6px;
    }
</style>

<div class="deal-form-card">
    <div class="alert alert-warning rounded-4 border-0 mb-4">
        Deal sau khi tạo hoặc cập nhật sẽ ở trạng thái <strong>chờ admin duyệt</strong>. Buyer chỉ thấy deal khi admin duyệt và chuyển trạng thái thành published.
    </div>

    @if ($errors->any())
        <div class="alert alert-danger rounded-4 border-0 mb-4">
            Vui lòng kiểm tra lại thông tin deal. Có trường đang thiếu hoặc nhập sai.
        </div>
    @endif

    <form method="POST"
          action="{{ $mode === 'create' ? route('seller.deals.store') : route('seller.deals.update', $deal) }}">
        @csrf

        @if ($mode === 'edit')
            @method('PUT')
        @endif

        {{-- Thông tin chung --}}
        <div class="row g-4">
            <div class="col-md-8">
                <label class="form-label">Tên deal</label>
                <input type="text"
                       name="title"
                       class="form-control @error('title') is-invalid @enderror"
                       value="{{ old('title', $deal->title) }}"
                       placeholder="Ví dụ: Gọi vốn mở rộng xưởng sản xuất nội thất"
                       required>

                @error('title')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="col-md-4">
                <label class="form-label">Loại deal</label>
                <select id="deal_type"
                        name="deal_type"
                        class="form-select @error('deal_type') is-invalid @enderror"
                        required>
                    <option value="">Chọn loại deal</option>

                    <option value="acquisition" {{ old('deal_type', $deal->deal_type) === 'acquisition' ? 'selected' : '' }}>
                        Mua công ty
                    </option>

                    <option value="share_sale" {{ old('deal_type', $deal->deal_type) === 'share_sale' ? 'selected' : '' }}>
                        Mua cổ phần
                    </option>

                    <option value="fundraising" {{ old('deal_type', $deal->deal_type) === 'fundraising' ? 'selected' : '' }}>
                        Góp vốn
                    </option>
                </select>

                @error('deal_type')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="col-md-6">
                <label class="form-label">Công ty</label>
                <input type="text"
                       class="form-control"
                       value="{{ $company->legal_name }}"
                       disabled>
            </div>

            <div class="col-md-6">
                <label class="form-label">Ngành nghề</label>
                <input type="text"
                       name="industry"
                       class="form-control @error('industry') is-invalid @enderror"
                       value="{{ old('industry', $deal->industry ?? $company->industry) }}"
                       placeholder="Ví dụ: Sản xuất nội thất"
                       required>

                @error('industry')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="col-md-6">
                <label class="form-label">Khu vực</label>
                <input type="text"
                       name="location"
                       class="form-control @error('location') is-invalid @enderror"
                       value="{{ old('location', $deal->location) }}"
                       placeholder="Ví dụ: TP. Hồ Chí Minh">

                @error('location')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="col-md-6">
                <label class="form-label">Tiền tệ</label>
                <input type="text"
                       name="currency"
                       class="form-control @error('currency') is-invalid @enderror"
                       value="{{ old('currency', $deal->currency ?? '') }}"
                       placeholder="VND">

                @error('currency')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
        </div>

        {{-- Riêng cho mua công ty --}}
        <div id="acquisitionFields" class="deal-type-section" data-deal-type="acquisition">
            <h4 class="deal-type-title">Thông tin mua công ty</h4>
            <p class="deal-type-desc">
                Dùng cho trường hợp seller muốn bán toàn bộ công ty hoặc bán quyền kiểm soát doanh nghiệp.
            </p>

            <div class="row g-4">
                <div class="col-md-4">
                    <label class="form-label">Giá mong muốn bán công ty</label>
                    <input type="number"
                           name="target_amount"
                           class="form-control @error('target_amount') is-invalid @enderror"
                           value="{{ old('target_amount', $deal->target_amount) }}"
                           placeholder="Ví dụ: 10000000000"
                           min="0">

                    @error('target_amount')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror

                    <div class="form-help">Đây là số tiền seller mong muốn nhận khi bán công ty.</div>
                </div>

                <div class="col-md-4">
                    <label class="form-label">Định giá doanh nghiệp</label>
                    <input type="number"
                           name="valuation"
                           class="form-control @error('valuation') is-invalid @enderror"
                           value="{{ old('valuation', $deal->valuation) }}"
                           placeholder="Ví dụ: 10000000000"
                           min="0">

                    @error('valuation')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror

                    <div class="form-help">Có thể bằng hoặc khác giá bán mong muốn.</div>
                </div>

                <div class="col-md-4">
                    <label class="form-label">Tỷ lệ chuyển nhượng (%)</label>
                    <input type="number"
                           step="0.01"
                           name="equity_offered_percent"
                           class="form-control @error('equity_offered_percent') is-invalid @enderror"
                           value="{{ old('equity_offered_percent', $deal->exists ? $deal->equity_offered_percent : 100) }}"
                           placeholder="Ví dụ: 100"
                           min="0"
                           max="100">

                    @error('equity_offered_percent')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror

                    <div class="form-help">Nếu bán toàn bộ công ty thì nhập 100%.</div>
                </div>
            </div>
        </div>

        {{-- Riêng cho mua cổ phần --}}
        <div id="shareSaleFields" class="deal-type-section" data-deal-type="share_sale">
            <h4 class="deal-type-title">Thông tin mua cổ phần</h4>
            <p class="deal-type-desc">
                Dùng cho trường hợp seller muốn bán một phần cổ phần hoặc phần vốn góp trong công ty.
            </p>

            <div class="row g-4">
                <div class="col-md-4">
                    <label class="form-label">Tổng giá trị cổ phần chào bán</label>
                    <input type="number"
                           name="target_amount"
                           class="form-control @error('target_amount') is-invalid @enderror"
                           value="{{ old('target_amount', $deal->target_amount) }}"
                           placeholder="Ví dụ: 5000000000"
                           min="0">

                    @error('target_amount')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror

                    <div class="form-help">Tổng số tiền seller muốn thu về từ phần cổ phần chào bán.</div>
                </div>

                <div class="col-md-4">
                    <label class="form-label">Định giá doanh nghiệp</label>
                    <input type="number"
                           name="valuation"
                           class="form-control @error('valuation') is-invalid @enderror"
                           value="{{ old('valuation', $deal->valuation) }}"
                           placeholder="Ví dụ: 25000000000"
                           min="0">

                    @error('valuation')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror

                    <div class="form-help">Định giá làm cơ sở tính giá trị cổ phần.</div>
                </div>

                <div class="col-md-4">
                    <label class="form-label">Tổng tỷ lệ cổ phần chào bán (%)</label>
                    <input type="number"
                           step="0.01"
                           name="equity_offered_percent"
                           class="form-control @error('equity_offered_percent') is-invalid @enderror"
                           value="{{ old('equity_offered_percent', $deal->equity_offered_percent) }}"
                           placeholder="Ví dụ: 20"
                           min="0"
                           max="100">

                    @error('equity_offered_percent')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror

                    <div class="form-help">Ví dụ bán 20% cổ phần công ty.</div>
                </div>

                <div class="col-md-4">
                    <label class="form-label">Số tiền mua tối thiểu</label>
                    <input type="number"
                           name="min_ticket"
                           class="form-control @error('min_ticket') is-invalid @enderror"
                           value="{{ old('min_ticket', $deal->min_ticket) }}"
                           placeholder="Ví dụ: 500000000"
                           min="0">

                    @error('min_ticket')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror

                    <div class="form-help">Số tiền tối thiểu mỗi buyer/investor cần mua.</div>
                </div>

                <div class="col-md-4">
                    <label class="form-label">Tỷ lệ cổ phần tối thiểu (%)</label>
                    <input type="number"
                           step="0.01"
                           name="min_equity_percent"
                           class="form-control @error('min_equity_percent') is-invalid @enderror"
                           value="{{ old('min_equity_percent', $deal->min_equity_percent) }}"
                           placeholder="Ví dụ: 2"
                           min="0"
                           max="100">

                    @error('min_equity_percent')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror

                    <div class="form-help">Tỷ lệ cổ phần tối thiểu cho mỗi người mua.</div>
                </div>

                <div class="col-md-4">
                    <label class="form-label">Cho phép nhiều investor?</label>
                    <select name="allow_multiple_investors"
                            class="form-select @error('allow_multiple_investors') is-invalid @enderror">
                        <option value="0" {{ old('allow_multiple_investors', $deal->allow_multiple_investors) == 0 ? 'selected' : '' }}>
                            Không
                        </option>
                        <option value="1" {{ old('allow_multiple_investors', $deal->allow_multiple_investors) == 1 ? 'selected' : '' }}>
                            Có
                        </option>
                    </select>

                    @error('allow_multiple_investors')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror

                    <div class="form-help">Nếu có, nhiều investor có thể cùng mua phần cổ phần chào bán.</div>
                </div>
            </div>
        </div>

        {{-- Riêng cho góp vốn --}}
        <div id="fundraisingFields" class="deal-type-section" data-deal-type="fundraising">
            <h4 class="deal-type-title">Thông tin góp vốn</h4>
            <p class="deal-type-desc">
                Dùng cho trường hợp công ty muốn gọi vốn từ một hoặc nhiều investor.
            </p>

            <div class="row g-4">
                <div class="col-md-4">
                    <label class="form-label">Số tiền cần gọi vốn</label>
                    <input type="number"
                           name="target_amount"
                           class="form-control @error('target_amount') is-invalid @enderror"
                           value="{{ old('target_amount', $deal->target_amount) }}"
                           placeholder="Ví dụ: 3000000000"
                           min="0">

                    @error('target_amount')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror

                    <div class="form-help">Tổng số vốn doanh nghiệp muốn gọi trong vòng này.</div>
                </div>

                <div class="col-md-4">
                    <label class="form-label">Định giá doanh nghiệp</label>
                    <input type="number"
                           name="valuation"
                           class="form-control @error('valuation') is-invalid @enderror"
                           value="{{ old('valuation', $deal->valuation) }}"
                           placeholder="Ví dụ: 20000000000"
                           min="0">

                    @error('valuation')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror

                    <div class="form-help">Định giá dùng để tham khảo khi gọi vốn.</div>
                </div>

                <div class="col-md-4">
                    <label class="form-label">Tỷ lệ cổ phần dành cho vòng gọi vốn (%)</label>
                    <input type="number"
                           step="0.01"
                           name="equity_offered_percent"
                           class="form-control @error('equity_offered_percent') is-invalid @enderror"
                           value="{{ old('equity_offered_percent', $deal->equity_offered_percent) }}"
                           placeholder="Ví dụ: 15"
                           min="0"
                           max="100">

                    @error('equity_offered_percent')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror

                    <div class="form-help">Ví dụ gọi vốn đổi lấy 15% cổ phần.</div>
                </div>

                <div class="col-md-4">
                    <label class="form-label">Số tiền góp tối thiểu</label>
                    <input type="number"
                           name="min_ticket"
                           class="form-control @error('min_ticket') is-invalid @enderror"
                           value="{{ old('min_ticket', $deal->min_ticket) }}"
                           placeholder="Ví dụ: 300000000"
                           min="0">

                    @error('min_ticket')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror

                    <div class="form-help">Số tiền tối thiểu mỗi investor cần góp.</div>
                </div>

                <div class="col-md-4">
                    <label class="form-label">Tỷ lệ góp vốn tối thiểu (%)</label>
                    <input type="number"
                           step="0.01"
                           name="min_equity_percent"
                           class="form-control @error('min_equity_percent') is-invalid @enderror"
                           value="{{ old('min_equity_percent', $deal->min_equity_percent) }}"
                           placeholder="Ví dụ: 1"
                           min="0"
                           max="100">

                    @error('min_equity_percent')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror

                    <div class="form-help">Tỷ lệ cổ phần tối thiểu tương ứng cho mỗi investor.</div>
                </div>

                <div class="col-md-4">
                    <label class="form-label">Cho phép nhiều investor?</label>
                    <select name="allow_multiple_investors"
                            class="form-select @error('allow_multiple_investors') is-invalid @enderror">
                        <option value="1" {{ old('allow_multiple_investors', $deal->allow_multiple_investors ?? 1) == 1 ? 'selected' : '' }}>
                            Có
                        </option>
                        <option value="0" {{ old('allow_multiple_investors', $deal->allow_multiple_investors ?? 1) == 0 ? 'selected' : '' }}>
                            Không
                        </option>
                    </select>

                    @error('allow_multiple_investors')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror

                    <div class="form-help">Gọi vốn thường nên cho phép nhiều investor cùng tham gia.</div>
                </div>
            </div>
        </div>

        {{-- Mô tả chung --}}
        <div class="row g-4 mt-1">
            <div class="col-12">
                <label class="form-label">Mô tả ngắn</label>
                <textarea name="short_description"
                          class="form-control @error('short_description') is-invalid @enderror"
                          rows="3"
                          placeholder="Mô tả ngắn hiển thị công khai cho buyer"
                          required>{{ old('short_description', $deal->short_description) }}</textarea>

                @error('short_description')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="col-12">
                <label class="form-label">Mô tả chi tiết</label>
                <textarea name="description"
                          class="form-control @error('description') is-invalid @enderror"
                          rows="6"
                          placeholder="Mô tả chi tiết hơn về cơ hội đầu tư, lý do gọi vốn hoặc chuyển nhượng">{{ old('description', $deal->description) }}</textarea>

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
                {{ $mode === 'create' ? 'Tạo deal' : 'Cập nhật deal' }}
            </button>
        </div>
    </form>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const dealTypeSelect = document.getElementById('deal_type');
        const sections = document.querySelectorAll('.deal-type-section');

        function setSectionState(section, isActive) {
            section.style.display = isActive ? 'block' : 'none';

            const fields = section.querySelectorAll('input, select, textarea');

            fields.forEach(function (field) {
                field.disabled = !isActive;
            });
        }

        function toggleDealFields() {
            const selectedType = dealTypeSelect.value;

            sections.forEach(function (section) {
                const sectionType = section.dataset.dealType;
                setSectionState(section, sectionType === selectedType);
            });
        }

        dealTypeSelect.addEventListener('change', toggleDealFields);

        toggleDealFields();
    });
</script>

@endsection