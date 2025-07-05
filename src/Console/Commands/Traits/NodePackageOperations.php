<?php

namespace Voorhof\Bries\Console\Commands\Traits;

use RuntimeException;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Process\Process;

/**
 * Node Package Operations Trait
 *
 * Handles Node.js package management and compilation.
 *
 * @package Voorhof\Bries\Console\Commands
 * @property-read OutputInterface $output
 * @method void error(string $message)
 * @method void info(string $message)
 */
trait NodePackageOperations
{
    /**
     * Update the dependencies in the "package.json" file.
     */
    protected static function updateNodePackages(callable $callback, bool $dev = true): bool
    {
        if (! file_exists(base_path('package.json'))) {
            return false;
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

        return true;
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
}
