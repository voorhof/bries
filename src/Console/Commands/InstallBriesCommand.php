<?php

namespace Voorhof\Bries\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Contracts\Console\PromptsForMissingInput;
use Illuminate\Filesystem\Filesystem;
use RuntimeException;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Process\Process;

use function Laravel\Prompts\select;

#[AsCommand(name: 'bries:install')]
class InstallBriesCommand extends Command implements PromptsForMissingInput
{
    /**
     * The name and signature of the command.
     *
     * @var string
     */
    protected $signature = 'bries:install
                                {dark : Indicate that dark mode support should be installed}
                                {grid : Indicate that CSS grid classes should be installed}
                                {cheatsheet : Indicate that a cheatsheet page should be installed}
                                {pest : Indicate that Pest should be installed}
                                {--composer=global : Absolute path to the Composer binary which should be used to install packages}';

    /**
     * The command description.
     *
     * @var string
     */
    protected $description = 'Install the bootstrap starter kit.';

    /**
     * Execute the command.
     */
    public function handle(): ?int
    {
        return $this->InstallsBootstrapStack();
    }

    /**
     * Prompt for user input arguments.
     */
    protected function promptForMissingArgumentsUsing(): array
    {
        return [
            'dark' => fn () => select(
                label: 'Would you like to install CSS dark mode classes?',
                options: [
                    1 => 'Yes',
                    0 => 'No',
                ],
                default: 1,
            ),
            'grid' => fn () => select(
                label: 'Would you like to install CSS grid classes?',
                options: [
                    1 => 'Yes',
                    0 => 'No',
                ],
                default: 0,
            ),
            'cheatsheet' => fn () => select(
                label: 'Would you like to include a Bootstrap CSS cheatsheet page?',
                options: [
                    1 => 'Yes',
                    0 => 'No',
                ],
                default: 1,
            ),
            'pest' => fn () => select(
                label: 'Which testing framework do you prefer?',
                options: [
                    1 => 'Pest',
                    0 => 'PHPUnit',
                ],
                default: 1,
            ),
        ];
    }

    /**
     * Install the Bootstrap CSS stack.
     */
    protected function InstallsBootstrapStack(): ?int
    {
        // Start installation
        $this->components->info('(step 0/4) Starting installation...');

        // Copy files
        $this->components->info('(step 1/4) Copying starter kit files...');
        if (! $this->copyFiles()) {
            $this->components->error('File copy failed!');

            return 1;
        }

        // Setup testing
        $this->components->info('(step 2/4) Setting up testunit...');
        if (! $this->installTests()) {
            $this->components->error('Installation testunit failed!');

            return 1;
        }

        $this->line('');

        // NPM Packages
        $this->components->info('(step 3/4) Updating node packages...');
        $this->updateNodePackages(function () {
            return [
                '@popperjs/core' => '^2.11.8',
                'autoprefixer' => '^10.4.21',
                'axios' => '^1.8.2',
                'bootstrap' => '^5.3.7',
                'bootstrap-icons' => '^1.13.1',
                'concurrently' => '^9.0.1',
                'laravel-vite-plugin' => '^1.2.0',
                'postcss' => '^8.5.6',
                'sass' => '^1.89.2',
                'vite' => '^6.2.4',
            ];
        });

        // Compile
        $this->components->info('(step 4/4) Compiling node packages...');
        if (! $this->compileNodePackages()) {
            $this->components->error('Compiling failed!');

            return 1;
        }

        // End installation
        $this->components->success('Installation successful!');

        return 0;
    }

    /**
     * Copy starter kit files.
     */
    protected function copyFiles(): bool
    {
        // App
        // // Controllers
        (new Filesystem)->ensureDirectoryExists(app_path('Http/Controllers'));
        (new Filesystem)->copyDirectory(__DIR__.'/../../../stubs/default/app/Http/Controllers', app_path('Http/Controllers'));

        // // Requests
        (new Filesystem)->ensureDirectoryExists(app_path('Http/Requests'));
        (new Filesystem)->copyDirectory(__DIR__.'/../../../stubs/default/app/Http/Requests', app_path('Http/Requests'));

        // // Components
        (new Filesystem)->ensureDirectoryExists(app_path('View/Components'));
        (new Filesystem)->copyDirectory(__DIR__.'/../../../stubs/default/app/View/Components', app_path('View/Components'));

        // Resources
        // // JS
        (new Filesystem)->ensureDirectoryExists(resource_path('js'));
        (new Filesystem)->copyDirectory(__DIR__.'/../../../stubs/default/resources/js', resource_path('js'));

        // // SCSS (remove existing CSS)
        (new Filesystem)->deleteDirectory(resource_path('css'));
        (new Filesystem)->ensureDirectoryExists(resource_path('scss'));
        (new Filesystem)->copyDirectory(__DIR__.'/../../../stubs/default/resources/scss', resource_path('scss'));

        // // Views
        (new Filesystem)->ensureDirectoryExists(resource_path('views'));
        (new Filesystem)->copyDirectory(__DIR__.'/../../../stubs/default/resources/views', resource_path('views'));

        // Routes
        (new Filesystem)->ensureDirectoryExists(base_path('routes'));
        copy(__DIR__.'/../../../stubs/default/routes/web.php', base_path('routes/web.php'));
        copy(__DIR__.'/../../../stubs/default/routes/auth.php', base_path('routes/auth.php'));

        // Vite
        copy(__DIR__.'/../../../stubs/default/postcss.config.js', base_path('postcss.config.js'));
        copy(__DIR__.'/../../../stubs/default/vite.config.js', base_path('vite.config.js'));

        // Cheatsheet option
        if ($this->argument('cheatsheet')) {
            copy(__DIR__.'/../../../stubs/cheatsheet/app/Http/Controllers/PageController.php', app_path('Http/Controllers/PageController.php'));
            copy(__DIR__.'/../../../stubs/cheatsheet/resources/views/layouts/navbar.blade.php', resource_path('views/layouts/navbar.blade.php'));
            copy(__DIR__.'/../../../stubs/cheatsheet/routes/web.php', base_path('routes/web.php'));

            if ($this->argument('dark')) {
                copy(__DIR__.'/../../../stubs/dark/resources/views/cheatsheet.blade.php', resource_path('views/cheatsheet.blade.php'));
            } else {
                copy(__DIR__.'/../../../stubs/cheatsheet/resources/views/cheatsheet.blade.php', resource_path('views/cheatsheet.blade.php'));
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
     * Copy testsuite files based on the given argument.
     */
    protected function installTests(): bool
    {
        (new Filesystem)->ensureDirectoryExists(base_path('tests'));

        if ($this->argument('pest') || $this->isUsingPest()) {
            if ($this->hasComposerPackage('phpunit/phpunit')) {
                $this->removeComposerPackages(['phpunit/phpunit'], true);
            }

            if (! $this->requireComposerPackages(['pestphp/pest', 'pestphp/pest-plugin-laravel'], true)) {
                return false;
            }

            (new Filesystem)->copyDirectory(__DIR__.'/../../../stubs/default/tests-pest', base_path('tests'));
        } else {
            (new Filesystem)->copyDirectory(__DIR__.'/../../../stubs/default/tests', base_path('tests'));
        }

        return true;
    }

    /**
     * Determine whether the project is already using Pest.
     */
    protected function isUsingPest(): bool
    {
        return class_exists(\Pest\TestSuite::class);
    }

    /**
     * Determine if the given Composer package is installed.
     */
    protected function hasComposerPackage(string $package): bool
    {
        $packages = json_decode(file_get_contents(base_path('composer.json')), true);

        return array_key_exists($package, $packages['require'] ?? [])
            || array_key_exists($package, $packages['require-dev'] ?? []);
    }

    /**
     * Installs the given Composer Packages into the application.
     */
    protected function requireComposerPackages(array $packages, bool $asDev = false): bool
    {
        $composer = $this->option('composer');

        if ($composer !== 'global') {
            $command = ['php', $composer, 'require'];
        }

        $command = array_merge(
            $command ?? ['composer', 'require'],
            $packages,
            $asDev ? ['--dev'] : [],
        );

        return (new Process($command, base_path(), ['COMPOSER_MEMORY_LIMIT' => '-1']))
            ->setTimeout(null)
            ->run(function ($type, $output) {
                $this->output->write($output);
            }) === 0;
    }

    /**
     * Removes the given Composer Packages from the application.
     */
    protected function removeComposerPackages(array $packages, bool $asDev = false): bool
    {
        $composer = $this->option('composer');

        if ($composer !== 'global') {
            $command = ['php', $composer, 'remove'];
        }

        $command = array_merge(
            $command ?? ['composer', 'remove'],
            $packages,
            $asDev ? ['--dev'] : [],
        );

        return (new Process($command, base_path(), ['COMPOSER_MEMORY_LIMIT' => '-1']))
            ->setTimeout(null)
            ->run(function ($type, $output) {
                $this->output->write($output);
            }) === 0;
    }

    /**
     * Update the dependencies in the "package.json" file.
     */
    protected static function updateNodePackages(callable $callback, bool $dev = true): void
    {
        if (! file_exists(base_path('package.json'))) {
            return;
        }

        $configurationKey = $dev ? 'devDependencies' : 'dependencies';

        $packages = json_decode(file_get_contents(base_path('package.json')), true);

        $packages[$configurationKey] = $callback(
            array_key_exists($configurationKey, $packages) ? $packages[$configurationKey] : [],
            $configurationKey
        );

        ksort($packages[$configurationKey]);

        file_put_contents(
            base_path('package.json'),
            json_encode($packages, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT).PHP_EOL
        );
    }

    /**
     * Compile the node dependencies with Vite.
     */
    protected function compileNodePackages(): bool
    {
        if (file_exists(base_path('pnpm-lock.yaml'))) {
            $this->runCommands(['pnpm install', 'pnpm run build']);
        } elseif (file_exists(base_path('yarn.lock'))) {
            $this->runCommands(['yarn install', 'yarn run build']);
        } elseif (file_exists(base_path('bun.lock')) || file_exists(base_path('bun.lockb'))) {
            $this->runCommands(['bun install', 'bun run build']);
        } elseif (file_exists(base_path('deno.lock'))) {
            $this->runCommands(['deno install', 'deno task build']);
        } else {
            $this->runCommands(['npm install', 'npm run build']);
        }

        return true;
    }

    /**
     * Run the given commands.
     */
    protected function runCommands(array $commands): void
    {
        $process = Process::fromShellCommandline(implode(' && ', $commands), null, null, null, null);

        if ('\\' !== DIRECTORY_SEPARATOR && file_exists('/dev/tty') && is_readable('/dev/tty')) {
            try {
                $process->setTty(true);
            } catch (RuntimeException $e) {
                $this->output->writeln('  <bg=yellow;fg=black> WARN </> '.$e->getMessage().PHP_EOL);
            }
        }

        $process->run(function ($type, $line) {
            $this->output->write('    '.$line);
        });
    }

    /**
     * Replace a given string within a given file.
     */
    protected function replaceInFile(string $search, string $replace, string $path): void
    {
        file_put_contents($path, str_replace($search, $replace, file_get_contents($path)));
    }
}
