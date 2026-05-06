<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\KycVerification;
use Illuminate\Http\Request;

class AdminKycController extends Controller
{
  public function pending()
    {
        $kycs = KycVerification::with('user')
            ->where('status', 'pending')
            ->latest()
            ->get();

        return view('admin.kyc.pending', compact('kycs'));
    }

    public function show($id)
    {
        $kyc = KycVerification::with('user')->findOrFail($id);

        return view('admin.kyc.show', compact('kyc'));
    }

    public function approve(KycVerification $kyc)
    {
        if ($kyc->status !== 'pending') {
            return back()->with('error', 'Chỉ có thể duyệt hồ sơ đang chờ duyệt.');
        }

        $kyc->update([
            'status' => 'approved',
            'rejection_reason' => null,
        ]);

        return redirect()
            ->route('admin.kyc.pending')
            ->with('success', 'Đã duyệt hồ sơ người dùng thành công.');
    }

    public function reject(Request $request, KycVerification $kyc)
    {
        if ($kyc->status !== 'pending') {
            return back()->with('error', 'Chỉ có thể từ chối hồ sơ đang chờ duyệt.');
        }

        $request->validate([
            'reason' => ['required', 'string', 'max:1000'],
        ], [
            'reason.required' => 'Vui lòng nhập lý do từ chối.',
            'reason.max' => 'Lý do từ chối không được vượt quá 1000 ký tự.',
        ]);

        $kyc->update([
            'status' => 'rejected',
            'rejection_reason' => $request->reason,
        ]);

        return redirect()
            ->route('admin.kyc.pending')
            ->with('success', 'Đã từ chối hồ sơ và lưu lý do.');
    }
}
