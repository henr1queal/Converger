<?php

namespace App\Providers;

use App\Models\Event;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class PermissionsServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        View::composer('*', function ($view) {
            if (Auth()->check()) {
                // Para eventos vencidos
                $eventsExpiredIds = Event::whereHas('fiscalNote', function ($query) {
                    $query->where('paid', 0);
                })
                    ->where('payment_term', '<', now())
                    ->where('status', 2)
                    ->pluck('id')
                    ->toArray();

                // Para eventos futuros
                $eventsFutureIds = Event::whereHas('fiscalNote', function ($query) {
                    $query->where('paid', 0);
                })
                    ->where('payment_term', '>=', now())
                    ->where('payment_term', '<=', now()->addDays(3))
                    ->where('status', 2)
                    ->pluck('id')
                    ->toArray();

                $userPermissions = auth()->user()->features;
                
                if(auth()->user()->position_id === 1) {
                    $userCeo = true;
                } else {
                    $userCeo = false;
                }

                $view->with(['userPermissions' => $userPermissions, 'eventsExpiredIds' => $eventsExpiredIds, 'eventsFutureIds' => $eventsFutureIds, 'userCeo' => $userCeo]);
            } else {
                return redirect('login');
            };
        });
    }
}
