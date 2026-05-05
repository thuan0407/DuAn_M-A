<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use App\Models\Company;
use App\Models\Deal;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class SellerDealController extends Controller
{
    public function index()
    {
        $sellerId = Auth::id();

        $company = Company::where('seller_id', $sellerId)->first();

        $deals = Deal::with('company')
            ->where('seller_id', $sellerId)
            ->latest()
            ->get();

        return view('seller.deals.index', compact('company', 'deals'));
    }

    public function create()
    {
        $company = Company::where('seller_id', Auth::id())->first();

        if (!$company) {
            return redirect()
                ->route('seller.company.show')
                ->with('error', 'Bạn cần tạo hồ sơ doanh nghiệp trước khi tạo deal.');
        }

        if ($company->verification_status !== 'verified') {
            return redirect()
                ->route('seller.company.show')
                ->with('error', 'Hồ sơ doanh nghiệp cần được admin duyệt trước khi tạo deal.');
        }

        $deal = new Deal();

        return view('seller.deals.form', [
            'deal' => $deal,
            'company' => $company,
            'mode' => 'create',
        ]);
    }

    public function store(Request $request)
    {
        $company = Company::where('seller_id', Auth::id())->first();

        if (!$company) {
            return redirect()
                ->route('seller.company.show')
                ->with('error', 'Bạn cần tạo hồ sơ doanh nghiệp trước khi tạo deal.');
        }

        if ($company->verification_status !== 'verified') {
            return redirect()
                ->route('seller.company.show')
                ->with('error', 'Hồ sơ doanh nghiệp cần được admin duyệt trước khi tạo deal.');
        }

        $data = $this->validateDeal($request);

        Deal::create([
            'seller_id' => Auth::id(),
            'company_id' => $company->id,

            'title' => $data['title'],
            'deal_type' => $data['deal_type'],

            'industry' => $data['industry'],
            'location' => $data['location'] ?? null,

            'short_description' => $data['short_description'],
            'full_description' => $data['description'] ?? null,

            'target_amount' => $data['target_amount'] ?? null,
            'valuation' => $data['valuation'] ?? null,
            'equity_offered_percent' => $data['equity_offered_percent'] ?? null,
            'min_ticket' => $data['min_ticket'] ?? null,

            'allow_multiple_investors' => $request->boolean('allow_multiple_investors'),

            'currency' => $data['currency'] ?? 'VND',
            'status' => 'pending_review',

            'committed_amount' => 0,
            'confirmed_amount' => 0,
        ]);

        return redirect()
            ->route('seller.company.show')
            ->with('success', 'Đã tạo deal mới. Deal đang chờ admin duyệt.');
    }

    public function edit(Deal $deal)
    {
        $this->authorizeSellerDeal($deal);

        $company = Company::where('seller_id', Auth::id())->firstOrFail();

        return view('seller.deals.form', [
            'deal' => $deal,
            'company' => $company,
            'mode' => 'edit',
        ]);
    }

    public function update(Request $request, Deal $deal)
    {
        $this->authorizeSellerDeal($deal);

        $data = $this->validateDeal($request);

        $deal->update([
            'title' => $data['title'],
            'deal_type' => $data['deal_type'],

            'industry' => $data['industry'],
            'location' => $data['location'] ?? null,

            'short_description' => $data['short_description'],
            'full_description' => $data['description'] ?? null,

            'target_amount' => $data['target_amount'] ?? null,
            'valuation' => $data['valuation'] ?? null,
            'equity_offered_percent' => $data['equity_offered_percent'] ?? null,
            'min_ticket' => $data['min_ticket'] ?? null,

            'allow_multiple_investors' => $request->boolean('allow_multiple_investors'),

            'currency' => $data['currency'] ?? 'VND',
            'status' => 'pending_review',
        ]);

        return redirect()
            ->route('seller.company.show')
            ->with('success', 'Đã cập nhật deal. Deal cần admin duyệt lại trước khi hiển thị.');
    }

    private function validateDeal(Request $request): array
    {
        $baseRules = [
            'title' => ['required', 'string', 'max:255'],
            'deal_type' => ['required', Rule::in(['fundraising', 'share_sale', 'acquisition'])],
            'industry' => ['required', 'string', 'max:255'],
            'location' => ['nullable', 'string', 'max:255'],
            'currency' => ['nullable', 'string', 'max:20'],
            'short_description' => ['required', 'string', 'max:1000'],
            'description' => ['nullable', 'string', 'max:5000'],
        ];

        if ($request->deal_type === 'acquisition') {
            $typeRules = [
                'target_amount' => ['required', 'numeric', 'min:0'],
                'valuation' => ['nullable', 'numeric', 'min:0'],
                'equity_offered_percent' => ['nullable', 'numeric', 'min:0', 'max:100'],
                'min_ticket' => ['nullable', 'numeric', 'min:0'],
                'allow_multiple_investors' => ['nullable', 'boolean'],
            ];
        } elseif ($request->deal_type === 'share_sale') {
            $typeRules = [
                'target_amount' => ['required', 'numeric', 'min:0'],
                'valuation' => ['required', 'numeric', 'min:0'],
                'equity_offered_percent' => ['required', 'numeric', 'min:0.01', 'max:100'],
                'min_ticket' => ['nullable', 'numeric', 'min:0'],
                'allow_multiple_investors' => ['nullable', 'boolean'],
            ];
        } else {
            $typeRules = [
                'target_amount' => ['required', 'numeric', 'min:0'],
                'valuation' => ['nullable', 'numeric', 'min:0'],
                'equity_offered_percent' => ['required', 'numeric', 'min:0.01', 'max:100'],
                'min_ticket' => ['nullable', 'numeric', 'min:0'],
                'allow_multiple_investors' => ['nullable', 'boolean'],
            ];
        }

        return $request->validate(array_merge($baseRules, $typeRules), [
            'title.required' => 'Vui lòng nhập tên deal.',
            'deal_type.required' => 'Vui lòng chọn loại deal.',
            'deal_type.in' => 'Loại deal không hợp lệ.',
            'industry.required' => 'Vui lòng nhập ngành nghề.',
            'short_description.required' => 'Vui lòng nhập mô tả ngắn.',

            'target_amount.required' => 'Vui lòng nhập số tiền chính của deal.',
            'target_amount.numeric' => 'Số tiền chính của deal phải là số.',

            'valuation.required' => 'Vui lòng nhập định giá doanh nghiệp.',
            'valuation.numeric' => 'Định giá phải là số.',

            'equity_offered_percent.required' => 'Vui lòng nhập tỷ lệ cổ phần.',
            'equity_offered_percent.numeric' => 'Tỷ lệ cổ phần phải là số.',
            'equity_offered_percent.max' => 'Tỷ lệ cổ phần không được vượt quá 100%.',

            'min_ticket.numeric' => 'Số tiền tối thiểu phải là số.',
        ]);
    }

    private function authorizeSellerDeal(Deal $deal): void
    {
        if ((int) $deal->seller_id !== (int) Auth::id()) {
            abort(403);
        }
    }
}