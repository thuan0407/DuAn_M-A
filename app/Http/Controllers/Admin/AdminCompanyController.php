<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Company;

class AdminCompanyController extends Controller
{
    public function index()
    {
        $companies = Company::latest()->get();
        return view('admin.companies.index', compact('companies'));
    }

    public function show(Company $company)
    {
        // Lấy công ty kèm theo tài liệu và các deal liên quan
        $company->load(['documents', 'deals']); 
        
        return view('admin.companies.show', compact('company'));
    }




    //=======================
    //Duyệt công ty mới
    public function pending()
    {
        // Khớp với tên cột verification_status trong sơ đồ[cite: 2]
        $companies = Company::where('verification_status', 'pending')->latest()->get(); 
        return view('admin.companies.pending', compact('companies'));
    }


    public function approve(Company $company)
    {
        // Kiểm tra xem biến $company có nhận đúng dữ liệu không
        // dd($company); // Bỏ comment dòng này để debug nếu cần

        $updated = $company->update([
            'verification_status' => 'verified' // Phải khớp với giá trị ENUM trong sơ đồ[cite: 2]
        ]);

        if ($updated) {
            return back()->with('success', 'Đã duyệt công ty thành công!');
        }

        return back()->with('error', 'Có lỗi xảy ra khi cập nhật.');
    }

    //Từ chối 
public function reject(Request $request, Company $company)
{
    // 1. Validation lý do
    $request->validate([
        'reason' => 'required|string|max:1000',
    ]);

    // 2. Cập nhật trạng thái và lý do
    $updated = $company->update([
        'verification_status' => 'rejected',
        'rejection_reason' => $request->reason 
    ]);

    if ($updated) {
        return back()->with('success', 'Đã từ chối hồ sơ và gửi lý do cho người bán.');
    }

    return back()->with('error', 'Có lỗi xảy ra.');
}

}
