@extends('layouts.buyer')

@section('title', 'Hồ sơ chi tiết deal')
@section('page_title', 'Hồ sơ chi tiết deal')
@section('page_description', 'Thông tin chi tiết sau khi seller đã duyệt quyền xem hồ sơ')

@section('content')
@php
    $financialsForChart = $deal->company && $deal->company->financials
        ? $deal->company->financials->sortBy('financial_year')->values()
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
<style>
    .profile-card {
        background: #ffffff;
        border: 1px solid #fed7aa;
        border-radius: 24px;
        box-shadow: 0 10px 26px rgba(249, 115, 22, 0.07);
        padding: 28px;
        margin-bottom: 24px;
    }

    .profile-label {
        font-size: 13px;
        color: #9ca3af;
        margin-bottom: 4px;
    }

    .profile-value {
        font-weight: 700;
        color: #1f2937;
        margin-bottom: 0;
    }

    .profile-soft-box {
        background: #fff7ed;
        border: 1px solid #fed7aa;
        border-radius: 18px;
        padding: 18px;
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
</style>
{{-- Thông báo --}}
@php
    $myOffer = \App\Models\Offer::where('deal_id', $deal->id)
        ->where('buyer_id', auth()->id())
        ->latest()
        ->first();
@endphp

@if ($myOffer && $myOffer->status === 'pending')
    <div class="alert alert-warning">
        Bạn đã gửi offer và đang chờ seller phản hồi.
    </div>
@endif

<div class="mb-4">
    <a href="{{ route('buyer.home') }}" class="btn-soft-orange">
        Quay lại danh sách deal
    </a>
</div>


<div class="profile-card">
    <h4 class="fw-bold mb-4">
        Hồ sơ doanh nghiệp
    </h4>

    <div class="row g-4">
        <div class="col-md-6">
            <div class="profile-soft-box">
                <p class="profile-label">Tên pháp lý</p>
                <p class="profile-value">{{ $deal->company->legal_name ?? 'Chưa cập nhật' }}</p>
            </div>
        </div>

        <div class="col-md-6">
            <div class="profile-soft-box">
                <p class="profile-label">Mã số thuế</p>
                <p class="profile-value">{{ $deal->company->tax_code ?? 'Chưa cập nhật' }}</p>
            </div>
        </div>

        <div class="col-md-6">
            <div class="profile-soft-box">
                <p class="profile-label">Số đăng ký doanh nghiệp</p>
                <p class="profile-value">{{ $deal->company->business_registration_number ?? 'Chưa cập nhật' }}</p>
            </div>
        </div>

        <div class="col-md-6">
            <div class="profile-soft-box">
                <p class="profile-label">Người đại diện pháp luật</p>
                <p class="profile-value">{{ $deal->company->legal_representative_name ?? 'Chưa cập nhật' }}</p>
            </div>
        </div>

        <div class="col-12">
            <div class="profile-soft-box">
                <p class="profile-label">Địa chỉ</p>
                <p class="profile-value">{{ $deal->company->address ?? 'Chưa cập nhật' }}</p>
            </div>
        </div>

        <div class="col-12">
            <div class="profile-soft-box">
                <p class="profile-label">Mô tả doanh nghiệp</p>
                <p class="profile-value">{{ $deal->company->description ?? 'Chưa cập nhật' }}</p>
            </div>
        </div>
    </div>
</div>

<div class="profile-card">
    <div class="d-flex flex-column flex-lg-row justify-content-between align-items-lg-center gap-3 mb-4">
        <div>
            <h4 class="fw-bold mb-1">
                Dữ liệu tài chính doanh nghiệp
            </h4>

            <p class="text-muted mb-0">
                Doanh thu, EBITDA và lợi nhuận ròng của doanh nghiệp theo từng năm.
            </p>
        </div>
    </div>

    @if ($deal->company && $deal->company->financials && $deal->company->financials->count() > 0)
        <div class="row g-4">
            <div class="col-12">
                <div style="position: relative; width: 100%; height: 420px;">
                    <canvas id="buyerCompanyFinancialChart"></canvas>
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
        <div class="profile-soft-box text-center">
            Chưa có dữ liệu tài chính.
        </div>
    @endif
</div>


<div class="profile-card">
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
        <div class="profile-soft-box text-center">
            Deal này chưa có tài liệu Data Room.
        </div>
    @endif
</div>

{{-- Nút offer --}}
<a href="{{ route('buyer.deals.offers.create', $deal) }}" class="btn-orange">
    Tạo offer
</a>


@if (count($chartLabels) > 0)
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const canvas = document.getElementById('buyerCompanyFinancialChart');

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