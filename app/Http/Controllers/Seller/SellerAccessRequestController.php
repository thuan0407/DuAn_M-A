<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use App\Models\DealAccessRequest;
use App\Models\Notification;
use Illuminate\Http\Request;

class SellerAccessRequestController extends Controller
{
    public function index()
    {
        $requests = DealAccessRequest::with([
            'buyer.kyc', // 👈 thêm dòng này
            'deal'
        ])
        ->whereHas('deal', function ($q) {
            $q->where('seller_id', auth()->id());
        })
        ->latest()
        ->get();

        return view('seller.access_requests.index', compact('requests'));
    }

    // ✅ DUYỆT
    public function approve(DealAccessRequest $accessRequest)
    {
        // 🔒 check quyền
        if ($accessRequest->deal->seller_id != auth()->id()) {
            abort(403);
        }

        $accessRequest->update([
            'status' => 'approved'
        ]);

        // 🔔 thông báo
        Notification::create([
            'user_id' => $accessRequest->buyer_id,
            'related_deal_id' => $accessRequest->deal_id,
            'type' => 'access_request_approved',
            'title' => 'Yêu cầu đã được duyệt',
            'message' => 'Bạn đã được phép xem deal: ' . $accessRequest->deal->title,
            'is_read' => false,
        ]);

        return back()->with('success', 'Đã duyệt yêu cầu');
    }

    // ❌ TỪ CHỐI
    public function reject(Request $request, DealAccessRequest $accessRequest)
    {
        // 🔒 check quyền
        if ($accessRequest->deal->seller_id != auth()->id()) {
            abort(403);
        }

        // ✅ validate lý do
        $request->validate([
            'reason' => 'required|string|max:1000'
        ]);

        $accessRequest->update([
            'status' => 'rejected',
            'rejection_reason' => $request->reason // ✅ đúng field
        ]);

        // 🔔 thông báo
        Notification::create([
            'user_id' => $accessRequest->buyer_id,
            'related_deal_id' => $accessRequest->deal_id,
            'type' => 'access_request_rejected',
            'title' => 'Yêu cầu bị từ chối',
            'message' => 'Deal "' . $accessRequest->deal->title . '" bị từ chối. Lý do: ' . $request->reason,
            'is_read' => false,
        ]);

        return back()->with('error', 'Đã từ chối yêu cầu');
    }
}