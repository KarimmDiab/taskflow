<?php

namespace App\Providers;

use App\Models\Category;
use App\Models\Inventory;
use App\Models\OnlineOrder;
use App\Models\SalesInvoice;
use App\Listeners\CreateUserLoginNotification;
use App\Observers\InventoryObserver;
use App\Observers\OnlineOrderObserver;
use App\Observers\SalesInvoiceObserver;
use App\Policies\OrderOnlineDetailsPolicy;
use Carbon\Carbon;
use Carbon\CarbonImmutable;
use Illuminate\Auth\Events\Login;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;
use Illuminate\Validation\Rules\Password;

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
        $this->configureDefaults();
        Carbon::setLocale('ar');
        Gate::policy(OnlineOrder::class, OrderOnlineDetailsPolicy::class);
        OnlineOrder::observe(OnlineOrderObserver::class);
        Inventory::observe(InventoryObserver::class);
        SalesInvoice::observe(SalesInvoiceObserver::class);
        Event::listen(Login::class, CreateUserLoginNotification::class);

        View::composer('partials.footer', function ($view) {

            $footerCategories = Category::where('is_featured', true)
                ->where('is_active', true)
                ->take(5)
                ->get();

            $view->with('footerCategories', $footerCategories);
        });

    }

    /**
     * Configure default behaviors for production-ready applications.
     */
    protected function configureDefaults(): void
    {
        Date::use(CarbonImmutable::class);

        DB::prohibitDestructiveCommands(
            app()->isProduction(),
        );

        Password::defaults(fn (): ?Password => app()->isProduction()
            ? Password::min(12)
                ->mixedCase()
                ->letters()
                ->numbers()
                ->symbols()
                ->uncompromised()
            : null,
        );
    }

}
