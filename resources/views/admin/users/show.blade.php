@extends('layouts.admin')

@section('title', 'Chi tiết người dùng')

@section('content')

<style>
    .user-card {
        background: #ffffff;
        border-radius: 24px;
        border: 1px solid #fed7aa;
        box-shadow: 0 10px 30px rgba(249, 115, 22, 0.1);
        padding: 28px;
    }

    .user-avatar {
        width: 80px;
        height: 80px;
        border-radius: 20px;
        background: linear-gradient(135deg, #f97316, #fb923c);
        color: white;
        font-size: 30px;
        font-weight: 900;
        display: flex;
        align-items: center;
        justify-content: center;
        box-shadow: 0 10px 20px rgba(249, 115, 22, 0.3);
    }

    .info-box {
        background: #fff7ed;
        border: 1px solid #fed7aa;
        border-radius: 16px;
        padding: 16px;
    }

    .info-label {
        font-size: 13px;
        color: #6b7280;
    }

    .info-value {
        font-weight: 800;
        color: #1f2937;
    }

    .btn-orange {
        background: #f97316;
        color: white;
        border-radius: 12px;
        padding: 10px 16px;
        font-weight: 700;
        border: none;
    }

    .btn-orange:hover {
        background: #ea580c;
    }

    .btn-red {
        background: #dc2626;
        color: white;
        border-radius: 12px;
        padding: 10px 16px;
        font-weight: 700;
        border: none;
    }

    .btn-green {
        background: #16a34a;
        color: white;
        border-radius: 12px;
        padding: 10px 16px;
        font-weight: 700;
        border: none;
    }
</style>

<div class="mb-3">
    <a href="{{ route('admin.users.index') }}" class="btn-orange">
        ← Quay lại
    </a>
</div>

<div class="user-card">

```
<div class="d-flex align-items-center gap-4 mb-4">
    <div class="user-avatar">
        {{ mb_substr($user->name, 0, 1) }}
    </div>

    <div>
        <h4 class="fw-bold mb-1">{{ $user->name }}</h4>
        <p class="text-muted mb-0">{{ $user->email }}</p>
    </div>
</div>

<div class="row g-3">

    <div class="col-md-4">
        <div class="info-box">
            <div class="info-label">ID</div>
            <div class="info-value">#{{ $user->id }}</div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="info-box">
            <div class="info-label">Role</div>
            <div class="info-value">{{ $user->role }}</div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="info-box">
            <div class="info-label">Trạng thái</div>
            <div class="info-value">
                @if($user->is_locked)
                    <span class="badge bg-danger">Bị khóa</span>
                @else
                    <span class="badge bg-success">Hoạt động</span>
                @endif
            </div>
        </div>
    </div>

    <div class="col-md-6">
        <div class="info-box">
            <div class="info-label">Ngày tạo</div>
            <div class="info-value">
                {{ $user->created_at->format('d/m/Y H:i') }}
            </div>
        </div>
    </div>

    <div class="col-md-6">
        <div class="info-box">
            <div class="info-label">Cập nhật</div>
            <div class="info-value">
                {{ $user->updated_at->format('d/m/Y H:i') }}
            </div>
        </div>
    </div>

</div>

<div class="mt-4">

    <form method="POST"
          action="{{ route('admin.users.lock', $user->id) }}"
          onsubmit="return confirm('Bạn chắc chắn muốn thay đổi trạng thái user này?');">
        @csrf

        @if($user->is_locked)
            <button class="btn-green">
                🔓 Mở khóa tài khoản
            </button>
        @else
            <button class="btn-red">
                🔒 Khóa tài khoản
            </button>
        @endif

    </form>

</div>
```

</div>

@endsection
