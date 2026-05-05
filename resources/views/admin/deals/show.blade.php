@extends('layouts.admin')

@section('content')
<div class="container pb-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <a href="{{ route('admin.deals.pending') }}" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left"></i> Quay lại danh sách
        </a>
<div class="d-flex gap-2">
    @if ($deal->status === 'pending_review')
        <form action="{{ route('admin.deals.approve', $deal) }}" method="POST">
            @csrf
            <button type="submit" class="btn btn-success">
                Duyệt Deal này
            </button>
        </form>

        <button class="btn btn-danger"
                data-bs-toggle="modal"
                data-bs-target="#rejectDeal{{ $deal->id }}">
            Từ chối
        </button>
    @else
    @php
        $statusLabel = match ($deal->status) {
            'draft' => 'Bản nháp',
            'pending_review' => 'Chờ admin duyệt',
            'approved' => 'Đã duyệt',
            'published' => 'Đang hoạt động',
            'negotiating' => 'Đang giao dịch',
            'closed' => 'Đã kết thúc',
            'rejected' => 'Đã bị từ chối',
            'cancelled' => 'Đã hủy',
            default => $deal->status,
        };

        $statusClass = match ($deal->status) {
            'draft' => 'bg-secondary',
            'pending_review' => 'bg-warning text-dark',
            'approved' => 'bg-info',
            'published' => 'bg-success',
            'negotiating' => 'bg-primary',
            'closed' => 'bg-secondary',
            'rejected' => 'bg-danger',
            'cancelled' => 'bg-dark',
            default => 'bg-warning text-dark',
        };
    @endphp

        <span class="badge {{ $statusClass }} px-3 py-2">
            {{ $statusLabel }}
        </span>
    @endif
</div>
    </div>

    <div class="row">
        {{-- Cột trái: Thông tin Deal & Tài chính --}}
        <div class="col-md-8">
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-white fw-bold">Thông tin chi tiết Deal</div>
                <div class="card-body">
                    <h3 class="text-primary">{{ $deal->title }}</h3>
                    <p class="text-muted">{{ $deal->short_description }}</p>
                    <hr>
                    <div class="row">
                        <div class="col-6 mb-3">
                            <label class="text-muted small">Loại hình:</label>
                            <div class="fw-bold">{{ strtoupper($deal->deal_type) }}</div>
                        </div>
                        <div class="col-6 mb-3">
                            <label class="text-muted small">Giá trị mục tiêu:</label>
                            <div class="fw-bold text-danger">{{ number_format($deal->target_amount) }} {{ $deal->currency }}</div>
                        </div>
                        <div class="col-6 mb-3">
                            <label class="text-muted small">Ngành nghề:</label>
                            <div>{{ $deal->industry }}</div>
                        </div>
                        <div class="col-6 mb-3">
                            <label class="text-muted small">Vị trí:</label>
                            <div>{{ $deal->location }}</div>
                        </div>
                    </div>
                    <div class="mt-3">
                        <label class="fw-bold">Mô tả đầy đủ:</label>
                        <p>{!! nl2br(e($deal->full_description)) !!}</p>
                    </div>
                </div>
            </div>

            {{-- Bảng chỉ số tài chính của công ty --}}
            <div class="card shadow-sm">
                <div class="card-header bg-white fw-bold">
                    Chỉ số tài chính doanh nghiệp qua các năm
                </div>

                <div class="card-body p-0">
                    <table class="table table-striped mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Năm</th>
                                <th>Doanh thu</th>
                                <th>EBITDA</th>
                                <th>LN ròng</th>
                            </tr>
                        </thead>

                        <tbody>
                            @forelse(($deal->company?->financials ?? collect()) as $fin)
                                <tr>
                                    <td>{{ $fin->financial_year }}</td>

                                    <td>
                                        @if (!is_null($fin->revenue))
                                            {{ number_format($fin->revenue, 0, ',', '.') }}
                                        @else
                                            Chưa nhập
                                        @endif
                                    </td>

                                    <td>
                                        @if (!is_null($fin->ebitda))
                                            {{ number_format($fin->ebitda, 0, ',', '.') }}
                                        @else
                                            Chưa nhập
                                        @endif
                                    </td>

                                    <td>
                                        @if (!is_null($fin->net_profit))
                                            {{ number_format($fin->net_profit, 0, ',', '.') }}
                                        @else
                                            Chưa nhập
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="text-center py-3">
                                        Công ty chưa có dữ liệu tài chính.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        {{-- Cột phải: Thông tin Công ty sở hữu --}}
        <div class="col-md-4">
            <div class="card shadow-sm border-info">
                <div class="card-header bg-info text-white fw-bold">Hồ sơ Doanh nghiệp</div>
                <div class="card-body">
                    <h5>{{ $deal->company->legal_name }}</h5>
                    <p class="small mb-1"><strong>MST:</strong> {{ $deal->company->tax_code }}</p>
                    <p class="small"><strong>Trạng thái xác minh:</strong> 
                        <span class="badge {{ $deal->company->verification_status == 'verified' ? 'bg-success' : 'bg-warning' }}">
                            {{ $deal->company->verification_status }}
                        </span>
                    </p>
                    <a href="{{ route('admin.companies.show', $deal->company_id) }}" class="btn btn-sm btn-outline-info w-100 mt-2">
                        Xem hồ sơ công ty đầy đủ
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>


<div class="modal fade" id="rejectDeal{{ $deal->id }}" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <form action="{{ route('admin.deals.reject', $deal) }}" method="POST">
            @csrf

            <div class="modal-content">
                <div class="modal-header bg-danger text-white">
                    <h5 class="modal-title">
                        Từ chối Deal: {{ $deal->title }}
                    </h5>

                    <button type="button"
                            class="btn-close"
                            data-bs-dismiss="modal">
                    </button>
                </div>

                <div class="modal-body">
                    <label class="form-label">
                        Lý do từ chối gửi cho Seller
                    </label>

                    <textarea name="reason"
                              class="form-control"
                              rows="4"
                              placeholder="Ví dụ: Deal thiếu thông tin tài chính, định giá chưa rõ ràng hoặc công ty chưa đủ điều kiện đăng deal."
                              required></textarea>
                </div>

                <div class="modal-footer">
                    <button type="button"
                            class="btn btn-secondary"
                            data-bs-dismiss="modal">
                        Hủy
                    </button>

                    <button type="submit" class="btn btn-danger">
                        Xác nhận từ chối
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection