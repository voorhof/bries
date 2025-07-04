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
                                {pest : Indicate that Pest should be installed}
                                {cheatsheet : Indicate that a cheatsheet page should be installed}
                                {--composer=global : Absolute path to the Composer binary which should be used to install packages}';

    /**
     * The command description.
     *
     * @var string
     */
    protected $description = 'Install the bootstrap starter kit.';

    /**
     * Execute the command.
     *
     * @return int|null
     */
    public function handle(): ?int
    {
        return $this->InstallsBootstrapStack();
    }

    /**
     * Prompt for user input arguments.
     *
     * @return array
     */
    protected function promptForMissingArgumentsUsing(): array
    {
        return [
            'dark' => fn () => select(
                label: 'Would you like dark mode support?',
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
            'cheatsheet' => fn () => select(
                label: 'Would you like to include a cheatsheet page?',
                options: [
                    1 => 'Yes',
                    0 => 'No',
                ],
                default: 1,
            ),
        ];
    }

    /**
     * Install the Bootstrap CSS stack.
     *
     * @return int|null
     */
    protected function InstallsBootstrapStack(): ?int
    {
        /*
        if ($this->argument('dark')) {
            $this->components->error('DARK OPTION SELECTED');
//            return 1;
        }
        if ($this->argument('pest')) {
            $this->components->error('PEST OPTION SELECTED');
//            return 1;
        }
        if ($this->argument('cheatsheet')) {
            $this->components->error('CHEATSHEET OPTION SELECTED');
//            return 1;
        }
        */

        // Start installation
        $this->components->info('(step 0/XYZ) Starting installation...');

        // Copy files
        $this->components->info('(step 1/XYZ) Copying starter kit files...');
        if (! $this->copyFiles()) {
            $this->components->error('File copy failed!');
            return 1;
        }

        // Setup testing
        $this->components->info ('(step 2/XYZ) Setting up testunit...');
        if (! $this->installTests()) {
            $this->components->error('Installation testunit failed!');
            return 1;
        }

        $this->line('');

        // NPM Packages
        $this->components->info ('(step 3/XYZ) Updating node packages...');
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
        $this->components->info ('(step 4/XYZ) Compiling node packages...');
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
     *
     * @return bool
     */
    protected function copyFiles(): bool
    {
        // App
        //// Controllers
        (new Filesystem)->ensureDirectoryExists(app_path('Http/Controllers'));
        (new Filesystem)->copyDirectory(__DIR__.'/../../../stubs/default/app/Http/Controllers', app_path('Http/Controllers'));

        //// Requests
        (new Filesystem)->ensureDirectoryExists(app_path('Http/Requests'));
        (new Filesystem)->copyDirectory(__DIR__.'/../../../stubs/default/app/Http/Requests', app_path('Http/Requests'));

        //// Components
        (new Filesystem)->ensureDirectoryExists(app_path('View/Components'));
        (new Filesystem)->copyDirectory(__DIR__.'/../../../stubs/default/app/View/Components', app_path('View/Components'));

        // Resources
        //// JS
        (new Filesystem)->ensureDirectoryExists(resource_path('js'));
        (new Filesystem)->copyDirectory(__DIR__.'/../../../stubs/default/resources/js', resource_path('js'));

        //// SCSS (remove existing CSS)
        (new Filesystem)->deleteDirectory(resource_path('css'));
        (new Filesystem)->ensureDirectoryExists(resource_path('scss'));
        (new Filesystem)->copyDirectory(__DIR__.'/../../../stubs/default/resources/scss', resource_path('scss'));

        //// Views
        (new Filesystem)->ensureDirectoryExists(resource_path('views'));
        (new Filesystem)->copyDirectory(__DIR__.'/../../../stubs/default/resources/views', resource_path('views'));

        // Routes
        (new Filesystem)->ensureDirectoryExists(base_path('routes'));
        copy(__DIR__.'/../../../stubs/default/routes/web.php', base_path('routes/web.php'));
        copy(__DIR__.'/../../../stubs/default/routes/auth.php', base_path('routes/auth.php'));

        // Vite
        copy(__DIR__.'/../../../stubs/default/postcss.config.js', base_path('postcss.config.js'));
        copy(__DIR__.'/../../../stubs/default/vite.config.js', base_path('vite.config.js'));

        return true;
    }

    /**
     * Copy test files based on the given argument.
     *
     * @return bool
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
     *
     * @return bool
     */
    protected function isUsingPest(): bool
    {
        return class_exists(\Pest\TestSuite::class);
    }

    /**
     * Determine if the given Composer package is installed.
     *
     * @param string $package
     * @return bool
     */
    protected function hasComposerPackage(string $package): bool
    {
        $packages = json_decode(file_get_contents(base_path('composer.json')), true);

        return array_key_exists($package, $packages['require'] ?? [])
            || array_key_exists($package, $packages['require-dev'] ?? []);
    }

    /**
     * Installs the given Composer Packages into the application.
     *
     * @param array $packages
     * @param bool $asDev
     * @return bool
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
     *
     * @param array $packages
     * @param bool $asDev
     * @return bool
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
     *
     * @param callable $callback
     * @param bool $dev
     * @return void
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
     *
     * @return bool
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
     *
     * @param array $commands
     * @return void
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
}
