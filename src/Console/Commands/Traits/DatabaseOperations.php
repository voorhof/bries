<?php

namespace Voorhof\Bries\Console\Commands\Traits;

use Illuminate\Support\Facades\Artisan;

/**
 * Database Operations Trait
 *
 * Manages database migration
 */
trait DatabaseOperations
{
    /**
     * Migrate fresh the database.
     *
     * @return bool Success status
     */
    protected function migrateFresh(): bool
    {
        Artisan::call('migrate:fresh');

        return true;
    }
}
