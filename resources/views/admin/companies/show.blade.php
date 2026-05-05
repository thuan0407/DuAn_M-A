@extends('layouts.admin')
@section('title', 'Chi tiết công ty: ' . $company->legal_name)

@section('content')
<div class="container-fluid">
    {{-- 1. THÔNG TIN CHUNG --}}
    <div class="card shadow-sm rounded-4 p-4 mb-4">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h5 class="fw-bold">Thông tin pháp lý doanh nghiệp</h5>
            <span class="badge {{ $company->status == 'approved' ? 'bg-success' : 'bg-warning' }}">
                {{ strtoupper($company->status) }}
            </span>
        </div>
        <div class="row">
            <div class="col-md-6">
                <p><strong>Tên pháp nhân:</strong> {{ $company->legal_name }}</p>
                <p><strong>Mã số thuế:</strong> {{ $company->tax_code }}</p>
                <p><strong>Ngành nghề:</strong> {{ $company->industry }}</p>
            </div>
            <div class="col-md-6">
                <p><strong>Người đại diện:</strong> {{ $company->legal_representative_name }}</p>
                <p><strong>Địa chỉ:</strong> {{ $company->address }}</p>
                <p><strong>Seller sở hữu:</strong> {{ $company->seller->name ?? 'N/A' }}</p>
            </div>
        </div>
    </div>

    {{-- 2. TÀI LIỆU PHÁP LÝ (Bảng company_documents) --}}
    <div class="card shadow-sm rounded-4 p-4 mb-4">
        <h5 class="fw-bold mb-3">Giấy tờ pháp lý đã upload</h5>
        <table class="table table-hover">
            <thead class="table-light">
                <tr>
                    <th>Loại tài liệu</th>
                    <th>Tên file</th>
                    <th>Ngày upload</th>
                    <th>Hành động</th>
                </tr>
            </thead>
            <tbody>
                @forelse($company->documents as $doc)
                <tr>
                    <td>{{ $doc->document_type }}</td>
                    <td>{{ $doc->file_name }}</td>
                    <td>{{ $doc->created_at->format('d/m/Y') }}</td>
                    <td>
                        <a href="{{ asset('storage/' . $doc->file_path) }}" target="_blank" class="btn btn-outline-secondary btn-sm">Xem file</a>
                    </td>
                </tr>
                @empty
                <tr><td colspan="4" class="text-center">Chưa có tài liệu nào được upload.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- 3. DANH SÁCH DEALS CỦA CÔNG TY --}}
    <div class="card shadow-sm rounded-4 p-4">
        <h5 class="fw-bold mb-3">Các thương vụ (Deals) đang thực hiện</h5>
        <table class="table table-bordered">
            <thead class="table-dark">
                <tr>
                    <th>Tên Deal</th>
                    <th>Loại</th>
                    <th>Số tiền mục tiêu</th>
                    <th>Trạng thái</th>
                    <th>Tiến độ</th>
                </tr>
            </thead>
            <tbody>
                @forelse($company->deals as $deal)
                <tr>
                    <td>{{ $deal->title }}</td>
                    <td>{{ $deal->deal_type }}</td>
                    <td>{{ number_format($deal->target_amount) }} {{ $deal->currency }}</td>
                    <td><span class="badge bg-info text-dark">{{ $deal->status }}</span></td>
                    <td>
                        {{-- Logic hiển thị tiến độ thực tế --}}
                        @if($deal->deal_type == 'fundraising')
                            Vốn cam kết: {{ number_format($deal->committed_amount) }} / {{ number_format($deal->target_amount) }}
                        @else
                            {{ $deal->access_requests_count ?? count($deal->accessRequests) }} Buyer quan tâm
                        @endif
                    </td>
                </tr>
                @empty
                <tr><td colspan="5" class="text-center">Công ty này chưa đăng deal nào.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection