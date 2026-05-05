<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\User;

class AdminUserController extends Controller
{
public function index()
{
    $users = User::latest()
        ->whereIn('role', ['seller', 'buyer', 'support'])
        ->paginate(8);

    return view('admin.users.index', compact('users'));
}

    //khóa tài khoản
    public function lock($id)
    {
        $user = User::findOrFail($id);

        $user->status = $user->status === 'active' ? 'banned' : 'active';

        $user->save();

        return back()->with('success', 'Đã cập nhật trạng thái user');
    }

    //Xem chi tiết
    public function show($id)
    {
        $user = User::findOrFail($id);

        return view('admin.users.show', compact('user'));
    }



    public function ban(User $user)
    {
        $user->update(['status' => 'banned']);
        return back()->with('error', 'Đã khóa user');
    }

    public function unban(User $user)
    {
        $user->update(['status' => 'active']);
        return back()->with('success', 'Đã mở khóa');
    }
}
