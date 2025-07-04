<?php

namespace Voorhof\Bries\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Contracts\Console\PromptsForMissingInput;
use Symfony\Component\Console\Attribute\AsCommand;

//use Illuminate\Filesystem\Filesystem;
//use RuntimeException;

//use Symfony\Component\Console\Input\InputInterface;
//use Symfony\Component\Console\Output\OutputInterface;
//use Symfony\Component\Process\Process;

//use Voorhof\Bries\Console\InstallsBootstrapStack;
//use function Laravel\Prompts\confirm;
//use function Laravel\Prompts\select;


#[AsCommand(name: 'bries:install')]
class InstallCommand__BACKUP extends Command implements PromptsForMissingInput
{
    use InstallsBootstrapStack;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'bries:install {stack : The development stack that should be installed}
                            {--dark : Indicate that dark mode support should be installed}
                            {--pest : Indicate that Pest should be installed}
                            {--composer=global : Absolute path to the Composer binary which should be used to install packages}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Install the controllers and resources';

    /**
     * Execute the console command.
     *
     * @return int|null
     */
    public function handle(): ?int
    {
        if ($this->argument('stack') === 'bootstrap') {
//            return $this->InstallsBootstrapStack();
        }

//        $this->components->error('Invalid stack. Supported stacks are [bootstrap].');

        return 1;
    }

    /**
     * Replace a given string within a given file.
     *
     * @param string $search
     * @param string $replace
     * @param string $path
     * @return void
     */
    protected function replaceInFile(string $search, string $replace, string $path): void
    {
        file_put_contents($path, str_replace($search, $replace, file_get_contents($path)));
    }



}
