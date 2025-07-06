<?php

namespace Voorhof\Bries\Console\Commands\Traits;

use Illuminate\Filesystem\Filesystem;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * File Operations Trait
 *
 * Manages file copy during Bries installation.
 *
 * @property-read OutputInterface $output
 * @method void error(string $message)
 * @method void info(string $message)
 */
trait FileOperations
{
    private Filesystem $filesystem;

    private string $stubPath = __DIR__.'/../../../../stubs';

    protected function initializeFileSystem(): void
    {
        $this->filesystem = new Filesystem;
    }

    /**
     * Copy starter kit files to their respective locations.
     *
     * @return bool Success status
     */
    protected function copyFiles(): bool
    {
        $this->initializeFileSystem();

        // App
        // // Controllers
        $this->filesystem->ensureDirectoryExists(app_path('Http/Controllers'));
        $this->filesystem->copyDirectory($this->stubPath . '/default/app/Http/Controllers', app_path('Http/Controllers'));

        // // Requests
        $this->filesystem->ensureDirectoryExists(app_path('Http/Requests'));
        $this->filesystem->copyDirectory($this->stubPath . '/default/app/Http/Requests', app_path('Http/Requests'));

        // // Components
        $this->filesystem->ensureDirectoryExists(app_path('View/Components'));
        $this->filesystem->copyDirectory($this->stubPath . '/default/app/View/Components', app_path('View/Components'));

        // Resources
        // // JS
        $this->filesystem->ensureDirectoryExists(resource_path('js'));
        $this->filesystem->copyDirectory($this->stubPath . '/default/resources/js', resource_path('js'));

        // // SCSS (remove existing CSS)
        $this->filesystem->deleteDirectory(resource_path('css'));
        $this->filesystem->ensureDirectoryExists(resource_path('scss'));
        $this->filesystem->copyDirectory($this->stubPath . '/default/resources/scss', resource_path('scss'));

        // // Views
        $this->filesystem->ensureDirectoryExists(resource_path('views'));
        $this->filesystem->copyDirectory($this->stubPath . '/default/resources/views', resource_path('views'));

        // Routes
        $this->filesystem->ensureDirectoryExists(base_path('routes'));
        copy($this->stubPath . '/default/routes/web.php', base_path('routes/web.php'));
        copy($this->stubPath . '/default/routes/auth.php', base_path('routes/auth.php'));

        // Vite
        copy($this->stubPath . '/default/postcss.config.js', base_path('postcss.config.js'));
        copy($this->stubPath . '/default/vite.config.js', base_path('vite.config.js'));

        // Cheatsheet option
        if ($this->argument('cheatsheet')) {
            copy($this->stubPath . '/cheatsheet/app/Http/Controllers/PageController.php', app_path('Http/Controllers/PageController.php'));
            copy($this->stubPath . '/cheatsheet/resources/views/layouts/navbar.blade.php', resource_path('views/layouts/navbar.blade.php'));
            copy($this->stubPath . '/cheatsheet/routes/web.php', base_path('routes/web.php'));

            if ($this->argument('dark')) {
                copy($this->stubPath . '/dark/resources/views/cheatsheet.blade.php', resource_path('views/cheatsheet.blade.php'));
            } else {
                copy($this->stubPath . '/cheatsheet/resources/views/cheatsheet.blade.php', resource_path('views/cheatsheet.blade.php'));
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
     * Replace a given string within a file.
     *
     * @param string $search Search string
     * @param string $replace Replacement string
     * @param string $path File path
     */
    protected function replaceInFile(string $search, string $replace, string $path): void
    {
        file_put_contents($path, str_replace($search, $replace, file_get_contents($path)));
    }
}
