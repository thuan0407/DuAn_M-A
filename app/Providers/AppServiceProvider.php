<?php

namespace App\Providers;

use App\Models\Company;
use App\Models\Deal;
use App\Models\KycVerification;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
public function boot(): void
{
    View::composer('buyer.*', function ($view) {

        $kyc = null;

        if (auth()->check()) {
            $kyc = KycVerification::where('user_id', auth()->id())->first();
        }

        $view->with([
            'kyc' => $kyc,
        ]);
    });

    // 🔥 Cái này nếu cần dùng global thì giữ
    View::composer('*', function ($view) {
        $view->with([
            'pending_companies_count' => Company::where('verification_status', 'pending')->count(),
            'pending_deals_count' => Deal::where('status', 'pending_review')->count(),
            'pending_kyc_count' => KycVerification::where('status', 'pending')->count(),
        ]);
    });
}
}