<?php

namespace Voorhof\Bries\Console\Commands;

use Exception;
use Illuminate\Console\Command;
use Illuminate\Contracts\Console\PromptsForMissingInput;
use Illuminate\Filesystem\Filesystem;
use Symfony\Component\Console\Attribute\AsCommand;
use Voorhof\Bries\Console\Commands\Traits\ComposerOperations;
use Voorhof\Bries\Console\Commands\Traits\FileOperations;
use Voorhof\Bries\Console\Commands\Traits\NodePackageOperations;

use function Laravel\Prompts\select;

/**
 * Bootstrap Starter Kit Installation Command
 *
 * @property string $signature Command signature with arguments and options
 * @property string $description Command description
 */
#[AsCommand(name: 'bries:install')]
class InstallBriesCommand extends Command implements PromptsForMissingInput
{
    use ComposerOperations, FileOperations, NodePackageOperations;

    /**
     * The command signature with available arguments and options.
     *
     * @var string
     *
     * Arguments:
     *   - dark: Enable dark mode support
     *   - grid: Enable CSS grid classes
     *   - cheatsheet: Include Bootstrap cheatsheet
     *   - pest: Use Pest as the testing framework
     *
     * Options:
     *   - composer: Path to Composer binary
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
     * Install the Bootstrap starter kit components.
     *
     * @return int|null 0 on success, 1 on failure
     * @throws Exception
     */
    public function handle(): ?int
    {
        return $this->installsBootstrapStack();
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
     * Execute the Bootstrap stack installation process.
     *
     * Steps:
     * 1. Copy starter kit files
     * 2. Set up the test framework
     * 3. Update Node.js dependencies
     * 4. Compile assets
     *
     * @return int Exit code (0: success, 1: failure)
     *
     * @throws Exception When any installation step fails
     */
    protected function installsBootstrapStack(): int
    {
        try {
            $steps = [
                ['message' => 'Copying starter kit files...', 'method' => 'copyFiles'],
                ['message' => 'Setting up testunit...', 'method' => 'installTests'],
                ['message' => 'Updating node packages...', 'method' => 'updateNodeDependencies'],
                ['message' => 'Compiling node packages...', 'method' => 'compileNodePackages'],
            ];

            $this->components->info('Starting installation...');

            foreach ($steps as $index => $step) {
                $this->components->info('(step '.($index + 1).'/'.count($steps).") {$step['message']}");

                if (! $this->{$step['method']}()) {
                    return 1;
                }
            }

            $this->components->success('Installation successful!');

            return 0;
        } catch (Exception $e) {
            $this->components->error("Installation failed: {$e->getMessage()}");

            return 1;
        }
    }

    /**
     * Copy testsuite files based on the given argument.
     */
    protected function installTests(): bool
    {
        (new Filesystem)->ensureDirectoryExists(base_path('tests'));

        try {
            if ($this->argument('pest') || $this->isUsingPest()) {
                // Use trait methods for package management
                if ($this->hasComposerPackage('phpunit/phpunit')) {
                    if (! $this->manageComposerPackages(['phpunit/phpunit'], 'remove', true)) {
                        $this->error('Failed to remove PHPUnit');

                        return false;
                    }
                }

                if (! $this->manageComposerPackages(
                    ['pestphp/pest', 'pestphp/pest-plugin-laravel'],
                    'require',
                    true
                )) {
                    $this->error('Failed to install Pest');

                    return false;
                }

                (new Filesystem)->copyDirectory(
                    __DIR__.'/../../../stubs/default/tests-pest',
                    base_path('tests')
                );
            } else {
                (new Filesystem)->copyDirectory(
                    __DIR__.'/../../../stubs/default/tests',
                    base_path('tests')
                );
            }

            return true;
        } catch (Exception $e) {
            $this->error("Test installation failed: {$e->getMessage()}");

            return false;
        }
    }

    /**
     * Determine whether the project is already using Pest.
     */
    protected function isUsingPest(): bool
    {
        return class_exists(\Pest\TestSuite::class);
    }

    /**
     * Check for composer configuration availability
     */
    protected function ensureComposerConfigAvailable(): bool
    {
        if (empty($this->getComposerConfig())) {
            $this->error('Unable to read composer configuration');

            return false;
        }

        return true;
    }

    /**
     * List node dependencies
     */
    protected function updateNodeDependencies(): bool
    {
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

        return true;
    }
}
