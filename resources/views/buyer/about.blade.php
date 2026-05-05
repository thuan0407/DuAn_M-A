@extends('layouts.buyer')

@section('title', 'Giới thiệu')
@section('page_title', 'Giới thiệu')
@section('page_description', 'Thông tin tổng quan về nền tảng kết nối mua bán doanh nghiệp và gọi vốn')

@section('content')

<style>
    .intro-card {
        border-radius: 20px;
        border: 1px solid #fed7aa;
        background: #ffffff;
        box-shadow: 0 10px 25px rgba(249, 115, 22, 0.08);
        padding: 24px;
    }

    .intro-box {
        background: #fff7ed;
        border: 1px solid #fed7aa;
        border-radius: 18px;
        padding: 20px;
        transition: all 0.2s ease;
        height: 100%;
    }

    .intro-box:hover {
        transform: translateY(-4px);
        box-shadow: 0 10px 20px rgba(249, 115, 22, 0.15);
        background: #ffedd5;
    }

    .intro-icon {
        width: 48px;
        height: 48px;
        border-radius: 14px;
        background: linear-gradient(135deg, #f97316, #fb923c);
        color: white;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 22px;
        margin-bottom: 12px;
        box-shadow: 0 6px 14px rgba(249, 115, 22, 0.25);
    }

    .intro-title {
        font-weight: 800;
        color: #1f2937;
        margin-bottom: 6px;
    }

    .intro-text {
        font-size: 14px;
        color: #6b7280;
        line-height: 1.6;
    }
</style>

<div class="intro-card">

```
<h3 class="text-xl font-bold text-gray-900 mb-4">
    Giới thiệu nền tảng
</h3>

<p class="text-gray-600 leading-7">
    M&A Platform là nền tảng hỗ trợ kết nối giữa bên bán doanh nghiệp, bên gọi vốn
    và nhà đầu tư tiềm năng. Người mua có thể tìm kiếm deal phù hợp, lưu deal quan tâm,
    yêu cầu xem hồ sơ chi tiết, đồng ý bảo mật thông tin và trao đổi trực tiếp với seller trên hệ thống.
</p>

<div class="row mt-4 g-3">

    <div class="col-md-4">
        <div class="intro-box">
            <div class="intro-icon">🔍</div>
            <h4 class="intro-title">Tìm kiếm deal</h4>
            <p class="intro-text">
                Lọc các cơ hội theo loại deal, ngành nghề, khu vực và nhu cầu đầu tư.
            </p>
        </div>
    </div>

    <div class="col-md-4">
        <div class="intro-box">
            <div class="intro-icon">🔒</div>
            <h4 class="intro-title">Bảo mật thông tin</h4>
            <p class="intro-text">
                Hồ sơ chi tiết chỉ được xem sau khi buyer đồng ý NDA và được seller duyệt.
            </p>
        </div>
    </div>

    <div class="col-md-4">
        <div class="intro-box">
            <div class="intro-icon">💬</div>
            <h4 class="intro-title">Trao đổi trên sàn</h4>
            <p class="intro-text">
                Buyer và seller có thể trò chuyện, làm rõ thông tin và gửi offer ngay trên nền tảng.
            </p>
        </div>
    </div>

</div>
```

</div>

@endsection
