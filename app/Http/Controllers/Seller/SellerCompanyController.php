<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use App\Models\Company;
use App\Models\CompanyDocument;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use App\Models\CompanyFinancial;
use App\Models\Deal;

class SellerCompanyController extends Controller
{
    
public function show()
{
    $seller = Auth::user();

    $company = Company::with([
            'financials',
            'documents',
        ])
        ->where('seller_id', $seller->id)
        ->first();

    if (!$company) {
        return redirect()
            ->route('seller.company.create')
            ->with('error', 'Bạn cần tạo hồ sơ doanh nghiệp trước.');
    }

    $deals = Deal::where('seller_id', $seller->id)
        ->where('company_id', $company->id)
        ->latest()
        ->get();

    return view('seller.company.show', compact(
        'company',
        'deals'
    ));
}

    public function create()
    {
        $existingCompany = Company::where('seller_id', Auth::id())->first();

        if ($existingCompany) {
            return redirect()
                ->route('seller.company.show')
                ->with('info', 'Bạn đã có hồ sơ doanh nghiệp. Bạn có thể cập nhật thông tin nếu cần.');
        }

        $company = new Company();

        return view('seller.company.form', [
            'company' => $company,
            'mode' => 'create',
        ]);
    }

    public function store(Request $request)
    {
        $existingCompany = Company::where('seller_id', Auth::id())->first();

        if ($existingCompany) {
            return redirect()
                ->route('seller.company.show')
                ->with('info', 'Bạn đã có hồ sơ doanh nghiệp.');
        }

        $data = $this->validateCompany($request);

        Company::create([
            'seller_id' => Auth::id(),
            'legal_name' => $data['legal_name'],
            'tax_code' => $data['tax_code'],
            'business_registration_number' => $data['business_registration_number'],
            'address' => $data['address'],
            'legal_representative_name' => $data['legal_representative_name'],
            'industry' => $data['industry'],
            'description' => $data['description'] ?? null,
            'verification_status' => 'pending',
            'rejection_reason' => null,
        ]);

        return redirect()
            ->route('seller.company.show')
            ->with('success', 'Đã tạo hồ sơ doanh nghiệp. Hồ sơ đang chờ admin duyệt.');
    }

    public function edit()
    {
        $company = Company::where('seller_id', Auth::id())->firstOrFail();

        return view('seller.company.form', [
            'company' => $company,
            'mode' => 'edit',
        ]);
    }

    public function update(Request $request)
    {
        $company = Company::where('seller_id', Auth::id())->firstOrFail();

        $data = $this->validateCompany($request, $company->id);

        $company->update([
            'legal_name' => $data['legal_name'],
            'tax_code' => $data['tax_code'],
            'business_registration_number' => $data['business_registration_number'],
            'address' => $data['address'],
            'legal_representative_name' => $data['legal_representative_name'],
            'industry' => $data['industry'],
            'description' => $data['description'] ?? null,
            'verification_status' => 'pending',
            'rejection_reason' => null,
        ]);

        return redirect()
            ->route('seller.company.show')
            ->with('success', 'Đã cập nhật hồ sơ doanh nghiệp. Hồ sơ cần admin duyệt lại.');
    }

    private function validateCompany(Request $request, ?int $companyId = null): array
    {
        return $request->validate([
            'legal_name' => ['required', 'string', 'max:255'],
            'tax_code' => [
                'required',
                'string',
                'max:50',
                Rule::unique('companies', 'tax_code')->ignore($companyId),
            ],
            'business_registration_number' => [
                'required',
                'string',
                'max:100',
                Rule::unique('companies', 'business_registration_number')->ignore($companyId),
            ],
            'address' => ['required', 'string', 'max:500'],
            'legal_representative_name' => ['required', 'string', 'max:255'],
            'industry' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string', 'max:3000'],
        ], [
            'legal_name.required' => 'Vui lòng nhập tên pháp lý của công ty.',
            'tax_code.required' => 'Vui lòng nhập mã số thuế.',
            'tax_code.unique' => 'Mã số thuế này đã được sử dụng.',
            'business_registration_number.required' => 'Vui lòng nhập số đăng ký kinh doanh.',
            'business_registration_number.unique' => 'Số đăng ký kinh doanh này đã được sử dụng.',
            'address.required' => 'Vui lòng nhập địa chỉ công ty.',
            'legal_representative_name.required' => 'Vui lòng nhập người đại diện pháp luật.',
            'industry.required' => 'Vui lòng nhập ngành nghề hoạt động.',
        ]);
    }



    // thêm thông tin giấy tờ của công ty
    public function storeDocument(Request $request)
    {
        $company = Company::where('seller_id', Auth::id())->first();

        if (!$company) {
            return redirect()
                ->route('seller.company.show')
                ->with('error', 'Bạn cần tạo hồ sơ doanh nghiệp trước khi upload giấy tờ.');
        }

        $request->validate([
            'document_type' => [
                'required',
                'in:business_license,tax_document,financial_statement,ownership_document,other',
            ],
            'document_file' => [
                'required',
                'file',
                'mimes:pdf,jpg,jpeg,png,doc,docx',
                'max:10240',
            ],
            'note' => [
                'nullable',
                'string',
                'max:1000',
            ],
        ], [
            'document_type.required' => 'Vui lòng chọn loại giấy tờ.',
            'document_type.in' => 'Loại giấy tờ không hợp lệ.',
            'document_file.required' => 'Vui lòng chọn file giấy tờ.',
            'document_file.file' => 'Tệp tải lên không hợp lệ.',
            'document_file.mimes' => 'File phải thuộc định dạng pdf, jpg, jpeg, png, doc hoặc docx.',
            'document_file.max' => 'Dung lượng file không được vượt quá 10MB.',
        ]);

        $file = $request->file('document_file');

        $path = $file->store('company-documents', 'public');

        CompanyDocument::create([
            'company_id' => $company->id,
            'uploaded_by' => Auth::id(),
            'document_type' => $request->document_type,
            'file_name' => $file->getClientOriginalName(),
            'file_path' => $path,
            'note' => $request->note,
        ]);

        return redirect()
            ->route('seller.company.show')
            ->with('success', 'Đã tải giấy tờ hồ sơ lên. Admin sẽ kiểm tra sau.');
    }

    //Sửa thông tin giấy tờ của công ty
    public function updateDocument(Request $request, CompanyDocument $document)
    {
        $document->load('company');

        if (!$document->company || (int) $document->company->seller_id !== (int) Auth::id()) {
            abort(403);
        }

        $request->validate([
            'document_type' => [
                'required',
                'in:business_license,tax_document,financial_statement,ownership_document,other',
            ],
            'document_file' => [
                'nullable',
                'file',
                'mimes:pdf,jpg,jpeg,png,doc,docx',
                'max:10240',
            ],
            'note' => [
                'nullable',
                'string',
                'max:1000',
            ],
        ], [
            'document_type.required' => 'Vui lòng chọn loại giấy tờ.',
            'document_type.in' => 'Loại giấy tờ không hợp lệ.',
            'document_file.file' => 'Tệp tải lên không hợp lệ.',
            'document_file.mimes' => 'File phải thuộc định dạng pdf, jpg, jpeg, png, doc hoặc docx.',
            'document_file.max' => 'Dung lượng file không được vượt quá 10MB.',
        ]);

        $data = [
            'document_type' => $request->document_type,
            'note' => $request->note,
        ];

        if ($request->hasFile('document_file')) {
            if ($document->file_path) {
                Storage::disk('public')->delete($document->file_path);
            }

            $file = $request->file('document_file');

            $data['file_name'] = $file->getClientOriginalName();
            $data['file_path'] = $file->store('company-documents', 'public');
        }

        $document->update($data);

        return redirect()
            ->route('seller.company.show')
            ->with('success', 'Đã cập nhật giấy tờ hồ sơ.');
    }


    //thêm thông tin tài chính
    public function storeFinancial(Request $request)
    {
        $company = Company::where('seller_id', Auth::id())->first();

        if (!$company) {
            return redirect()
                ->route('seller.company.show')
                ->with('error', 'Bạn cần tạo hồ sơ doanh nghiệp trước khi thêm dữ liệu tài chính.');
        }

        $request->validate([
            'financial_year' => ['required', 'integer', 'min:1900', 'max:2100'],
            'revenue' => ['nullable', 'numeric', 'min:0'],
            'ebitda' => ['nullable', 'numeric'],
            'net_profit' => ['nullable', 'numeric'],
        ], [
            'financial_year.required' => 'Vui lòng nhập năm tài chính.',
            'financial_year.integer' => 'Năm tài chính phải là số.',
            'financial_year.min' => 'Năm tài chính không hợp lệ.',
            'financial_year.max' => 'Năm tài chính không hợp lệ.',
            'revenue.numeric' => 'Doanh thu phải là số.',
            'revenue.min' => 'Doanh thu không được âm.',
            'ebitda.numeric' => 'EBITDA phải là số.',
            'net_profit.numeric' => 'Lợi nhuận ròng phải là số.',
        ]);

        $existing = CompanyFinancial::where('company_id', $company->id)
            ->where('financial_year', $request->financial_year)
            ->first();

        if ($existing) {
            return redirect()
                ->route('seller.company.show')
                ->with('error', 'Năm tài chính này đã tồn tại. Vui lòng dùng nút sửa để cập nhật.');
        }

        CompanyFinancial::create([
            'company_id' => $company->id,
            'financial_year' => $request->financial_year,
            'revenue' => $request->revenue,
            'ebitda' => $request->ebitda,
            'net_profit' => $request->net_profit,
        ]);

        return redirect()
            ->route('seller.company.show')
            ->with('success', 'Đã thêm dữ liệu tài chính doanh nghiệp.');
    }

    //sửa dữ liệu thông tin tài chính
    public function updateFinancial(Request $request, CompanyFinancial $financial)
    {
        $financial->load('company');

        if (!$financial->company || (int) $financial->company->seller_id !== (int) Auth::id()) {
            abort(403);
        }

        $request->validate([
            'financial_year' => ['required', 'integer', 'min:1900', 'max:2100'],
            'revenue' => ['nullable', 'numeric', 'min:0'],
            'ebitda' => ['nullable', 'numeric'],
            'net_profit' => ['nullable', 'numeric'],
        ], [
            'financial_year.required' => 'Vui lòng nhập năm tài chính.',
            'financial_year.integer' => 'Năm tài chính phải là số.',
            'financial_year.min' => 'Năm tài chính không hợp lệ.',
            'financial_year.max' => 'Năm tài chính không hợp lệ.',
            'revenue.numeric' => 'Doanh thu phải là số.',
            'revenue.min' => 'Doanh thu không được âm.',
            'ebitda.numeric' => 'EBITDA phải là số.',
            'net_profit.numeric' => 'Lợi nhuận ròng phải là số.',
        ]);

        $duplicate = CompanyFinancial::where('company_id', $financial->company_id)
            ->where('financial_year', $request->financial_year)
            ->where('id', '!=', $financial->id)
            ->exists();

        if ($duplicate) {
            return redirect()
                ->route('seller.company.show')
                ->with('error', 'Năm tài chính này đã tồn tại.');
        }

        $financial->update([
            'financial_year' => $request->financial_year,
            'revenue' => $request->revenue,
            'ebitda' => $request->ebitda,
            'net_profit' => $request->net_profit,
        ]);

        return redirect()
            ->route('seller.company.show')
            ->with('success', 'Đã cập nhật dữ liệu tài chính doanh nghiệp.');
    }

    //Xóa dữ liệu thông tin tài chính
    public function destroyFinancial(CompanyFinancial $financial)
    {
        $financial->load('company');

        if (!$financial->company || (int) $financial->company->seller_id !== (int) Auth::id()) {
            abort(403);
        }

        $financial->delete();

        return redirect()
            ->route('seller.company.show')
            ->with('success', 'Đã xóa dữ liệu tài chính.');
    }

}