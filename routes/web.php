<?php

use App\Http\Controllers\Admin\AdminCompanyController;
use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Admin\AdminDealController;
use App\Http\Controllers\Admin\AdminKycController;
use App\Http\Controllers\Admin\AdminUserController;
use App\Http\Controllers\Buyer\BuyerDealAccessRequestController;
use App\Http\Controllers\Buyer\BuyerDealController;
use App\Http\Controllers\Buyer\BuyerHomeController;
use App\Http\Controllers\Buyer\BuyerInterestController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Seller\SellerAccessRequestController;
use App\Http\Controllers\Seller\SellerCompanyController;
use App\Http\Controllers\Seller\SellerDashboardController;
use App\Http\Controllers\Seller\SellerDealController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Buyer\BuyerOfferController;
use App\Http\Controllers\Buyer\BuyerSuccessfulDealController;
use App\Http\Controllers\Buyer\KycController as BuyerKycController;
use App\Http\Controllers\Seller\KycController as SellerKycController;
use App\Http\Controllers\Seller\SellerOfferController;

Route::get('/', function () {
    if (!Auth::check()) {
        return to_route('login');
    }

    return to_route('dashboard');
})->name('home');

Route::get('/dashboard', function () {
    $user = Auth::user();

    if ($user->role === 'admin') {
        return to_route('admin.dashboard');
    }

    if ($user->role === 'support') {
        return to_route('support.dashboard');
    }

    if ($user->role === 'seller') {
        return to_route('seller.dashboard');
    }

    if ($user->role === 'buyer') {
        return to_route('buyer.home');
    }

    abort(403);
})->middleware(['auth'])->name('dashboard');


// Người mua
Route::middleware(['web', 'auth'])
    ->prefix('buyer')
    ->name('buyer.')
    ->group(function () {

    Route::get('/home', [BuyerHomeController::class, 'index'])->name('home');

    Route::get('/dashboard', function () {
        return to_route('buyer.home');
    })->name('dashboard');

    Route::get('/deals', [BuyerDealController::class, 'index'])->name('deals');

    Route::get('/deals/{deal}/profile', [BuyerDealController::class, 'show'])->name('deals.show');

    Route::get('/interests', [BuyerInterestController::class, 'index'])->name('interests');

    Route::post('/deals/{deal}/interest', [BuyerInterestController::class, 'store'])
        ->name('deals.interest.store');

    Route::delete('/deals/{deal}/interest', [BuyerInterestController::class, 'destroy'])
        ->name('deals.interest.destroy');

    Route::post('/deals/{deal}/access-request',
        [BuyerDealAccessRequestController::class, 'store']
    )->name('deals.access_requests.store');

    Route::get('/about', fn() => view('buyer.about'))->name('about');
    Route::get('/privacy', fn() => view('buyer.privacy'))->name('privacy');

    // SUCCESS DEAL
    Route::get('/successful-deals', [BuyerSuccessfulDealController::class, 'index'])
        ->name('successful_deals.index');

    Route::get('/successful-deals/{offer}', [BuyerSuccessfulDealController::class, 'show'])
        ->name('successful_deals.show');

    // OFFER
    Route::get('/deals/{deal}/offers/create', [BuyerOfferController::class, 'create'])
        ->name('deals.offers.create');

    Route::post('/deals/{deal}/offers', [BuyerOfferController::class, 'store'])
        ->name('deals.offers.store');

   
    Route::get('/kyc/create', [BuyerKycController::class, 'create'])->name('kyc.create');
    Route::post('/kyc', [BuyerKycController::class, 'store'])->name('kyc.store');
    Route::get('/kyc', [BuyerKycController::class, 'show'])->name('kyc.show');

});

//Thông báo
Route::post('/notifications/{id}/read', function ($id) {

    $noti = \App\Models\Notification::where('id', $id)
        ->where('user_id', auth()->id())
        ->firstOrFail();

    $noti->update([
        'is_read' => true
    ]);

    return back();

})->name('notifications.read');


// Người bán
Route::middleware(['web', 'auth'])->prefix('seller')->name('seller.')->group(function () {
    Route::get('/kyc/create', [SellerKycController::class, 'create'])
        ->name('kyc.create');

    Route::get('/kyc', [SellerKycController::class, 'show'])
        ->name('kyc.show');

    Route::post('/kyc', [SellerKycController::class, 'store'])
        ->name('kyc.store');
});

Route::middleware(['web', 'auth', 'kyc.approved'])->prefix('seller')->name('seller.')->group(function () {
    Route::get('/dashboard', [SellerDashboardController::class, 'index'])
        ->name('dashboard');

    Route::get('/company', [SellerCompanyController::class, 'show'])
        ->name('company.show');

    Route::get('/company/create', [SellerCompanyController::class, 'create'])
        ->name('company.create');

    Route::post('/company', [SellerCompanyController::class, 'store'])
        ->name('company.store');

    Route::get('/company/edit', [SellerCompanyController::class, 'edit'])
        ->name('company.edit');

    Route::put('/company', [SellerCompanyController::class, 'update'])
        ->name('company.update');

    Route::post('/company/documents', [SellerCompanyController::class, 'storeDocument'])
        ->name('company.documents.store');

    Route::put('/company/documents/{document}', [SellerCompanyController::class, 'updateDocument'])
        ->name('company.documents.update');

    Route::post('/company/financials', [SellerCompanyController::class, 'storeFinancial'])
        ->name('company.financials.store');

    Route::put('/company/financials/{financial}', [SellerCompanyController::class, 'updateFinancial'])
        ->name('company.financials.update');

    Route::delete('/company/financials/{financial}', [SellerCompanyController::class, 'destroyFinancial'])
        ->name('company.financials.destroy');

    Route::get('/deals', [SellerDealController::class, 'index'])
        ->name('deals.index');

    Route::get('/deals/create', [SellerDealController::class, 'create'])
        ->name('deals.create');

    Route::post('/deals', [SellerDealController::class, 'store'])
        ->name('deals.store');

    Route::get('/deals/{deal}/edit', [SellerDealController::class, 'edit'])
        ->name('deals.edit');

    Route::put('/deals/{deal}', [SellerDealController::class, 'update'])
        ->name('deals.update');

    Route::get('/access-requests', [SellerAccessRequestController::class, 'index'])
        ->name('access_requests.index');

    Route::post('/access-requests/{accessRequest}/approve', [SellerAccessRequestController::class, 'approve'])
        ->name('access_requests.approve');

    Route::post('/access-requests/{accessRequest}/reject', [SellerAccessRequestController::class, 'reject'])
        ->name('access_requests.reject');


    //offer
    Route::get('/offers', [SellerOfferController::class, 'index'])
        ->name('offers.index');

    Route::get('/offers/{offer}', [SellerOfferController::class, 'show'])
        ->name('offers.show');

    Route::post('/offers/{offer}/accept', [SellerOfferController::class, 'accept'])
        ->name('offers.accept');

    Route::post('/offers/{offer}/reject', [SellerOfferController::class, 'reject'])
        ->name('offers.reject');
});


//ADmin
Route::middleware(['auth'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {

    // 1. Trang Tổng quan
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');

    // 2. Quản lý Công ty (Danh sách đã duyệt)
    Route::get('/companies', [AdminCompanyController::class, 'index'])->name('companies.index');
    Route::get('/companies/{company}', [AdminCompanyController::class, 'show'])->name('companies.show');
    Route::post('/companies/{company}/approve', [AdminCompanyController::class, 'approve'])->name('companies.approve');
    Route::post('/companies/{company}/reject', [AdminCompanyController::class, 'reject'])->name('companies.reject');
    Route::post('/companies/{company}/delete', [AdminCompanyController::class, 'delete'])->name('companies.delete');

    // 3. Duyệt Công ty mới (Yêu cầu đang chờ - Pending)
    // Lưu ý: Dùng GET để hiển thị trang danh sách, không dùng POST
    Route::get('/pending-companies', [AdminCompanyController::class, 'pending'])->name('companies.pending');

    // 4. Duyệt bài đăng Deals (Kiểm duyệt bài đăng)
    // Tách riêng trang dành cho các deal đang chờ duyệt
    Route::get('/pending-deals', [AdminDealController::class, 'pending'])->name('deals.pending');
    
    // Quản lý Deals chung
    Route::get('/deals', [AdminDealController::class, 'index'])->name('deals.index');
    Route::get('/deals/{deal}', [AdminDealController::class, 'show'])->name('deals.show');
    Route::post('/deals/{deal}/approve', [AdminDealController::class, 'approve'])->name('deals.approve'); // Duyệt deal
    Route::post('/deals/{deal}/hide', [AdminDealController::class, 'hide'])->name('deals.hide');
    Route::post('/deals/{deal}/delete', [AdminDealController::class, 'delete'])->name('deals.delete');
    Route::post('/deals/{deal}/reject', [AdminDealController::class, 'reject'])->name('deals.reject');

    // 5. Quản lý Người dùng
    Route::get('/users', [AdminUserController::class, 'index'])->name('users.index');
    Route::get('users/show/{user}', [AdminUserController::class, 'show'])->name('users.show');
    Route::post('/users/{user}', [AdminUserController::class, 'lock'])->name('users.lock');
    Route::post('/users/{user}/ban', [AdminUserController::class, 'ban'])->name('users.ban');
    Route::post('/users/{user}/unban', [AdminUserController::class, 'unban'])->name('users.unban');


    // Duyệt hồ sơ người dùng KYC
    Route::get('/kyc/pending', [AdminKycController::class, 'pending'])
        ->name('kyc.pending');

    Route::get('/kyc/{kyc}', [AdminKycController::class, 'show'])
        ->name('kyc.show');

    Route::post('/kyc/{kyc}/approve', [AdminKycController::class, 'approve'])
        ->name('kyc.approve');

    Route::post('/kyc/{kyc}/reject', [AdminKycController::class, 'reject'])
        ->name('kyc.reject');
});

// Người hỗ trợ
Route::middleware(['auth'])->prefix('support')->name('support.')->group(function () {
    Route::get('/dashboard', function () {
        return 'Support dashboard';
    })->name('dashboard');
});


Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__ . '/auth.php';