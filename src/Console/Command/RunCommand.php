<?php

declare(strict_types=1);

namespace Jascha030\Localphp\Console\Command;

use Jascha030\Localphp\LocalWPService;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

final class RunCommand extends PhpCommandAbstract
{
    private const COMMAND_NAME = 'run';

    public function __construct(LocalWPService $localWPService)
    {
        parent::__construct(self::COMMAND_NAME, $localWPService);
    }

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

        return $php(
            $php::sanitizeForRunFlag($subcommand),
            $input->getOption('working-dir'),
            $input->getOption('silent') ?? false
        );
    }
}
