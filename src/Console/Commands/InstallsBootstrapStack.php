<?php

namespace Voorhof\Bries\Console\Commands;

//use function Voorhof\Bries\Console\app_path;
//use function Voorhof\Bries\Console\resource_path;

trait InstallsBootstrapStack
{
    /**
     * Install the Bootstrap Blade stack.
     *
     * @return int|null
     */
    protected function InstallsBootstrapStack(): ?int
    {
        // Check if dark mode is disabled
        if (! $this->option('dark')) {
            copy(__DIR__.'/../../stubs/no-dark-mode/vite.config.js', base_path('vite.config.js'));
            copy(__DIR__.'/../../stubs/no-dark-mode/views/cheatsheet.blade.php', resource_path('views/cheatsheet.blade.php'));
            copy(__DIR__.'/../../stubs/no-dark-mode/resources/scss/_cheatsheet.scss', resource_path('scss/_cheatsheet.scss'));
            copy(__DIR__.'/../../stubs/no-dark-mode/resources/scss/bootstrap.scss', resource_path('scss/bootstrap.scss'));

            (new Filesystem)->delete(resource_path('js/color-modes.js'));
        }

        return 1;
    }
}
