@extends('layouts.admin')

@section('content')
<div class="container">
    <h2 class="mb-4">Kiểm duyệt Deal mới</h2>

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

    <div class="card shadow-sm">
        <div class="card-body">
            <table class="table table-hover align-middle">
                <thead class="table-light">
                    <tr>
                        <th>Tiêu đề Deal</th>
                        <th>Công ty</th>
                        <th>Loại Deal</th>
                        <th>Giá trị/Mục tiêu</th>
                        <th>Trạng thái</th>
                        <th>Hành động</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse($deals as $deal)
                        @php
                            $dealTypeLabel = match ($deal->deal_type) {
                                'fundraising' => 'Góp vốn',
                                'share_sale' => 'Mua cổ phần',
                                'acquisition' => 'Mua công ty',
                                default => 'Khác',
                            };
                        @endphp

                        <tr>
                            <td>
                                <strong>{{ $deal->title }}</strong><br>
                                <small class="text-muted">
                                    Ngành: {{ $deal->industry ?? 'Chưa cập nhật' }}
                                </small>
                            </td>

                            <td>
                                {{ $deal->company->legal_name ?? 'Chưa có công ty' }}
                            </td>

                            <td>
                                <span class="badge bg-secondary">
                                    {{ $dealTypeLabel }}
                                </span>
                            </td>

                            <td>
                                @if($deal->target_amount)
                                    {{ number_format($deal->target_amount, 0, ',', '.') }} {{ $deal->currency ?? 'VND' }}
                                @else
                                    Chưa nhập
                                @endif
                            </td>

                            <td>
                                <span class="badge bg-warning text-dark">
                                    Chờ admin duyệt
                                </span>
                            </td>

                            <td>
                                <div class="d-flex gap-2">
                                    <a href="{{ route('admin.deals.show', $deal) }}" class="btn btn-sm btn-outline-info">
                                        Chi tiết
                                    </a>

                                    <form action="{{ route('admin.deals.approve', $deal) }}" method="POST">
                                        @csrf
                                        <button type="submit" class="btn btn-sm btn-success">
                                            Duyệt
                                        </button>
                                    </form>

                                    <button type="button"
                                            class="btn btn-sm btn-danger"
                                            data-bs-toggle="modal"
                                            data-bs-target="#rejectDeal{{ $deal->id }}">
                                        Từ chối
                                    </button>
                                </div>
                            </td>
                        </tr>

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
                                                      rows="3"
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
                    @empty
                        <tr>
                            <td colspan="6" class="text-center py-4">
                                Không có deal nào đang chờ duyệt.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection