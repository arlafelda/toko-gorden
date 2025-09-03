<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Auth;
use App\Models\KeranjangItem;

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
        $jumlahKeranjang = 0;

        if (Auth::check()) {
            $userId = Auth::id();

            $jumlahKeranjang = KeranjangItem::whereHas('keranjang', function ($query) use ($userId) {
                $query->where('user_id', $userId);
            })->count();
        }

        $view->with('jumlahKeranjang', $jumlahKeranjang);
    });
    }
}
