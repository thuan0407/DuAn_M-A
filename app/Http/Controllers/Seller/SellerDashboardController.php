<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use App\Models\Company;
use App\Models\Deal;
use App\Models\DealAccessRequest;
use App\Models\Offer;
use Illuminate\Support\Facades\Auth;

class SellerDashboardController extends Controller
{
    public function index()
    {
        $sellerId = Auth::id();

        $companies = Company::where('seller_id', $sellerId)->get();

        $deals = Deal::with('company')
            ->where('seller_id', $sellerId)
            ->latest()
            ->get();

        $dealIds = $deals->pluck('id');

        $accessRequests = DealAccessRequest::with(['buyer', 'deal'])
            ->whereIn('deal_id', $dealIds)
            ->latest()
            ->take(5)
            ->get();

        $offers = Offer::with(['buyer', 'deal'])
            ->where('seller_id', $sellerId)
            ->latest()
            ->take(5)
            ->get();

        $stats = [
            'companies' => $companies->count(),
            'total_deals' => $deals->count(),
            'published_deals' => $deals->where('status', 'published')->count(),
            'pending_deals' => $deals->where('status', 'pending_review')->count(),
            'access_requests' => DealAccessRequest::whereIn('deal_id', $dealIds)->count(),
            'pending_access_requests' => DealAccessRequest::whereIn('deal_id', $dealIds)
                ->where('status', 'pending')
                ->count(),
            'offers' => Offer::where('seller_id', $sellerId)->count(),
        ];

        return view('seller.dashboard', compact(
            'companies',
            'deals',
            'accessRequests',
            'offers',
            'stats'
        ));
    }
}