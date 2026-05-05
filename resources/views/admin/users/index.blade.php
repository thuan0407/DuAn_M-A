@extends('layouts.admin')

@section('title', 'Quản lý người dùng')

@section('content')

<style>
    .btn-soft-red {
        background: #fee2e2;
        color: #dc2626;
        border: 1px solid #fecaca;
        border-radius: 10px;
        padding: 6px 12px;
        font-weight: 600;
    }

    .btn-soft-red:hover {
        background: #fecaca;
    }

    .btn-soft-green {
        background: #dcfce7;
        color: #16a34a;
        border: 1px solid #bbf7d0;
        border-radius: 10px;
        padding: 6px 12px;
        font-weight: 600;
    }

    .btn-soft-green:hover {
        background: #bbf7d0;
    }

    .btn-soft-blue {
        background: #eff6ff;
        color: #2563eb;
        border: 1px solid #bfdbfe;
        border-radius: 10px;
        padding: 6px 12px;
        font-weight: 600;
        text-decoration: none;
    }

    .btn-soft-blue:hover {
        background: #dbeafe;
    }
</style>

<div class="card shadow-sm rounded-4 p-4">

```
<h5 class="mb-3">Danh sách người dùng</h5>

<table class="table align-middle">
    <thead>
        <tr>
            <th>ID</th>
            <th>Tên</th>
            <th>Email</th>
            <th>Role</th>
            <th width="220">Hành động</th>
        </tr>
    </thead>

    <tbody>
    @foreach($users as $user)
        <tr>
            <td>#{{ $user->id }}</td>
            <td>{{ $user->name }}</td>
            <td>{{ $user->email }}</td>

            <td>
                <span class="badge bg-secondary">
                    {{ $user->role }}
                </span>
            </td>

            <td class="d-flex gap-2">

                {{-- 🔵 Xem chi tiết --}}
                <a href="{{ route('admin.users.show', $user->id) }}"
                   class="btn-soft-blue">
                    👁 Chi tiết
                </a>

                {{-- 🔒 Khóa / Mở khóa --}}
                <form method="POST"
                      action="{{ route('admin.users.lock', $user->id) }}"
                      onsubmit="return confirm('Bạn chắc chắn muốn thay đổi trạng thái user này?');">
                    @csrf

                    @if($user->status =='banned')
                        <button class="btn-soft-green">
                            🔓 Mở khóa
                        </button>
                    @else
                        <button class="btn-soft-red">
                            🔒 Khóa
                        </button>
                    @endif
                </form>

            </td>
        </tr>
    @endforeach
    </tbody>

</table>

<div class="mt-3">
    {{ $users->links('pagination::bootstrap-5') }}
</div>

</div>

@endsection
