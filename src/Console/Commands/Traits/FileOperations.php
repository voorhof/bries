<?php

namespace Voorhof\Bries\Console\Commands\Traits;

use Illuminate\Filesystem\Filesystem;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * File Operations Trait
 *
 * Manages file copy during Bries installation.
 *
 * @package Voorhof\Bries\Console\Commands
 * @property-read OutputInterface $output
 * @method void error(string $message)
 * @method void info(string $message)
 */
trait FileOperations
{
    private Filesystem $filesystem;

    protected function initializeFileSystem(): void
    {
        $this->filesystem = new Filesystem;
    }

    /**
     * Copy starter kit files.
     */
    protected function copyFiles(): bool
    {
        // App
        // // Controllers
        (new Filesystem)->ensureDirectoryExists(app_path('Http/Controllers'));
        (new Filesystem)->copyDirectory(__DIR__.'/../../../../stubs/default/app/Http/Controllers', app_path('Http/Controllers'));

        // // Requests
        (new Filesystem)->ensureDirectoryExists(app_path('Http/Requests'));
        (new Filesystem)->copyDirectory(__DIR__.'/../../../../stubs/default/app/Http/Requests', app_path('Http/Requests'));

        // // Components
        (new Filesystem)->ensureDirectoryExists(app_path('View/Components'));
        (new Filesystem)->copyDirectory(__DIR__.'/../../../../stubs/default/app/View/Components', app_path('View/Components'));

        // Resources
        // // JS
        (new Filesystem)->ensureDirectoryExists(resource_path('js'));
        (new Filesystem)->copyDirectory(__DIR__.'/../../../../stubs/default/resources/js', resource_path('js'));

        // // SCSS (remove existing CSS)
        (new Filesystem)->deleteDirectory(resource_path('css'));
        (new Filesystem)->ensureDirectoryExists(resource_path('scss'));
        (new Filesystem)->copyDirectory(__DIR__.'/../../../../stubs/default/resources/scss', resource_path('scss'));

        // // Views
        (new Filesystem)->ensureDirectoryExists(resource_path('views'));
        (new Filesystem)->copyDirectory(__DIR__.'/../../../../stubs/default/resources/views', resource_path('views'));

        // Routes
        (new Filesystem)->ensureDirectoryExists(base_path('routes'));
        copy(__DIR__.'/../../../../stubs/default/routes/web.php', base_path('routes/web.php'));
        copy(__DIR__.'/../../../../stubs/default/routes/auth.php', base_path('routes/auth.php'));

        // Vite
        copy(__DIR__.'/../../../../stubs/default/postcss.config.js', base_path('postcss.config.js'));
        copy(__DIR__.'/../../../../stubs/default/vite.config.js', base_path('vite.config.js'));

        // Cheatsheet option
        if ($this->argument('cheatsheet')) {
            copy(__DIR__.'/../../../../stubs/cheatsheet/app/Http/Controllers/PageController.php', app_path('Http/Controllers/PageController.php'));
            copy(__DIR__.'/../../../../stubs/cheatsheet/resources/views/layouts/navbar.blade.php', resource_path('views/layouts/navbar.blade.php'));
            copy(__DIR__.'/../../../../stubs/cheatsheet/routes/web.php', base_path('routes/web.php'));

            if ($this->argument('dark')) {
                copy(__DIR__.'/../../../../stubs/dark/resources/views/cheatsheet.blade.php', resource_path('views/cheatsheet.blade.php'));
            } else {
                copy(__DIR__.'/../../../../stubs/cheatsheet/resources/views/cheatsheet.blade.php', resource_path('views/cheatsheet.blade.php'));
            }
        }

        // CSS dark mode option
        if ($this->argument('dark')) {
            $this->replaceInFile('$enable-dark-mode: false;', '$enable-dark-mode: true;', resource_path('scss/bootstrap.scss'));
        }

        // CSS grid option
        if ($this->argument('grid')) {
            $this->replaceInFile('$enable-cssgrid: false;', '$enable-cssgrid: true;', resource_path('scss/bootstrap.scss'));
        }

        return true;
    }

    /**
     * Replace a given string within a given file.
     */
    protected function replaceInFile(string $search, string $replace, string $path): void
    {
        file_put_contents($path, str_replace($search, $replace, file_get_contents($path)));
    }
}
