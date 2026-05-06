<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EnsureSellerKycApproved
{
    public function handle(Request $request, Closure $next)
    {
        $user = Auth::user();

        if (! $user || $user->role !== 'seller') {
            return $next($request);
        }

        $kyc = $user->kycVerification;

        if (! $kyc || $kyc->status !== 'approved') {
            return redirect()
                ->route('seller.kyc.create')
                ->with('error', 'Bạn cần xác minh danh tính người bán trước khi thao tác trong tài khoản seller.');
        }

        return $next($request);
    }
}
