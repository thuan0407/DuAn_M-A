<?php

namespace App\Http\Controllers\Buyer;

use App\Http\Controllers\Controller;
use App\Models\Deal;
use App\Models\DealAccessRequest;
use App\Models\NdaAcceptance;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BuyerDealAccessRequestController extends Controller
{
    public function store(Request $request, Deal $deal): RedirectResponse
    {
        if ($deal->status !== 'active') {
            return back()->with('error', 'Deal này chưa được hiển thị công khai.');
        }

        $buyerId = Auth::id();

        $existingAccessRequest = DealAccessRequest::where('deal_id', $deal->id)
            ->where('buyer_id', $buyerId)
            ->first();

        if ($existingAccessRequest) {
            if ($existingAccessRequest->status === 'pending') {
                return back()->with('info', 'Bạn đã gửi yêu cầu xem hồ sơ và đang chờ seller duyệt.');
            }

            if ($existingAccessRequest->status === 'approved') {
                return back()->with('success', 'Seller đã duyệt yêu cầu của bạn. Bạn có thể xem hồ sơ chi tiết.');
            }

            if ($existingAccessRequest->status === 'rejected') {
                $ndaAcceptance = NdaAcceptance::updateOrCreate(
                    [
                        'deal_id' => $deal->id,
                        'buyer_id' => $buyerId,
                    ],
                    [
                        'nda_version' => 'v1.0',
                        'accepted_at' => now(),
                        'accepted_ip' => $request->ip(),
                        'status' => 'signed',
                    ]
                );

                $existingAccessRequest->update([
                    'nda_acceptance_id' => $ndaAcceptance->id,
                    'status' => 'pending',
                    'rejection_reason' => null,
                ]);

                return back()->with('success', 'Bạn đã gửi lại yêu cầu xem hồ sơ. Vui lòng chờ seller duyệt.');
            }
        }

        $ndaAcceptance = NdaAcceptance::updateOrCreate(
            [
                'deal_id' => $deal->id,
                'buyer_id' => $buyerId,
            ],
            [
                'nda_version' => 'v1.0',
                'accepted_at' => now(),
                'accepted_ip' => $request->ip(),
                'status' => 'signed',
            ]
        );

        DealAccessRequest::create([
            'nda_acceptance_id' => $ndaAcceptance->id,
            'deal_id' => $deal->id,
            'buyer_id' => $buyerId,
            'status' => 'pending',
            'rejection_reason' => null,
        ]);

        return back()->with('success', 'Đã đồng ý NDA và gửi yêu cầu xem hồ sơ. Vui lòng chờ seller duyệt.');
    }
}
