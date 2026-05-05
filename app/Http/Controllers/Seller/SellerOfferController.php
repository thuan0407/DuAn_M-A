<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use App\Models\Notification;
use App\Models\Offer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class SellerOfferController extends Controller
{
  public function index()
    {
        $offers = Offer::with([
                'deal.company',
                'buyer',
            ])
            ->where('seller_id', Auth::id())
            ->latest()
            ->paginate(10);

        return view('seller.offers.index', compact('offers'));
    }

    public function show(Offer $offer)
    {
        if ((int) $offer->seller_id !== (int) Auth::id()) {
            abort(403);
        }

        $offer->load([
            'deal.company',
            'buyer',
        ]);

        return view('seller.offers.show', compact('offer'));
    }

    public function reject(Offer $offer)
    {
        if ((int) $offer->seller_id !== (int) Auth::id()) {
            abort(403);
        }

        try {
            DB::transaction(function () use ($offer) {
                $offer = Offer::where('id', $offer->id)
                    ->lockForUpdate()
                    ->firstOrFail();

                if ((int) $offer->seller_id !== (int) Auth::id()) {
                    abort(403);
                }

                if ($offer->status !== 'pending') {
                    throw new \Exception('Offer này đã được xử lý trước đó.');
                }

                $deal = $offer->deal()
                    ->firstOrFail();

                $offer->update([
                    'status' => 'rejected',
                ]);

                $this->notifyBuyer(
                    offer: $offer,
                    type: 'offer_rejected',
                    title: 'Offer của bạn đã bị từ chối',
                    message: 'Seller đã từ chối offer của bạn cho deal: ' . ($deal->title ?? 'Không rõ deal')
                );
            });
        } catch (\Exception $e) {
            return redirect()
                ->route('seller.offers.show', $offer)
                ->with('error', $e->getMessage());
        }

        return redirect()
            ->route('seller.offers.show', $offer)
            ->with('success', 'Đã từ chối offer và gửi thông báo cho buyer.');
    }

    public function accept(Offer $offer)
    {
        if ((int) $offer->seller_id !== (int) Auth::id()) {
            abort(403);
        }

        try {
            DB::transaction(function () use ($offer) {
                $offer = Offer::where('id', $offer->id)
                    ->lockForUpdate()
                    ->firstOrFail();

                if ((int) $offer->seller_id !== (int) Auth::id()) {
                    abort(403);
                }

                if ($offer->status !== 'pending') {
                    throw new \Exception('Offer này đã được xử lý trước đó.');
                }

                $deal = $offer->deal()
                    ->lockForUpdate()
                    ->firstOrFail();

                if ((int) $deal->seller_id !== (int) Auth::id()) {
                    abort(403);
                }

                if ($deal->status !== 'active') {
                    throw new \Exception('Deal này hiện không còn nhận offer.');
                }

                if ($deal->deal_type === 'acquisition') {
                    $offer->update([
                        'status' => 'accepted',
                    ]);

                    $deal->update([
                        'committed_amount' => $offer->amount,
                        'status' => 'in_transaction',
                    ]);

                    $this->notifyBuyer(
                        offer: $offer,
                        type: 'offer_accepted',
                        title: 'Offer của bạn đã được chấp nhận',
                        message: 'Seller đã chấp nhận offer của bạn cho deal: ' . ($deal->title ?? 'Không rõ deal')
                    );

                    $this->rejectOtherPendingOffers($deal->id, $offer->id);

                    return;
                }

                if (in_array($deal->deal_type, ['share_sale', 'fundraising'])) {
                    $targetAmount = (float) ($deal->target_amount ?? 0);
                    $currentCommittedAmount = (float) ($deal->committed_amount ?? 0);
                    $newCommittedAmount = $currentCommittedAmount + (float) $offer->amount;

                    $totalEquity = (float) ($deal->equity_offered_percent ?? 0);

                    $currentAcceptedEquity = Offer::where('deal_id', $deal->id)
                        ->where('status', 'accepted')
                        ->sum('equity_percent');

                    $newAcceptedEquity = (float) $currentAcceptedEquity + (float) ($offer->equity_percent ?? 0);

                    if ($targetAmount > 0 && $newCommittedAmount > $targetAmount) {
                        throw new \Exception('Số tiền offer vượt quá số tiền còn lại của deal.');
                    }

                    if ($totalEquity > 0 && $newAcceptedEquity > $totalEquity) {
                        throw new \Exception('Tỷ lệ cổ phần offer vượt quá tỷ lệ còn lại của deal.');
                    }

                    $remainingAmount = max(0, $targetAmount - $newCommittedAmount);
                    $remainingEquity = max(0, $totalEquity - $newAcceptedEquity);

                    $shouldCloseForMoreOffers = false;

                    if (!$deal->allow_multiple_investors) {
                        $shouldCloseForMoreOffers = true;
                    }

                    if ($remainingAmount <= 0 || $remainingEquity <= 0) {
                        $shouldCloseForMoreOffers = true;
                    }

                    $offer->update([
                        'status' => 'accepted',
                    ]);

                    $deal->update([
                        'committed_amount' => $newCommittedAmount,
                        'status' => $shouldCloseForMoreOffers ? 'in_transaction' : 'active',
                    ]);

                    $this->notifyBuyer(
                        offer: $offer,
                        type: 'offer_accepted',
                        title: 'Offer của bạn đã được chấp nhận',
                        message: 'Seller đã chấp nhận offer của bạn cho deal: ' . ($deal->title ?? 'Không rõ deal')
                    );

                    if ($shouldCloseForMoreOffers) {
                        $this->rejectOtherPendingOffers($deal->id, $offer->id);
                    }

                    return;
                }

                throw new \Exception('Loại deal không hợp lệ.');
            });
        } catch (\Exception $e) {
            return redirect()
                ->route('seller.offers.show', $offer)
                ->with('error', $e->getMessage());
        }

        return redirect()
            ->route('seller.offers.show', $offer)
            ->with('success', 'Đã đồng ý offer và cập nhật deal.');
    }

    private function notifyBuyer(Offer $offer, string $type, string $title, string $message): void
    {
        Notification::create([
            'user_id' => $offer->buyer_id,
            'type' => $type,
            'title' => $title,
            'message' => $message,
            'data' => [
                'offer_id' => $offer->id,
                'deal_id' => $offer->deal_id,
            ],
            'is_read' => false,
        ]);
    }

    private function rejectOtherPendingOffers(int $dealId, int $acceptedOfferId): void
    {
        $otherOffers = Offer::with('deal')
            ->where('deal_id', $dealId)
            ->where('id', '!=', $acceptedOfferId)
            ->where('status', 'pending')
            ->get();

        foreach ($otherOffers as $otherOffer) {
            $otherOffer->update([
                'status' => 'rejected',
            ]);

            $this->notifyBuyer(
                offer: $otherOffer,
                type: 'offer_rejected',
                title: 'Offer của bạn đã bị từ chối',
                message: 'Offer của bạn đã bị từ chối vì deal này đã chuyển sang giai đoạn giao dịch: ' . ($otherOffer->deal->title ?? 'Không rõ deal')
            );
        }
    }
}
