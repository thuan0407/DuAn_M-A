<?php

namespace App\Http\Controllers\Buyer;

use App\Http\Controllers\Controller;
use App\Models\Offer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BuyerSuccessfulDealController extends Controller
{
 public function index()
    {
        $offers = Offer::with([
                'deal.company',
                'seller',
            ])
            ->where('buyer_id', Auth::id())
            ->where('status', 'accepted')
            ->latest()
            ->paginate(10);

        return view('buyer.successful_deals.index', compact('offers'));
    }

    public function show(Offer $offer)
    {
        if ((int) $offer->buyer_id !== (int) Auth::id()) {
            abort(403);
        }

        if ($offer->status !== 'accepted') {
            return redirect()
                ->route('buyer.successful_deals.index')
                ->with('error', 'Deal này chưa phải deal thành công.');
        }

        $offer->load([
            'deal.company.financials',
            'deal.company.documents',
            'deal.dataRoomFiles',
            'deal.seller',
            'seller',
        ]);

        $deal = $offer->deal;

        if (!$deal) {
            return redirect()
                ->route('buyer.successful_deals.index')
                ->with('error', 'Deal không còn tồn tại.');
        }

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

        $financialsForChart = $deal->company && $deal->company->financials
            ? $deal->company->financials->sortBy('financial_year')->values()
            : collect();

        $chartLabels = $financialsForChart
            ->pluck('financial_year')
            ->map(fn ($value) => (string) $value)
            ->values()
            ->all();

        $chartRevenue = $financialsForChart
            ->pluck('revenue')
            ->map(fn ($value) => (float) $value)
            ->values()
            ->all();

        $chartEbitda = $financialsForChart
            ->pluck('ebitda')
            ->map(fn ($value) => (float) $value)
            ->values()
            ->all();

        $chartNetProfit = $financialsForChart
            ->pluck('net_profit')
            ->map(fn ($value) => (float) $value)
            ->values()
            ->all();

        return view('buyer.successful_deals.show', compact(
            'offer',
            'deal',
            'dealTypeLabel',
            'acceptedEquityPercent',
            'remainingAmount',
            'remainingEquityPercent',
            'chartLabels',
            'chartRevenue',
            'chartEbitda',
            'chartNetProfit'
        ));
    }

}
