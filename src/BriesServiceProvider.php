<?php

namespace Voorhof\Bries;

use Voorhof\Bries\Console\Commands\InstallBriesCommand;
use Illuminate\Contracts\Support\DeferrableProvider;
use Illuminate\Support\ServiceProvider;

class BriesServiceProvider extends ServiceProvider implements DeferrableProvider
{
/**
 * Register services.
 *
 * @return void
 */
public function register()
{
    //
}



/**
 * Bootstrap services.
 *
 * @return void
 */
public function boot(): void
{
    if ($this->app->runningInConsole()) {
        $this->commands([
            InstallBriesCommand::class,
        ]);
    }
}



/**
 * DeferrableProvider services.
 *
 * @return array
 */
public function provides(): array
{
    return [
        InstallBriesCommand::class,
    ];
}
}
