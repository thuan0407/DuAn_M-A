@extends('layouts.buyer')

@section('title', 'Chính sách bảo mật')
@section('page_title', 'Chính sách bảo mật')
@section('page_description', 'Quy định về bảo mật thông tin khi sử dụng nền tảng')

@section('content')

<style>
    .privacy-card {
        background: #ffffff;
        border: 1px solid #fed7aa;
        border-radius: 20px;
        padding: 28px;
        box-shadow: 0 10px 30px rgba(249, 115, 22, 0.08);
    }

    .privacy-item {
        background: #fff7ed;
        border: 1px solid #fed7aa;
        border-radius: 16px;
        padding: 18px;
        transition: all 0.2s ease;
    }

    .privacy-item:hover {
        transform: translateY(-2px);
        box-shadow: 0 10px 20px rgba(249, 115, 22, 0.12);
    }

    .privacy-icon {
        width: 42px;
        height: 42px;
        border-radius: 12px;
        background: linear-gradient(135deg, #f97316, #fb923c);
        color: #fff;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 18px;
        font-weight: bold;
    }

    .privacy-title {
        font-weight: 800;
        color: #1f2937;
        margin-bottom: 4px;
    }

    .privacy-text {
        color: #6b7280;
        line-height: 1.7;
        margin: 0;
    }
</style>

<div class="privacy-card">

    <h3 class="text-xl fw-bold mb-4" style="color:#ea580c;">
        🔐 Chính sách bảo mật thông tin
    </h3>

    <div class="d-flex flex-column gap-3">

        {{-- 1 --}}
        <div class="privacy-item d-flex gap-3">
            <div class="privacy-icon">🌐</div>
            <div>
                <p class="privacy-title">1. Thông tin công khai</p>
                <p class="privacy-text">
                    Người mua có thể xem các thông tin công khai của deal như ngành nghề, khu vực,
                    loại deal, mô tả ngắn, mức gọi vốn hoặc giá chào bán dự kiến.
                </p>
            </div>
        </div>

        {{-- 2 --}}
        <div class="privacy-item d-flex gap-3">
            <div class="privacy-icon">🔒</div>
            <div>
                <p class="privacy-title">2. Thông tin bảo mật</p>
                <p class="privacy-text">
                    Các tài liệu như báo cáo tài chính chi tiết, hồ sơ pháp lý, hợp đồng,
                    thông tin thuế, danh sách khách hàng hoặc tài liệu Data Room chỉ được xem
                    sau khi buyer đồng ý NDA và được seller cấp quyền.
                </p>
            </div>
        </div>

        {{-- 3 --}}
        <div class="privacy-item d-flex gap-3">
            <div class="privacy-icon">⚖️</div>
            <div>
                <p class="privacy-title">3. Trách nhiệm của người mua</p>
                <p class="privacy-text">
                    Người mua không được tự ý tải xuống, sao chép, chia sẻ hoặc sử dụng thông tin bảo mật
                    ngoài mục đích đánh giá cơ hội giao dịch, trừ khi được bên cung cấp thông tin cho phép.
                </p>
            </div>
        </div>

        {{-- 4 --}}
        <div class="privacy-item d-flex gap-3">
            <div class="privacy-icon">💬</div>
            <div>
                <p class="privacy-title">4. Liên lạc trên nền tảng</p>
                <p class="privacy-text">
                    Buyer và seller nên trao đổi thông qua hệ thống trò chuyện của nền tảng để đảm bảo
                    lịch sử trao đổi được lưu lại và hỗ trợ xử lý khi có phát sinh.
                </p>
            </div>
        </div>

    </div>
</div>

@endsection