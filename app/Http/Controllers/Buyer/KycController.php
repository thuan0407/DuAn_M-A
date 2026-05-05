<?php

namespace App\Http\Controllers\Buyer;

use App\Http\Controllers\Controller;
use App\Models\KycVerification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class KycController extends Controller
{
    public function create()
    {
        $kyc = KycVerification::where('user_id', Auth::id())->first();

        return view('buyer.kyc.create', compact('kyc'));
    }

    public function store(Request $request)
    {
        $kyc = KycVerification::where('user_id', Auth::id())->first();

        $request->validate([
            'document_type' => ['required', 'in:cccd,cmnd,passport'],
            'document_number' => ['required', 'string', 'max:100'],

            'document_front' => [
                $kyc && $kyc->document_front ? 'nullable' : 'required',
                'image',
                'mimes:jpg,jpeg,png,webp',
                'max:4096',
            ],

            'document_back' => [
                $kyc && $kyc->document_back ? 'nullable' : 'required',
                'image',
                'mimes:jpg,jpeg,png,webp',
                'max:4096',
            ],

            'selfie_image' => [
                $kyc && $kyc->selfie_image ? 'nullable' : 'required',
                'image',
                'mimes:jpg,jpeg,png,webp',
                'max:4096',
            ],

            'company' => ['nullable', 'string', 'max:100'],
            'position' => ['nullable', 'string', 'max:50'],
            'experience' => ['nullable', 'string', 'max:500'],
            'goal' => ['nullable', 'string', 'max:500'],
            'phone' => ['nullable', 'regex:/^0[0-9]{9,10}$/'],
        ], [
            'document_type.required' => 'Vui lòng chọn loại giấy tờ.',
            'document_type.in' => 'Loại giấy tờ không hợp lệ.',

            'document_number.required' => 'Vui lòng nhập số giấy tờ.',
            'document_number.max' => 'Số giấy tờ không được vượt quá 100 ký tự.',

            'document_front.required' => 'Vui lòng tải ảnh mặt trước giấy tờ.',
            'document_front.image' => 'Ảnh mặt trước phải là file hình ảnh.',
            'document_front.mimes' => 'Ảnh mặt trước phải có định dạng jpg, jpeg, png hoặc webp.',
            'document_front.max' => 'Ảnh mặt trước không được vượt quá 4MB.',

            'document_back.required' => 'Vui lòng tải ảnh mặt sau giấy tờ.',
            'document_back.image' => 'Ảnh mặt sau phải là file hình ảnh.',
            'document_back.mimes' => 'Ảnh mặt sau phải có định dạng jpg, jpeg, png hoặc webp.',
            'document_back.max' => 'Ảnh mặt sau không được vượt quá 4MB.',

            'selfie_image.required' => 'Vui lòng tải ảnh selfie xác minh.',
            'selfie_image.image' => 'Ảnh selfie phải là file hình ảnh.',
            'selfie_image.mimes' => 'Ảnh selfie phải có định dạng jpg, jpeg, png hoặc webp.',
            'selfie_image.max' => 'Ảnh selfie không được vượt quá 4MB.',

            'phone.regex' => 'Số điện thoại không hợp lệ.',
        ]);

        $data = [
            'document_type' => $request->document_type,
            'document_number' => $request->document_number,
            'company' => $request->company,
            'position' => $request->position,
            'experience' => $request->experience,
            'goal' => $request->goal,
            'phone' => $request->phone,
            'status' => 'pending',
            'rejection_reason' => null,
        ];

        $fileFields = [
            'document_front',
            'document_back',
            'selfie_image',
        ];

        foreach ($fileFields as $field) {
            if ($request->hasFile($field)) {
                if ($kyc && $kyc->$field && Storage::disk('public')->exists($kyc->$field)) {
                    Storage::disk('public')->delete($kyc->$field);
                }

                $data[$field] = $request->file($field)->store('kyc/' . Auth::id(), 'public');
            }
        }

        KycVerification::updateOrCreate(
            ['user_id' => Auth::id()],
            $data
        );

        return redirect()
            ->back()
            ->with('success', 'Gửi hồ sơ xác minh thành công. Hồ sơ đang chờ admin duyệt.');
    }
}