<?php

namespace Voorhof\Bries;

use Illuminate\Contracts\Support\DeferrableProvider;
use Illuminate\Support\ServiceProvider;
use Voorhof\Bries\Console\Commands\CopyBriesCommand;
use Voorhof\Bries\Console\Commands\InstallBriesCommand;

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
     */
    public function boot(): void
    {
        if ($this->app->runningInConsole()) {
            $this->commands([
                InstallBriesCommand::class,
                CopyBriesCommand::class,
            ]);
        }
    }

    /**
     * DeferrableProvider services.
     */
    public function provides(): array
    {
        return [
            InstallBriesCommand::class,
            CopyBriesCommand::class,
        ];
    }
}
