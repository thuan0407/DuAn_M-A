<?php

namespace App\Http\Controllers\Buyer;

use App\Http\Controllers\Controller;
use App\Models\Deal;
use App\Models\DealAccessRequest;
use App\Models\DealInterest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;

class BuyerInterestController extends Controller
{
    public function index()
    {
        $buyerId = Auth::id();

        $interests = DealInterest::with(['deal.company'])
            ->where('buyer_id', $buyerId)
            ->latest()
            ->get();

        $dealIds = $interests
            ->pluck('deal_id')
            ->filter()
            ->unique()
            ->values();

        $accessRequestsByDealId = DealAccessRequest::where('buyer_id', $buyerId)
            ->whereIn('deal_id', $dealIds)
            ->latest()
            ->get()
            ->unique('deal_id')
            ->keyBy('deal_id');

        return view('buyer.interests', compact(
            'interests',
            'accessRequestsByDealId'
        ));
    }

    public function store(Deal $deal): RedirectResponse
    {
        DealInterest::firstOrCreate([
            'deal_id' => $deal->id,
            'buyer_id' => Auth::id(),
            'type' => 'interested',
        ]);

        return back()->with('success', 'Đã thêm deal vào danh sách quan tâm.');
    }

    public function destroy(Deal $deal): RedirectResponse
    {
        DealInterest::where('deal_id', $deal->id)
            ->where('buyer_id', Auth::id())
            ->where('type', 'interested')
            ->delete();

        return back()->with('success', 'Đã bỏ deal khỏi danh sách quan tâm.');
    }
}
