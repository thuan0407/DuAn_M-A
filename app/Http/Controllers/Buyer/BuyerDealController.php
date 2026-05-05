<?php

namespace App\Http\Controllers\Buyer;

use App\Http\Controllers\Controller;
use App\Models\Deal;
use App\Models\DealAccessRequest;
use App\Models\DealInterest;
use App\Models\Offer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BuyerDealController extends Controller
{
    public function index(Request $request)
    {
        $query = Deal::with('company')
            ->where('status', 'active');

        if ($request->filled('type')) {
            $query->where('deal_type', $request->type);
        }

        if ($request->filled('keyword')) {
            $keyword = $request->keyword;

            $query->where(function ($q) use ($keyword) {
                $q->where('title', 'like', '%' . $keyword . '%')
                    ->orWhere('short_description', 'like', '%' . $keyword . '%')
                    ->orWhere('industry', 'like', '%' . $keyword . '%')
                    ->orWhere('location', 'like', '%' . $keyword . '%')
                    ->orWhereHas('company', function ($companyQuery) use ($keyword) {
                        $companyQuery->where('legal_name', 'like', '%' . $keyword . '%');
                    });
            });
        }

        $deals = $query->latest()->get();

        $interestedDealIds = DealInterest::where('buyer_id', Auth::id())
            ->where('type', 'interested')
            ->pluck('deal_id');

        $currentType = $request->type;

        $typeLabels = [
            'fundraising' => 'Góp vốn',
            'share_sale' => 'Mua cổ phần',
            'acquisition' => 'Mua công ty',
        ];

        $currentTypeLabel = $typeLabels[$currentType] ?? 'Tất cả deal';

        return view('buyer.deal', compact(
            'deals',
            'interestedDealIds',
            'currentType',
            'currentTypeLabel'
        ));
    }

    public function show(Deal $deal)
    {
        if (!in_array($deal->status, ['active', 'negotiating'])) {
            return redirect()
                ->route('buyer.home')
                ->with('error', 'Deal này hiện không khả dụng.');
        }

        $accessRequest = DealAccessRequest::where('deal_id', $deal->id)
            ->where('buyer_id', Auth::id())
            ->where('status', 'approved')
            ->first();

        if (!$accessRequest) {
            return redirect()
                ->route('buyer.home')
                ->with('error', 'Bạn chưa được seller duyệt quyền xem hồ sơ chi tiết của deal này.');
        }

        $deal->load([
            'company.financials',
            'company.documents',
            'dataRoomFiles',
            'seller',
        ]);

        $myOffer = Offer::where('deal_id', $deal->id)
            ->where('buyer_id', Auth::id())
            ->latest()
            ->first();

        $acceptedEquityPercent = Offer::where('deal_id', $deal->id)
            ->where('status', 'accepted')
            ->sum('equity_percent');

        $remainingAmount = max(
            0,
            (float) ($deal->target_amount ?? 0) - (float) ($deal->committed_amount ?? 0)
        );

        $remainingEquityPercent = max(
            0,
            (float) ($deal->equity_offered_percent ?? 0) - (float) $acceptedEquityPercent
        );

        $dealTypeLabels = [
            'fundraising' => 'Góp vốn',
            'share_sale' => 'Mua cổ phần',
            'acquisition' => 'Mua công ty',
        ];

        $dealTypeLabel = $dealTypeLabels[$deal->deal_type] ?? 'Khác';

        return view('buyer.deals.show', compact(
            'deal',
            'accessRequest',
            'myOffer',
            'acceptedEquityPercent',
            'remainingAmount',
            'remainingEquityPercent',
            'dealTypeLabel'
        ));
    }
}