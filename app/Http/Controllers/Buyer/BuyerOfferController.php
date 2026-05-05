<?php

namespace App\Http\Controllers\Buyer;

use App\Http\Controllers\Controller;
use App\Models\Deal;
use App\Models\DealAccessRequest;
use App\Models\Offer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BuyerOfferController extends Controller
{
public function create(Deal $deal)
    {
        if ($deal->status !== 'active') {
            return redirect()
                ->route('buyer.home')
                ->with('error', 'Deal này hiện không nhận offer.');
        }

        if ((int) $deal->seller_id === (int) Auth::id()) {
            return redirect()
                ->route('buyer.home')
                ->with('error', 'Bạn không thể gửi offer cho deal của chính mình.');
        }

        $hasApprovedAccess = DealAccessRequest::where('deal_id', $deal->id)
            ->where('buyer_id', Auth::id())
            ->where('status', 'approved')
            ->exists();

        if (!$hasApprovedAccess) {
            return redirect()
                ->route('buyer.home')
                ->with('error', 'Bạn cần được seller duyệt quyền xem hồ sơ trước khi gửi offer.');
        }

        $existingPendingOffer = Offer::where('deal_id', $deal->id)
            ->where('buyer_id', Auth::id())
            ->where('status', 'pending')
            ->exists();

        if ($existingPendingOffer) {
            return redirect()
                ->route('buyer.deals.show', $deal)
                ->with('error', 'Bạn đã có một offer đang chờ seller phản hồi cho deal này.');
        }

        $deal->load('company');

        return view('buyer.offers.create', compact('deal'));
    }



public function store(Request $request, Deal $deal)
{
    if (!in_array($deal->status, ['active'])) {
        return redirect()
            ->route('buyer.home')
            ->with('error', 'Deal này hiện không nhận offer.');
    }

    if ((int) $deal->seller_id === (int) Auth::id()) {
        return redirect()
            ->route('buyer.home')
            ->with('error', 'Bạn không thể gửi offer cho deal của chính mình.');
    }

    $hasApprovedAccess = DealAccessRequest::where('deal_id', $deal->id)
        ->where('buyer_id', Auth::id())
        ->where('status', 'approved')
        ->exists();

    if (!$hasApprovedAccess) {
        return redirect()
            ->route('buyer.home')
            ->with('error', 'Bạn cần được seller duyệt quyền xem hồ sơ trước khi gửi offer.');
    }

    $existingPendingOffer = Offer::where('deal_id', $deal->id)
        ->where('buyer_id', Auth::id())
        ->where('status', 'pending')
        ->exists();

    if ($existingPendingOffer) {
        return redirect()
            ->route('buyer.deals.show', $deal)
            ->with('error', 'Bạn đã có một offer đang chờ seller phản hồi.');
    }

    $minAmount = $deal->min_ticket ? (float) $deal->min_ticket : 1;
    $maxEquity = $deal->equity_offered_percent
        ? (float) $deal->equity_offered_percent
        : 100;

    $rules = [
        'amount' => ['required', 'numeric', 'min:' . $minAmount],
        'note' => ['nullable', 'string', 'max:2000'],
    ];

    if (in_array($deal->deal_type, ['share_sale', 'fundraising'])) {
        $rules['equity_percent'] = [
            'required',
            'numeric',
            'min:0.01',
            'max:' . $maxEquity
        ];
    }

    $data = $request->validate($rules);

    // 🔥 mapping chuẩn
    $offerType = match ($deal->deal_type) {
        'acquisition' => 'buyout',
        'share_sale' => 'share_purchase',
        'fundraising' => 'investment',
        default => throw new \Exception('Deal type không hợp lệ'),
    };

    Offer::create([
        'deal_id' => $deal->id,
        'buyer_id' => Auth::id(),
        'seller_id' => $deal->seller_id,
        'offer_type' => $offerType,
        'amount' => $data['amount'],
        'equity_percent' => $deal->deal_type === 'acquisition'
            ? null
            : ($data['equity_percent'] ?? null),
        'currency' => $deal->currency ?? 'VND',
        'note' => $data['note'] ?? null,
        'status' => 'pending',
    ]);

    return redirect()
        ->route('buyer.deals.show', $deal)
        ->with('success', 'Đã gửi offer cho seller.');
}
}
