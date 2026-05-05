@extends('layouts.buyer')

@section('title', 'Chi tiết deal thành công')
@section('page_title', 'Chi tiết deal thành công')
@section('page_description', 'Thông tin deal, seller và offer đã được chấp nhận')

@section('content')

<style>
    .success-card {
        background: #ffffff;
        border: 1px solid #fed7aa;
        border-radius: 24px;
        box-shadow: 0 10px 26px rgba(249, 115, 22, 0.07);
        padding: 28px;
        margin-bottom: 24px;
    }

    .success-soft-box {
        background: #fff7ed;
        border: 1px solid #fed7aa;
        border-radius: 18px;
        padding: 18px;
        height: 100%;
    }

    .success-label {
        font-size: 13px;
        color: #9ca3af;
        margin-bottom: 4px;
    }

    .success-value {
        font-weight: 800;
        color: #1f2937;
        margin-bottom: 0;
        word-break: break-word;
    }

    .summary-box {
        background: linear-gradient(135deg, #fff7ed, #ffffff);
        border: 1px solid #fed7aa;
        border-radius: 22px;
        padding: 22px;
        height: 100%;
    }

    .summary-label {
        font-size: 14px;
        color: #9ca3af;
        font-weight: 700;
        margin-bottom: 6px;
    }

    .summary-value {
        font-size: 22px;
        font-weight: 900;
        color: #1f2937;
        margin-bottom: 0;
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
</style>

@if (session('success'))
    <div class="alert alert-success rounded-4 border-0 shadow-sm mb-4">
        {{ session('success') }}
    </div>
@endif

@if (session('error'))
    <div class="alert alert-danger rounded-4 border-0 shadow-sm mb-4">
        {{ session('error') }}
    </div>
@endif

<div class="mb-4">
    <a href="{{ route('buyer.successful_deals.index') }}" class="btn-soft-orange">
        Quay lại Deal thành công
    </a>
</div>

<div class="success-card">
    <div class="d-flex flex-column flex-lg-row justify-content-between align-items-lg-start gap-3 mb-4">
        <div>
            <h4 class="fw-bold mb-2">
                Tổng quan deal thành công
            </h4>

            <p class="text-muted mb-0">
                Offer của bạn đã được seller chấp nhận cho deal này.
            </p>
        </div>

        <span class="badge bg-success fs-6 rounded-pill px-3 py-2">
            Đã chấp nhận
        </span>
    </div>

    <div class="row g-4">
        <div class="col-md-4">
            <div class="summary-box">
                <p class="summary-label">Số tiền bạn offer</p>
                <p class="summary-value">
                    {{ number_format($offer->amount ?? 0, 0, ',', '.') }}
                    {{ $offer->currency ?? $deal->currency ?? 'VND' }}
                </p>
            </div>
        </div>

        <div class="col-md-4">
            <div class="summary-box">
                <p class="summary-label">Tỷ lệ cổ phần</p>
                <p class="summary-value">
                    @if (!is_null($offer->equity_percent))
                        {{ $offer->equity_percent }}%
                    @else
                        Không áp dụng
                    @endif
                </p>
            </div>
        </div>

        <div class="col-md-4">
            <div class="summary-box">
                <p class="summary-label">Loại giao dịch</p>
                <p class="summary-value">
                    {{ $dealTypeLabel }}
                </p>
            </div>
        </div>
    </div>
</div>

<div class="success-card">
    <h4 class="fw-bold mb-4">
        Thông tin offer
    </h4>

    <div class="row g-4">
        <div class="col-md-4">
            <div class="success-soft-box">
                <p class="success-label">Ngày gửi offer</p>
                <p class="success-value">
                    {{ $offer->created_at?->format('d/m/Y H:i') }}
                </p>
            </div>
        </div>

        <div class="col-md-4">
            <div class="success-soft-box">
                <p class="success-label">Ngày seller chấp nhận</p>
                <p class="success-value">
                    {{ $offer->updated_at?->format('d/m/Y H:i') }}
                </p>
            </div>
        </div>

        <div class="col-md-4">
            <div class="success-soft-box">
                <p class="success-label">Trạng thái offer</p>
                <p class="success-value">
                    Đã chấp nhận
                </p>
            </div>
        </div>

        <div class="col-12">
            <div class="success-soft-box">
                <p class="success-label">Ghi chú offer</p>
                <p class="success-value">
                    {{ $offer->note ?? 'Bạn không để lại ghi chú cho offer này.' }}
                </p>
            </div>
        </div>
    </div>
</div>

<div class="success-card">
    <h4 class="fw-bold mb-4">
        Thông tin deal
    </h4>

    <div class="row g-4">
        <div class="col-md-6">
            <div class="success-soft-box">
                <p class="success-label">Tên deal</p>
                <p class="success-value">
                    {{ $deal->title ?? 'Chưa cập nhật' }}
                </p>
            </div>
        </div>

        <div class="col-md-6">
            <div class="success-soft-box">
                <p class="success-label">Loại deal</p>
                <p class="success-value">
                    {{ $dealTypeLabel }}
                </p>
            </div>
        </div>

        <div class="col-md-4">
            <div class="success-soft-box">
                <p class="success-label">Tổng giá trị deal</p>
                <p class="success-value">
                    @if (!is_null($deal->target_amount))
                        {{ number_format($deal->target_amount, 0, ',', '.') }}
                        {{ $deal->currency ?? 'VND' }}
                    @else
                        Chưa cập nhật
                    @endif
                </p>
            </div>
        </div>

        <div class="col-md-4">
            <div class="success-soft-box">
                <p class="success-label">Số tiền đã được seller đồng ý</p>
                <p class="success-value">
                    {{ number_format($deal->committed_amount ?? 0, 0, ',', '.') }}
                    {{ $deal->currency ?? 'VND' }}
                </p>
            </div>
        </div>

        <div class="col-md-4">
            <div class="success-soft-box">
                <p class="success-label">Số tiền còn lại</p>
                <p class="success-value">
                    {{ number_format($remainingAmount, 0, ',', '.') }}
                    {{ $deal->currency ?? 'VND' }}
                </p>
            </div>
        </div>

        @if (in_array($deal->deal_type, ['share_sale', 'fundraising']))
            <div class="col-md-4">
                <div class="success-soft-box">
                    <p class="success-label">Tổng tỷ lệ cổ phần</p>
                    <p class="success-value">
                        {{ $deal->equity_offered_percent ?? 0 }}%
                    </p>
                </div>
            </div>

            <div class="col-md-4">
                <div class="success-soft-box">
                    <p class="success-label">Tỷ lệ cổ phần đã đồng ý</p>
                    <p class="success-value">
                        {{ $acceptedEquityPercent }}%
                    </p>
                </div>
            </div>

            <div class="col-md-4">
                <div class="success-soft-box">
                    <p class="success-label">Tỷ lệ cổ phần còn lại</p>
                    <p class="success-value">
                        {{ $remainingEquityPercent }}%
                    </p>
                </div>
            </div>
        @endif

        <div class="col-md-4">
            <div class="success-soft-box">
                <p class="success-label">Ngành nghề</p>
                <p class="success-value">
                    {{ $deal->industry ?? 'Chưa cập nhật' }}
                </p>
            </div>
        </div>

        <div class="col-md-4">
            <div class="success-soft-box">
                <p class="success-label">Khu vực</p>
                <p class="success-value">
                    {{ $deal->location ?? 'Chưa cập nhật' }}
                </p>
            </div>
        </div>

        <div class="col-md-4">
            <div class="success-soft-box">
                <p class="success-label">Trạng thái deal</p>
                <p class="success-value">
                    {{ $deal->status ?? 'Chưa cập nhật' }}
                </p>
            </div>
        </div>

        @if (!empty($deal->short_description))
            <div class="col-12">
                <div class="success-soft-box">
                    <p class="success-label">Mô tả ngắn</p>
                    <p class="success-value">
                        {{ $deal->short_description }}
                    </p>
                </div>
            </div>
        @endif

        @if (!empty($deal->full_description))
            <div class="col-12">
                <div class="success-soft-box">
                    <p class="success-label">Mô tả chi tiết</p>
                    <p class="success-value">
                        {{ $deal->full_description }}
                    </p>
                </div>
            </div>
        @endif
    </div>
</div>

<div class="success-card">
    <h4 class="fw-bold mb-4">
        Thông tin seller
    </h4>

    <div class="row g-4">
        <div class="col-md-4">
            <div class="success-soft-box">
                <p class="success-label">Tên seller</p>
                <p class="success-value">
                    {{ $offer->seller->name ?? $deal->seller->name ?? 'Chưa cập nhật' }}
                </p>
            </div>
        </div>

        <div class="col-md-4">
            <div class="success-soft-box">
                <p class="success-label">Email seller</p>
                <p class="success-value">
                    {{ $offer->seller->email ?? $deal->seller->email ?? 'Chưa cập nhật' }}
                </p>
            </div>
        </div>

        <div class="col-md-4">
            <div class="success-soft-box">
                <p class="success-label">Số điện thoại seller</p>
                <p class="success-value">
                    {{ $offer->seller->phone ?? $deal->seller->phone ?? 'Chưa cập nhật' }}
                </p>
            </div>
        </div>
    </div>
</div>

<div class="success-card">
    <h4 class="fw-bold mb-4">
        Hồ sơ doanh nghiệp
    </h4>

    <div class="row g-4">
        <div class="col-md-6">
            <div class="success-soft-box">
                <p class="success-label">Tên pháp lý</p>
                <p class="success-value">
                    {{ $deal->company->legal_name ?? 'Chưa cập nhật' }}
                </p>
            </div>
        </div>

        <div class="col-md-6">
            <div class="success-soft-box">
                <p class="success-label">Mã số thuế</p>
                <p class="success-value">
                    {{ $deal->company->tax_code ?? 'Chưa cập nhật' }}
                </p>
            </div>
        </div>

        <div class="col-md-6">
            <div class="success-soft-box">
                <p class="success-label">Số đăng ký doanh nghiệp</p>
                <p class="success-value">
                    {{ $deal->company->business_registration_number ?? 'Chưa cập nhật' }}
                </p>
            </div>
        </div>

        <div class="col-md-6">
            <div class="success-soft-box">
                <p class="success-label">Người đại diện pháp luật</p>
                <p class="success-value">
                    {{ $deal->company->legal_representative_name ?? 'Chưa cập nhật' }}
                </p>
            </div>
        </div>

        <div class="col-12">
            <div class="success-soft-box">
                <p class="success-label">Địa chỉ</p>
                <p class="success-value">
                    {{ $deal->company->address ?? 'Chưa cập nhật' }}
                </p>
            </div>
        </div>

        <div class="col-12">
            <div class="success-soft-box">
                <p class="success-label">Mô tả doanh nghiệp</p>
                <p class="success-value">
                    {{ $deal->company->description ?? 'Chưa cập nhật' }}
                </p>
            </div>
        </div>
    </div>
</div>

<div class="success-card">
    <h4 class="fw-bold mb-4">
        Dữ liệu tài chính doanh nghiệp
    </h4>

    @if ($deal->company && $deal->company->financials && $deal->company->financials->count() > 0)
        <div class="row g-4">
            <div class="col-12">
                <div style="position: relative; width: 100%; height: 420px;">
                    <canvas id="successfulDealFinancialChart"></canvas>
                </div>
            </div>

            <div class="col-12">
                <div class="table-responsive mt-3">
                    <table class="table table-hover align-middle">
                        <thead class="table-light">
                            <tr>
                                <th>Năm</th>
                                <th>Doanh thu</th>
                                <th>EBITDA</th>
                                <th>Lợi nhuận ròng</th>
                            </tr>
                        </thead>

                        <tbody>
                            @foreach ($deal->company->financials->sortByDesc('financial_year') as $financial)
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
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    @else
        <div class="success-soft-box text-center">
            Chưa có dữ liệu tài chính.
        </div>
    @endif
</div>

<div class="success-card">
    <h4 class="fw-bold mb-4">
        Tài liệu Data Room
    </h4>

    @if ($deal->dataRoomFiles && $deal->dataRoomFiles->count() > 0)
        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead class="table-light">
                    <tr>
                        <th>Loại tài liệu</th>
                        <th>Tên file</th>
                        <th>Quyền tải</th>
                        <th class="text-end">Hành động</th>
                    </tr>
                </thead>

                <tbody>
                    @foreach ($deal->dataRoomFiles as $file)
                        <tr>
                            <td>{{ $file->document_type }}</td>
                            <td>{{ $file->file_name }}</td>
                            <td>
                                @if ($file->allow_download)
                                    <span class="badge bg-success">Cho tải</span>
                                @else
                                    <span class="badge bg-secondary">Chỉ xem</span>
                                @endif
                            </td>

                            <td class="text-end">
                                <a href="{{ asset('storage/' . $file->file_path) }}"
                                   target="_blank"
                                   class="btn btn-sm btn-outline-info">
                                    Xem file
                                </a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @else
        <div class="success-soft-box text-center">
            Deal này chưa có tài liệu Data Room.
        </div>
    @endif
</div>

@if (count($chartLabels) > 0)
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const canvas = document.getElementById('successfulDealFinancialChart');

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