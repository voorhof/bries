<?php

namespace Voorhof\Bries\Console\Commands;

use Exception;
use Illuminate\Console\Command;
use Illuminate\Contracts\Console\PromptsForMissingInput;
use Symfony\Component\Console\Attribute\AsCommand;
use Voorhof\Bries\Console\Commands\Traits\ComposerOperations;
use Voorhof\Bries\Console\Commands\Traits\FileOperations;
use Voorhof\Bries\Console\Commands\Traits\NodePackageOperations;
use Voorhof\Bries\Console\Commands\Traits\TestFrameworkOperations;

use function Laravel\Prompts\select;

/**
 * Bootstrap Starter Kit Copy Stubs Command
 *
 * @property string $signature Command signature with arguments and options
 * @property string $description Command description
 */
#[AsCommand(name: 'bries:copy')]
class CopyBriesCommand extends Command implements PromptsForMissingInput
{
    use ComposerOperations,
        FileOperations,
        NodePackageOperations,
        TestFrameworkOperations;

    private const YES_NO_OPTIONS = [
        1 => 'Yes',
        0 => 'No',
    ];

    private const TESTING_FRAMEWORK_OPTIONS = [
        1 => 'Pest',
        0 => 'PHPUnit',
    ];

    private const NODE_DEPENDENCIES = [
        '@popperjs/core' => '^2.11.8',
        'autoprefixer' => '^10.4.21',
        'axios' => '^1.11.0',
        'bootstrap' => '^5.3.7',
        'bootstrap-icons' => '^1.13.1',
        'concurrently' => '^9.0.1',
        'laravel-vite-plugin' => '^1.2.0',
        'postcss' => '^8.5.6',
        'sass' => '^1.89.2',
        'vite' => '^6.2.4',
    ];

    private const INSTALLATION_STEPS = [
        ['message' => 'Copying starter kit files...', 'method' => 'copyFiles'],
        ['message' => 'Setting up testunit...', 'method' => 'installTests'],
        ['message' => 'Updating node packages...', 'method' => 'updateNodeDependencies'],
    ];

    private const INSTALLATION_PROMPTS = [
        'dark' => [
            'label' => 'Would you like to install CSS dark mode classes?',
            'options' => self::YES_NO_OPTIONS,
            'default' => 1,
        ],
        'grid' => [
            'label' => 'Would you like to install CSS grid classes?',
            'options' => self::YES_NO_OPTIONS,
            'default' => 0,
        ],
        'cheatsheet' => [
            'label' => 'Would you like to include a Bootstrap CSS cheatsheet page?',
            'options' => self::YES_NO_OPTIONS,
            'default' => 1,
        ],
        'pest' => [
            'label' => 'Which testing framework do you prefer?',
            'options' => self::TESTING_FRAMEWORK_OPTIONS,
            'default' => 1,
        ],
        'backup' => [
            'label' => 'Would you like to backup the original files?',
            'options' => self::YES_NO_OPTIONS,
            'default' => 0,
        ],
    ];

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
     *   - backup: Back up the original Laravel files
     *
     * Options:
     *   - composer: Path to Composer binary
     */
    protected $signature = 'bries:copy
                                {dark : Indicate that dark mode support should be installed}
                                {grid : Indicate that CSS grid classes should be installed}
                                {cheatsheet : Indicate that a cheatsheet page should be installed}
                                {pest : Indicate that Pest should be installed}
                                {backup : Indicate that original files should have a backup}
                                {--composer=global : Absolute path to the Composer binary which should be used to install packages}';

    /**
     * The command description.
     *
     * @var string
     */
    protected $description = 'Copy the starter kit stubs.';

    /**
     * Copy the Bootstrap starter kit stubs.
     *
     * @return int|null 0 on success, 1 on failure
     *
     * @throws Exception
     */
    public function handle(): ?int
    {
        return $this->copyBootstrapStack();
    }

    /**
     * Prompt for user input arguments.
     */
    protected function promptForMissingArgumentsUsing(): array
    {
        return array_map(
            fn (array $prompt) => fn () => select(
                label: $prompt['label'],
                options: $prompt['options'],
                default: $prompt['default'],
            ),
            self::INSTALLATION_PROMPTS
        );
    }

    /**
     * Execute the Bootstrap stack copy process.
     *
     * Steps:
     * 1. Copy starter kit files
     * 2. Set up the test framework
     * 3. Update Node.js dependencies
     *
     * @return int Exit code (0: success, 1: failure)
     *
     * @throws Exception When any copy step fails
     */
    protected function copyBootstrapStack(): int
    {
        try {
            $this->components->info('Copy Bries stubs...');

            foreach (self::INSTALLATION_STEPS as $index => $step) {
                $this->components->info(sprintf(
                    '(step %d/%d) %s',
                    $index + 1,
                    count(self::INSTALLATION_STEPS),
                    $step['message']
                ));

                if (! $this->{$step['method']}()) {
                    return 1;
                }
            }

            $this->components->success('Bries stubs copy successful!');

            return 0;
        } catch (Exception $e) {
            $this->components->error("Bries stubs copy failed: {$e->getMessage()}");

            return 1;
        }
    }

    /**
     * Update node dependencies
     */
    protected function updateNodeDependencies(): bool
    {
        $this->updateNodePackages(fn () => self::NODE_DEPENDENCIES);

        return true;
    }
}
