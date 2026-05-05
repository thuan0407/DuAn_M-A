@extends('layouts.admin')

@section('title', 'Quản lý công ty')

@section('content')

<div class="card shadow-sm rounded-4 p-4">

    <h5 class="mb-3">Danh sách công ty</h5>

    <table class="table">
        <thead>
            <tr>
                <th>ID</th>
                <th>Tên công ty</th>
                <th>Seller</th>
                <th>Hành động</th>
            </tr>
        </thead>

        <tbody>
        @foreach($companies as $company)
            <tr>
                <td>#{{ $company->id }}</td>
                
                {{-- SỬA Ở ĐÂY: từ 'name' thành 'legal_name' --}}
                <td>{{ $company->legal_name }}</td> 
                
                <td>{{ $company->seller->name ?? 'N/A' }}</td>
                <td>
                    <div class="d-flex gap-2">
                        <a href="{{ route('admin.companies.show', $company->id) }}" class="btn btn-primary btn-sm">Xem chi tiết</a>
                        
                        <form method="POST" action="{{ route('admin.companies.delete', $company->id) }}" onsubmit="return confirm('Xác nhận xóa?')">
                            @csrf
                            <button class="btn btn-danger btn-sm">Xóa</button>
                        </form>
                    </div>
                </td>
            </tr>
        @endforeach
        </tbody>

    </table>

</div>

@endsection