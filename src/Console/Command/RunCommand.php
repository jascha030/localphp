<?php

declare(strict_types=1);

namespace Jascha030\Localphp\Console\Command;

use Exception;
use Jascha030\Localphp\LocalWPService;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

final class RunCommand extends PhpCommandAbstract
{
    private const COMMAND_NAME = 'run';

    public function __construct(LocalWPService $localWPService)
    {
        parent::__construct(self::COMMAND_NAME, $localWPService);
    }

    /**
     * @throws Exception
     */
    public function do(InputInterface $input, OutputInterface $output): int
    {
        $php = $this->validateOptions($input, $output);

        if (is_int($php)) {
            return $php;
        }

        $subcommand = $input->getArgument('subcommand');

        if (! $subcommand) {
            $subcommand = '-v';
        }

        $silent = $input->getOption('silent') ?? false;
        $cwd    = $input->getOption('working-dir');

        $commandOutput = $php($php::sanitizeForRunFlag($subcommand), $cwd, $silent);

        if (! $silent) {
            $output->writeln($commandOutput);
        }

        return Command::SUCCESS;
    }

    public function getCommandDescription(): string
    {
        return 'Equivalent of running `php -r [subcommand]` with a specified php binary.';
    }
}
