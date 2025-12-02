<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Pagination\Paginator;   // <--- tambah ini

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        // supaya {{ $schools->links() }} pakai style Bootstrap
        Paginator::useBootstrapFive();   // atau useBootstrapFour();
    }
}
