<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

use Livewire\Livewire;
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
        //
        Livewire::component('cart-transfer', \App\Livewire\CartTransfer::class);


        Livewire::component('cart-show', \App\Livewire\CartShow::class);
         Livewire::component('cart-icon', \App\Livewire\CartIcon::class);




    }
}
