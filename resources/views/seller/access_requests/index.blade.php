@extends('layouts.seller')

@section('title', 'Yêu cầu xem hồ sơ')

@section('page_title', 'Yêu cầu từ Buyer')
@section('page_description', 'Xem và duyệt yêu cầu truy cập hồ sơ')

@section('content')

<div class="row g-4">

@forelse($requests as $request)

@php
    $kyc = $request->buyer->kyc ?? null;
@endphp

<div class="col-md-6 col-lg-4">
    <div class="card border-0 shadow-sm rounded-4 h-100 p-4 d-flex flex-column">

        {{-- DEAL --}}
        <div class="mb-3">
            <h5 class="fw-bold mb-1">
                {{ $request->deal->title }}
            </h5>
            <p class="text-muted small mb-0">
                Deal #{{ $request->deal->id }}
            </p>
        </div>

        {{-- BUYER PROFILE --}}
        <div class="border rounded-4 p-3 mb-3 bg-light">

            {{-- HEADER --}}
            <div class="d-flex align-items-center gap-3 mb-3">
                <div class="rounded-circle bg-warning text-white d-flex align-items-center justify-content-center"
                     style="width:50px;height:50px;font-weight:800;font-size:18px;">
                    {{ strtoupper(substr($request->buyer->name,0,1)) }}
                </div>

                <div>
                    <p class="fw-bold mb-0">
                        {{ $request->buyer->name }}
                    </p>
                    <p class="text-muted small mb-0">
                        {{ $request->buyer->email }}
                    </p>

                    {{-- KYC STATUS --}}
                    @if($kyc && $kyc->status === 'approved')
                        <span class="badge bg-success mt-1">Đã xác minh</span>
                    @else
                        <span class="badge bg-secondary mt-1">Chưa xác minh</span>
                    @endif
                </div>
            </div>

            <hr class="my-2">

            {{-- INFO --}}
            @if($kyc)
                <div class="row small">

                    <div class="col-6 mb-2">
                        <span class="text-muted">📱 SĐT</span><br>
                        <strong>{{ $kyc->phone ?? 'Chưa có' }}</strong>
                    </div>

                    <div class="col-6 mb-2">
                        <span class="text-muted">🏢 Công ty</span><br>
                        <strong>{{ $kyc->company ?? 'Chưa cập nhật' }}</strong>
                    </div>

                    <div class="col-6 mb-2">
                        <span class="text-muted">💼 Chức vụ</span><br>
                        <strong>{{ $kyc->position ?? 'Chưa có' }}</strong>
                    </div>

                    <div class="col-6 mb-2">
                        <span class="text-muted">📊 Kinh nghiệm</span><br>
                        <strong>{{ $kyc->experience ?? 'Chưa có' }}</strong>
                    </div>

                    <div class="col-12">
                        <span class="text-muted">🎯 Mục tiêu đầu tư</span><br>
                        <strong>{{ $kyc->goal ?? 'Chưa có' }}</strong>
                    </div>

                </div>
            @else
                <p class="text-muted small mb-0">
                    Buyer chưa cung cấp thông tin xác minh.
                </p>
            @endif

        </div>

        {{-- STATUS --}}
        <div class="mb-3">
            <strong>Trạng thái:</strong>

            @if($request->status == 'pending')
                <span class="badge bg-warning text-dark">Chờ duyệt</span>
            @elseif($request->status == 'approved')
                <span class="badge bg-success">Đã duyệt</span>
            @else
                <span class="badge bg-danger">Từ chối</span>
            @endif
        </div>

        {{-- LÝ DO TỪ CHỐI --}}
        @if($request->status == 'rejected' && $request->reject_reason)
            <div class="alert alert-danger small p-2 mb-3">
                <strong>Lý do từ chối:</strong><br>
                {{ $request->reject_reason }}
            </div>
        @endif

        {{-- ACTION --}}
        @if($request->status == 'pending')
            <div class="d-flex gap-2 mt-auto">

                {{-- APPROVE --}}
                <form method="POST"
                      action="{{ route('seller.access_requests.approve', $request) }}"
                      class="w-50">
                    @csrf
                    <button class="btn btn-success w-100"
                        {{ (!$kyc || $kyc->status !== 'approved') ? 'disabled' : '' }}>
                        ✅ Duyệt
                    </button>
                </form>

                {{-- REJECT --}}
                <button class="btn btn-danger w-50"
                        data-bs-toggle="modal"
                        data-bs-target="#rejectModal{{ $request->id }}">
                    ❌ Từ chối
                </button>

            </div>

            {{-- CẢNH BÁO --}}
            @if(!$kyc || $kyc->status !== 'approved')
                <p class="text-danger small mt-2 mb-0">
                    ⚠ Buyer chưa xác minh KYC → không nên duyệt
                </p>
            @endif
        @endif

    </div>
</div>

{{-- MODAL TỪ CHỐI --}}
<div class="modal fade" id="rejectModal{{ $request->id }}" tabindex="-1">
    <div class="modal-dialog">
        <form method="POST"
              action="{{ route('seller.access_requests.reject', $request) }}"
              class="modal-content rounded-4">
            @csrf

            <div class="modal-header">
                <h5 class="modal-title fw-bold">Nhập lý do từ chối</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body">

                <p class="small text-muted">
                    Buyer sẽ nhìn thấy lý do này.
                </p>

                <textarea name="reason"
                          class="form-control"
                          rows="4"
                          required
                          placeholder="Ví dụ: Deal chưa phù hợp..."></textarea>

            </div>

            <div class="modal-footer">
                <button type="button"
                        class="btn btn-light"
                        data-bs-dismiss="modal">
                    Hủy
                </button>

                <button type="submit"
                        class="btn btn-danger">
                    Xác nhận từ chối
                </button>
            </div>

        </form>
    </div>
</div>

@empty

<div class="col-12">
    <div class="text-center p-5">
        <h5>Chưa có yêu cầu nào</h5>
    </div>
</div>

@endforelse

</div>

@endsection