<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthenticatedSessionController extends Controller
{
    /**
     * Hiển thị trang đăng nhập.
     */
    public function create()
    {
        return view('auth.login');
    }

    /**
     * Xử lý đăng nhập.
     */
 public function store(LoginRequest $request): RedirectResponse
{
    $request->authenticate();

    $request->session()->regenerate();

    $user = Auth::user();

    if ($user->status !== 'active') {
        Auth::guard('web')->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return to_route('login')
            ->withErrors([
                'email' => 'Tài khoản của bạn chưa được kích hoạt hoặc đã bị khóa.',
            ])
            ->onlyInput('email');
    }

    if ($user->role === 'admin') {
        return to_route('admin.dashboard');
    }

    if ($user->role === 'support') {
        return to_route('support.dashboard');
    }

    if ($user->role === 'seller') {
        return to_route('seller.dashboard');
    }

    if ($user->role === 'buyer') {
        return to_route('buyer.home');
    }

    Auth::guard('web')->logout();

    $request->session()->invalidate();
    $request->session()->regenerateToken();

    return to_route('login')
        ->withErrors([
            'email' => 'Tài khoản chưa được phân quyền hợp lệ.',
        ]);
}

    /**
     * Đăng xuất.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return to_route('login');
    }
}