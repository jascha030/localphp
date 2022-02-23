<?php

declare(strict_types=1);

namespace Jascha030\Localphp\Console\Command;

use Jascha030\Localphp\LocalWPService;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

final class ComposerCommand extends PhpCommandAbstract
{
    public function __construct(LocalWPService $localWPService)
    {
        parent::__construct('composer', $localWPService);
    }

    public function do(InputInterface $input, OutputInterface $output): int
    {
        $php = $this->validateOptions($input, $output);

        if (is_int($php)) {
            return $php;
        }

        return $php(
            "composer {$input->getArgument('subcommand')}",
            $input->getOption('working-dir'),
            $input->getOption('silent') ?? false
        );
    }
}
