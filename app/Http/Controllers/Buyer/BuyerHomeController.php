<?php

namespace App\Http\Controllers\Buyer;

use App\Http\Controllers\Controller;
use App\Models\Deal;
use App\Models\DealAccessRequest;
use App\Models\DealInterest;
use App\Models\KycVerification;
use App\Models\Offer;
use Illuminate\Support\Facades\Auth;

class BuyerHomeController extends Controller
{
    public function index()
    {
        $buyerId = Auth::id();

        //kiểm tra xem người dùng đã xác minh chưa
        $user = auth()->user();
        $kyc = KycVerification::where('user_id', $user->id)->first();

        $deals = Deal::with('company')
            ->where('status', 'active')
            ->latest()
            ->get();

        $interestedDealIds = DealInterest::where('buyer_id', $buyerId)
            ->where('type', 'interested')
            ->pluck('deal_id');

        $accessRequestsByDealId = DealAccessRequest::where('buyer_id', $buyerId)
            ->whereIn('deal_id', $deals->pluck('id'))
            ->get()
            ->keyBy('deal_id');

        $stats = [
            'total_deals' => $deals->count(),
            'interested_deals' => $interestedDealIds->count(),
            'access_requests' => DealAccessRequest::where('buyer_id', $buyerId)->count(),
            'offers' => Offer::where('buyer_id', $buyerId)->count(),
        ];

        return view('buyer.home', compact(
            'deals',
            'stats',
            'interestedDealIds',
            'accessRequestsByDealId',
            'kyc'
        ));
    }
}