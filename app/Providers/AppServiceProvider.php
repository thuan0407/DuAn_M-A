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
        View::composer('*', function ($view) {
            $pending_companies_count = Company::where('verification_status', 'pending')->count();

            $pending_deals_count = Deal::where('status', 'pending_review')->count();

            $pending_kyc_count = KycVerification::where('status', 'pending')->count();

            $kyc = null;

            if (auth()->check()) {
                $kyc = KycVerification::where('user_id', auth()->id())->first();
            }

            $view->with([
                'pending_companies_count' => $pending_companies_count,
                'pending_deals_count' => $pending_deals_count,
                'pending_kyc_count' => $pending_kyc_count,
                'kyc' => $kyc, // ✅ thêm dòng này
            ]);
        });
        }
}