<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;

class RegisteredUserController extends Controller
{
    /**
     * Hiển thị trang đăng ký.
     */
    public function create()
    {
        return view('auth.register');
    }

    /**
     * Xử lý đăng ký tài khoản.
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],

            'email' => [
                'required',
                'string',
                'lowercase',
                'email',
                'max:255',
                'unique:' . User::class,
            ],

            'password' => ['required', 'confirmed', Rules\Password::defaults()],

            'role' => ['required', 'in:buyer,seller,support'],
        ], [
            'name.required' => 'Vui lòng nhập họ và tên.',
            'email.required' => 'Vui lòng nhập email.',
            'email.email' => 'Email không đúng định dạng.',
            'email.unique' => 'Email này đã được sử dụng.',
            'password.required' => 'Vui lòng nhập mật khẩu.',
            'password.confirmed' => 'Xác nhận mật khẩu không khớp.',
            'role.required' => 'Vui lòng chọn vai trò đăng ký.',
            'role.in' => 'Vai trò đăng ký không hợp lệ.',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role,
            'status' => 'active',
        ]);

        event(new Registered($user));

        Auth::login($user);

        if ($user->role === 'support') {
            return redirect()->route('support.dashboard');
        }

        if ($user->role === 'seller') {
            return redirect()->route('seller.dashboard');
        }

        if ($user->role === 'buyer') {
            return redirect()->route('buyer.home');
        }

        return redirect()->route('login');
    }
}