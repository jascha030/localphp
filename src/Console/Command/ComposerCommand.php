<?php

declare(strict_types=1);

namespace Jascha030\Localphp\Console\Command;

use Exception;
use Jascha030\Localphp\LocalWPService;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

final class ComposerCommand extends PhpCommandAbstract
{
    public function __construct(LocalWPService $localWPService)
    {
        parent::__construct('composer', $localWPService);
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
        $cwd        = $input->getOption('working-dir');
        $silent     = $input->getOption('silent') ?? false;

        $output->write($php("composer {$subcommand}", $cwd, $silent));

        return Command::SUCCESS;
    }

    public function getCommandDescription(): string
    {
        return 'Run `composer [subcommand]` with a specified php binary.';
    }
}
