<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        Schema::disableForeignKeyConstraints();

        DB::table('notifications')->truncate();
        DB::table('offers')->truncate();
        DB::table('deal_messages')->truncate();
        DB::table('deal_conversations')->truncate();
        DB::table('data_room_files')->truncate();
        DB::table('deal_access_requests')->truncate();
        DB::table('nda_acceptances')->truncate();
        DB::table('deal_interests')->truncate();
        DB::table('company_financials')->truncate();
        DB::table('deals')->truncate();
        DB::table('company_documents')->truncate();
        DB::table('companies')->truncate();
        DB::table('kyc_verifications')->truncate();
        DB::table('users')->truncate();

        Schema::enableForeignKeyConstraints();

        $now = now();
        $password = Hash::make('12345678');

        /*
        |--------------------------------------------------------------------------
        | Admin
        |--------------------------------------------------------------------------
        */
        $adminId = DB::table('users')->insertGetId([
            'name' => 'Nguyễn Minh Quản Trị',
            'email' => 'admin@ma-platform.test',
            // Đã xóa dòng phone ở đây
            'email_verified_at' => $now,
            'password' => $password,
            'role' => 'admin',
            'status' => 'active',
            'remember_token' => Str::random(10),
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        /*
        |--------------------------------------------------------------------------
        | Support
        |--------------------------------------------------------------------------
        */
        $supportUsers = [
            [
                'name' => 'Trần Thị Hỗ Trợ',
                'email' => 'support1@ma-platform.test',
            ],
            [
                'name' => 'Lê Quốc Support',
                'email' => 'support2@ma-platform.test',
            ],
            [
                'name' => 'Phạm Anh Tư Vấn',
                'email' => 'support3@ma-platform.test',
            ],
        ];

        foreach ($supportUsers as $support) {
            $supportId = DB::table('users')->insertGetId([
                'name' => $support['name'],
                'email' => $support['email'],
                // Đã xóa dòng phone ở đây
                'email_verified_at' => $now,
                'password' => $password,
                'role' => 'support',
                'status' => 'active',
                'remember_token' => Str::random(10),
                'created_at' => $now,
                'updated_at' => $now,
            ]);

            DB::table('kyc_verifications')->insert([
                'user_id' => $supportId,
                'document_type' => 'cccd',
                'document_number' => '07920000000' . $supportId,
                'document_front' => 'demo/kyc/support_' . $supportId . '_front.jpg',
                'document_back' => 'demo/kyc/support_' . $supportId . '_back.jpg',
                'selfie_image' => 'demo/kyc/support_' . $supportId . '_selfie.jpg',

                // NEW
                'company' => 'M&A Platform',
                'position' => 'Support Staff',
                'experience' => '2+ năm hỗ trợ khách hàng.',
                'goal' => 'Hỗ trợ người dùng hiệu quả.',
                'phone' => '09000000' . $supportId,

                'status' => 'approved',
                'rejection_reason' => null,
                'created_at' => $now,
                'updated_at' => $now,
            ]);
        }

        /*
        |--------------------------------------------------------------------------
        | Buyers
        |--------------------------------------------------------------------------
        */
        $buyerUsers = [
            [
                'name' => 'Hoàng Gia Bảo',
                'email' => 'buyer1@ma-platform.test',
            ],
            [
                'name' => 'Đỗ Minh Khang',
                'email' => 'buyer2@ma-platform.test',
            ],
            [
                'name' => 'Vũ Thanh Tùng',
                'email' => 'buyer3@ma-platform.test',
            ],
            [
                'name' => 'Ngô Hà Anh',
                'email' => 'buyer4@ma-platform.test',
            ],
            [
                'name' => 'Phan Nhật Nam',
                'email' => 'buyer5@ma-platform.test',
            ],
        ];

        foreach ($buyerUsers as $buyer) {
            $buyerId = DB::table('users')->insertGetId([
                'name' => $buyer['name'],
                'email' => $buyer['email'],
                // Đã xóa dòng phone ở đây
                'email_verified_at' => $now,
                'password' => $password,
                'role' => 'buyer',
                'status' => 'active',
                'remember_token' => Str::random(10),
                'created_at' => $now,
                'updated_at' => $now,
            ]);

             DB::table('kyc_verifications')->insert([
                'user_id' => $buyerId,
                'document_type' => 'cccd',
                'document_number' => '07930000000' . $buyerId,
                'document_front' => 'demo/kyc/buyer_' . $buyerId . '_front.jpg',
                'document_back' => 'demo/kyc/buyer_' . $buyerId . '_back.jpg',
                'selfie_image' => 'demo/kyc/buyer_' . $buyerId . '_selfie.jpg',

                'company' => 'Quỹ đầu tư ABC',
                'position' => 'Nhà đầu tư',
                'experience' => '3+ năm đầu tư startup và SME.',
                'goal' => 'Tìm kiếm cơ hội M&A tiềm năng.',
                'phone' => '09100000' . $buyerId,

                'status' => 'approved',
                'rejection_reason' => null,
                'created_at' => $now,
                'updated_at' => $now,
            ]);

            DB::table('kyc_verifications')->insert([
                'user_id' => $buyerId,
                'document_type' => 'cccd',
                'document_number' => '07930000000' . $buyerId,
                'document_front' => 'demo/kyc/buyer_' . $buyerId . '_front.jpg',
                'document_back' => 'demo/kyc/buyer_' . $buyerId . '_back.jpg',
                'selfie_image' => 'demo/kyc/buyer_' . $buyerId . '_selfie.jpg',
                'status' => 'approved',
                'rejection_reason' => null,
                'created_at' => $now,
                'updated_at' => $now,
            ]);
        }

        /*
        |--------------------------------------------------------------------------
        | Sellers, Companies, Documents, Deals
        |--------------------------------------------------------------------------
        */
        $sellerData = [
            [
                'seller_name' => 'Nguyễn Hoàng Sơn',
                'seller_email' => 'seller1@ma-platform.test',
                // Đã xóa dòng seller_phone ở đây
                'company' => [
                    'legal_name' => 'Công ty TNHH Sản Xuất Nội Thất An Phát',
                    'tax_code' => '0318000001',
                    'business_registration_number' => 'BRN-AP-001',
                    'address' => 'Quận Bình Tân, TP. Hồ Chí Minh',
                    'legal_representative_name' => 'Nguyễn Hoàng Sơn',
                    'industry' => 'Sản xuất nội thất',
                    'description' => 'Doanh nghiệp chuyên sản xuất bàn ghế, kệ gỗ công nghiệp và nội thất văn phòng cho khách hàng B2B.',
                ],
                'deal' => [
                    'title' => 'Gọi vốn mở rộng xưởng sản xuất nội thất An Phát',
                    'deal_type' => 'fundraising',
                    'target_amount' => 5000000000,
                    'valuation' => 25000000000,
                    'equity_offered_percent' => 20,
                    'min_ticket' => 500000000,
                    'allow_multiple_investors' => true,
                ],
            ],
            [
                'seller_name' => 'Trần Thị Mai Anh',
                'seller_email' => 'seller2@ma-platform.test',
                // Đã xóa dòng seller_phone ở đây
                'company' => [
                    'legal_name' => 'Công ty Cổ phần Công Nghệ GreenTech Việt Nam',
                    'tax_code' => '0318000002',
                    'business_registration_number' => 'BRN-GT-002',
                    'address' => 'Quận Cầu Giấy, Hà Nội',
                    'legal_representative_name' => 'Trần Thị Mai Anh',
                    'industry' => 'Công nghệ môi trường',
                    'description' => 'Doanh nghiệp phát triển giải pháp quản lý năng lượng, IoT và phần mềm giám sát tiêu thụ điện cho nhà máy.',
                ],
                'deal' => [
                    'title' => 'Bán 25% cổ phần GreenTech Việt Nam',
                    'deal_type' => 'share_sale',
                    'target_amount' => 12000000000,
                    'valuation' => 48000000000,
                    'equity_offered_percent' => 25,
                    'min_ticket' => 1000000000,
                    'allow_multiple_investors' => false,
                ],
            ],
            [
                'seller_name' => 'Lê Minh Đức',
                'seller_email' => 'seller3@ma-platform.test',
                // Đã xóa dòng seller_phone ở đây
                'company' => [
                    'legal_name' => 'Công ty TNHH Thương Mại Dịch Vụ Hải Minh',
                    'tax_code' => '0318000003',
                    'business_registration_number' => 'BRN-HM-003',
                    'address' => 'Quận Hải Châu, Đà Nẵng',
                    'legal_representative_name' => 'Lê Minh Đức',
                    'industry' => 'Thương mại dịch vụ',
                    'description' => 'Doanh nghiệp vận hành chuỗi phân phối hàng tiêu dùng, có hệ thống khách hàng đại lý tại miền Trung.',
                ],
                'deal' => [
                    'title' => 'Chuyển nhượng 100% Công ty Hải Minh',
                    'deal_type' => 'acquisition',
                    'target_amount' => 18000000000,
                    'valuation' => 18000000000,
                    'equity_offered_percent' => 100,
                    'min_ticket' => 18000000000,
                    'allow_multiple_investors' => false,
                ],
            ],
            [
                'seller_name' => 'Phạm Quốc Hưng',
                'seller_email' => 'seller4@ma-platform.test',
                // Đã xóa dòng seller_phone ở đây
                'company' => [
                    'legal_name' => 'Công ty TNHH F&B Nhà Bếp Việt',
                    'tax_code' => '0318000004',
                    'business_registration_number' => 'BRN-NBV-004',
                    'address' => 'Quận 3, TP. Hồ Chí Minh',
                    'legal_representative_name' => 'Phạm Quốc Hưng',
                    'industry' => 'F&B',
                    'description' => 'Doanh nghiệp sở hữu mô hình bếp trung tâm, cung cấp suất ăn văn phòng và vận hành thương hiệu đồ ăn online.',
                ],
                'deal' => [
                    'title' => 'Gọi vốn phát triển chuỗi bếp trung tâm Nhà Bếp Việt',
                    'deal_type' => 'fundraising',
                    'target_amount' => 8000000000,
                    'valuation' => 32000000000,
                    'equity_offered_percent' => 25,
                    'min_ticket' => 800000000,
                    'allow_multiple_investors' => true,
                ],
            ],
            [
                'seller_name' => 'Đặng Thanh Bình',
                'seller_email' => 'seller5@ma-platform.test',
                // Đã xóa dòng seller_phone ở đây
                'company' => [
                    'legal_name' => 'Công ty Cổ phần Logistics Đông Á',
                    'tax_code' => '0318000005',
                    'business_registration_number' => 'BRN-DA-005',
                    'address' => 'Thành phố Thủ Đức, TP. Hồ Chí Minh',
                    'legal_representative_name' => 'Đặng Thanh Bình',
                    'industry' => 'Logistics',
                    'description' => 'Doanh nghiệp cung cấp dịch vụ kho vận, giao nhận nội địa và quản lý đơn hàng cho doanh nghiệp thương mại điện tử.',
                ],
                'deal' => [
                    'title' => 'Bán 30% cổ phần Logistics Đông Á',
                    'deal_type' => 'share_sale',
                    'target_amount' => 15000000000,
                    'valuation' => 50000000000,
                    'equity_offered_percent' => 30,
                    'min_ticket' => 1500000000,
                    'allow_multiple_investors' => true,
                ],
            ],
        ];

        foreach ($sellerData as $index => $item) {
            $sellerId = DB::table('users')->insertGetId([
                'name' => $item['seller_name'],
                'email' => $item['seller_email'],
                // Đã xóa dòng phone ở đây
                'email_verified_at' => $now,
                'password' => $password,
                'role' => 'seller',
                'status' => 'active',
                'remember_token' => Str::random(10),
                'created_at' => $now,
                'updated_at' => $now,
            ]);

            DB::table('kyc_verifications')->insert([
                'user_id' => $sellerId,
                'document_type' => 'cccd',
                'document_number' => '07940000000' . $sellerId,
                'document_front' => 'demo/kyc/seller_' . $sellerId . '_front.jpg',
                'document_back' => 'demo/kyc/seller_' . $sellerId . '_back.jpg',
                'selfie_image' => 'demo/kyc/seller_' . $sellerId . '_selfie.jpg',

                'company' => $item['company']['legal_name'],
                'position' => 'Chủ doanh nghiệp',
                'experience' => 'Kinh nghiệm vận hành và phát triển doanh nghiệp.',
                'goal' => 'Tìm nhà đầu tư hoặc chuyển nhượng.',
                'phone' => '09200000' . $sellerId,

                'status' => 'approved',
                'rejection_reason' => null,
                'created_at' => $now,
                'updated_at' => $now,
            ]);

            DB::table('kyc_verifications')->insert([
                'user_id' => $sellerId,
                'document_type' => 'cccd',
                'document_number' => '07940000000' . $sellerId,
                'document_front' => 'demo/kyc/seller_' . $sellerId . '_front.jpg',
                'document_back' => 'demo/kyc/seller_' . $sellerId . '_back.jpg',
                'selfie_image' => 'demo/kyc/seller_' . $sellerId . '_selfie.jpg',
                'status' => 'approved',
                'rejection_reason' => null,
                'created_at' => $now,
                'updated_at' => $now,
            ]);

            $companyId = DB::table('companies')->insertGetId([
                'seller_id' => $sellerId,
                'legal_name' => $item['company']['legal_name'],
                'tax_code' => $item['company']['tax_code'],
                'business_registration_number' => $item['company']['business_registration_number'],
                'address' => $item['company']['address'],
                'legal_representative_name' => $item['company']['legal_representative_name'],
                'industry' => $item['company']['industry'],
                'description' => $item['company']['description'],
                'verification_status' => 'verified',
                'rejection_reason' => null,
                'created_at' => $now,
                'updated_at' => $now,
            ]);

            DB::table('company_documents')->insert([
                [
                    'company_id' => $companyId,
                    'uploaded_by' => $sellerId,
                    'document_type' => 'business_license',
                    'file_name' => 'Giấy phép đăng ký kinh doanh.pdf',
                    'file_path' => 'demo/company_documents/company_' . $companyId . '/business_license.pdf',
                    'status' => 'approved',
                    'note' => 'Tài liệu mẫu đã được duyệt.',
                    'created_at' => $now,
                    'updated_at' => $now,
                ],
                [
                    'company_id' => $companyId,
                    'uploaded_by' => $sellerId,
                    'document_type' => 'financial_statement',
                    'file_name' => 'Báo cáo tài chính gần nhất.pdf',
                    'file_path' => 'demo/company_documents/company_' . $companyId . '/financial_statement.pdf',
                    'status' => 'approved',
                    'note' => 'Tài liệu mẫu phục vụ kiểm tra hồ sơ.',
                    'created_at' => $now,
                    'updated_at' => $now,
                ],
            ]);

            $deal = $item['deal'];

            $dealId = DB::table('deals')->insertGetId([
                'seller_id' => $sellerId,
                'company_id' => $companyId,
                'title' => $deal['title'],
                'deal_type' => $deal['deal_type'],
                'industry' => $item['company']['industry'],
                'location' => $item['company']['address'],
                'short_description' => 'Deal mẫu thuộc ngành ' . $item['company']['industry'],
                'full_description' => 'Thông tin chi tiết deal mẫu.',
                'target_amount' => $deal['target_amount'],
                'valuation' => $deal['valuation'],
                'equity_offered_percent' => $deal['equity_offered_percent'],
                'min_ticket' => $deal['min_ticket'],
                'allow_multiple_investors' => (bool) $deal['allow_multiple_investors'],
                'currency' => 'VND',
                'status' => 'active',
                'committed_amount' => 0,
                'confirmed_amount' => 0,
                'rejection_reason' => null,
                'published_at' => $now,
                'closed_at' => null,
                'created_at' => $now,
                'updated_at' => $now,
            ]);

            DB::table('company_financials')->insert([
                [
                    'company_id' => $companyId,
                    'financial_year' => 2023,
                    'revenue' => 12000000000 + ($index * 2000000000),
                    'ebitda' => 1800000000 + ($index * 300000000),
                    'net_profit' => 900000000 + ($index * 200000000),
                    'created_at' => $now,
                    'updated_at' => $now,
                ],
                [
                    'company_id' => $companyId,
                    'financial_year' => 2024,
                    'revenue' => 16000000000 + ($index * 2500000000),
                    'ebitda' => 2400000000 + ($index * 350000000),
                    'net_profit' => 1200000000 + ($index * 250000000),
                    'created_at' => $now,
                    'updated_at' => $now,
                ],
            ]);

            DB::table('data_room_files')->insert([
                [
                    'uploaded_by' => $sellerId,
                    'deal_id' => $dealId,
                    'document_type' => 'financial_statement',
                    'file_name' => 'Báo cáo tài chính chi tiết.pdf',
                    'file_path' => 'demo/data_room/deal_' . $dealId . '/financial_statement.pdf',
                    'allow_download' => false,
                    'created_at' => $now,
                    'updated_at' => $now,
                ],
                [
                    'uploaded_by' => $sellerId,
                    'deal_id' => $dealId,
                    'document_type' => 'legal_document',
                    'file_name' => 'Hồ sơ pháp lý doanh nghiệp.pdf',
                    'file_path' => 'demo/data_room/deal_' . $dealId . '/legal_documents.pdf',
                    'allow_download' => false,
                    'created_at' => $now,
                    'updated_at' => $now,
                ],
                [
                    'uploaded_by' => $sellerId,
                    'deal_id' => $dealId,
                    'document_type' => 'business_plan',
                    'file_name' => 'Kế hoạch kinh doanh.pdf',
                    'file_path' => 'demo/data_room/deal_' . $dealId . '/business_plan.pdf',
                    'allow_download' => true,
                    'created_at' => $now,
                    'updated_at' => $now,
                ],
            ]);
        }

        /*
        |--------------------------------------------------------------------------
        | Demo notifications
        |--------------------------------------------------------------------------
        */
        DB::table('notifications')->insert([
            [
                'related_deal_id' => null,
                'user_id' => $adminId,
                'type' => 'system',
                'title' => 'Dữ liệu mẫu đã được tạo',
                'message' => 'Hệ thống đã tạo admin, support, buyer, seller, công ty và deal mẫu.',
                'is_read' => false,
                'created_at' => $now,
                'updated_at' => $now,
            ],
        ]);
    }
}