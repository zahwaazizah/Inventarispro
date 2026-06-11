<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        // Hapus semua binding DomPDF – tidak digunakan lagi
    }

    public function boot(): void
    {
        //
    }
}