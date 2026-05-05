<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Deal;
use Illuminate\Http\Request;

class AdminDealController extends Controller
{
    public function index()
    {
        $deals = Deal::with('company')
            ->latest()
            ->get();

        return view('admin.deals.index', compact('deals'));
    }

    public function show(Deal $deal)
    {
        $deal->load(['company.financials']);

        return view('admin.deals.show', compact('deal'));
    }

    public function pending()
    {
        $deals = Deal::with('company')
            ->where('status', 'pending_review')
            ->latest()
            ->get();

        return view('admin.deals.pending', compact('deals'));
    }

    public function approve(Deal $deal)
    {
        $deal->load('company');

        if ($deal->status !== 'pending_review') {
            return back()->with('error', 'Chỉ có thể duyệt deal đang ở trạng thái chờ duyệt.');
        }

        if (!$deal->company) {
            return back()->with('error', 'Không thể duyệt deal vì deal chưa có hồ sơ công ty.');
        }

        if ($deal->company->verification_status !== 'verified') {
            return back()->with('error', 'Không thể duyệt deal vì công ty chưa được xác minh.');
        }

        $deal->update([
            'status' => 'active',
            'published_at' => now(),
            'rejection_reason' => null,
        ]);

        return redirect()
            ->route('admin.deals.pending')
            ->with('success', 'Deal đã được duyệt và chuyển sang trạng thái đang hoạt động.');
    }

    public function reject(Request $request, Deal $deal)
    {
        if ($deal->status !== 'pending_review') {
            return back()->with('error', 'Chỉ có thể từ chối deal đang ở trạng thái chờ duyệt.');
        }

        $request->validate([
            'reason' => ['required', 'string', 'max:1000'],
        ], [
            'reason.required' => 'Vui lòng nhập lý do từ chối deal.',
            'reason.max' => 'Lý do từ chối không được vượt quá 1000 ký tự.',
        ]);

        $deal->update([
            'status' => 'rejected',
            'rejection_reason' => $request->reason,
            'published_at' => null,
        ]);

        return redirect()
            ->route('admin.deals.pending')
            ->with('success', 'Đã từ chối deal và lưu lý do từ chối.');
    }

    public function hide(Deal $deal)
    {
        $deal->update([
            'status' => 'hidden',
        ]);

        return back()->with('success', 'Đã ẩn deal.');
    }

    public function delete(Deal $deal)
    {
        $deal->delete();

        return back()->with('success', 'Đã xóa deal.');
    }
}