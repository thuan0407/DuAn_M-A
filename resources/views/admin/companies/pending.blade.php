@extends('layouts.admin')

@section('content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="mb-0">Danh sách Công ty chờ duyệt</h2>
        <span class="badge bg-primary">{{ $companies->count() }} hồ sơ đang chờ</span>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="card shadow-sm">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>Tên pháp nhân</th>
                            <th>Mã số thuế</th>
                            <th>Ngành nghề</th>
                            <th>Ngày gửi</th>
                            <th class="text-end">Hành động</th>
                        </tr>
                    </thead>
                    <!-- Chèn vào trước thẻ </body>  hiển thị model điền lý do từ chối-->
                    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
                    <tbody>
                        @forelse($companies as $company)
                            <tr>
                                <td>
                                    <div class="fw-bold text-dark">{{ $company->legal_name }}</div>
                                    <small class="text-muted">ID: #{{ $company->id }}</small>
                                </td>
                                <td><code>{{ $company->tax_code }}</code></td>
                                <td>{{ $company->industry }}</td>
                                <td>{{ $company->created_at->format('d/m/Y H:i') }}</td>
                                <td class="text-end">
                                    <div class="d-flex justify-content-end gap-2">
                                        {{-- Xem chi tiết --}}
                                        <a href="{{ route('admin.companies.show', $company) }}" class="btn btn-sm btn-outline-info">
                                            Xem hồ sơ
                                        </a>

                                        {{-- Nút Duyệt trực tiếp --}}
                                        <form action="{{ route('admin.companies.approve', $company) }}" method="POST">
                                            @csrf
                                            <button type="submit" class="btn btn-sm btn-success" onclick="return confirm('Xác nhận duyệt công ty này?')">
                                                Duyệt
                                            </button>
                                        </form>

                                        {{-- Nút Mở Modal Từ chối --}}
                                        <button type="button" class="btn btn-sm btn-danger" data-bs-toggle="modal" data-bs-target="#rejectModal{{ $company->id }}">
                                            Từ chối
                                        </button>
                                    </div>

                                    <!-- Modal Từ chối (Nằm ngay trong vòng lặp để tránh lỗi ID) -->
                                    <div class="modal fade" id="rejectModal{{ $company->id }}" tabindex="-1" aria-labelledby="rejectModalLabel{{ $company->id }}" aria-hidden="true">
                                        <div class="modal-dialog modal-dialog-centered">
                                            <div class="modal-content">
                                                <form action="{{ route('admin.companies.reject', $company) }}" method="POST">
                                                    @csrf
                                                    <div class="modal-header bg-danger text-white">
                                                        <h5 class="modal-title" id="rejectModalLabel{{ $company->id }}">Từ chối hồ sơ</h5>
                                                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                                                    </div>
                                                    <div class="modal-body text-start">
                                                        <p>Bạn đang từ chối hồ sơ của: <strong>{{ $company->legal_name }}</strong></p>
                                                        <div class="mb-3">
                                                            <label for="reason" class="form-label text-dark fw-bold">Lý do từ chối:</label>
                                                            <textarea class="form-control" name="reason" rows="4" required placeholder="Vui lòng nhập lý do cụ thể để người bán biết đường sửa đổi..."></textarea>
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                                                        <button type="submit" class="btn btn-danger">Xác nhận từ chối</button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- Kết thúc Modal -->

                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center py-4 text-muted">
                                    Hiện không có công ty nào đang chờ duyệt.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection