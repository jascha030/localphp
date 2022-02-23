<?php

declare(strict_types=1);

namespace Jascha030\Localphp\Console\Command;

use Jascha030\Localphp\LocalWPService;
use Jascha030\Localphp\Process\Binary\PhpBinary;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

abstract class PhpCommandAbstract extends Command
{
    public function __construct(string $name, private LocalWPService $localWPService)
    {
        parent::__construct($name);
    }

    abstract public function getCommandDescription(): string;

    abstract public function do(InputInterface $input, OutputInterface $output): int;

    final public function configure(): void
    {
        $this->setDescription($this->getCommandDescription())
            ->addArgument('subcommand', InputArgument::OPTIONAL, 'The php command to execute.')
            ->addOption('use', 'u', InputOption::VALUE_REQUIRED, 'The php version to use.')
            ->addOption('list', 'l', InputOption::VALUE_NONE, 'List available versions')
            ->addOption('silent', 's', InputOption::VALUE_NONE, 'Don\'t write command output to console.')
            ->addOption('working-dir', 'w', InputOption::VALUE_OPTIONAL, 'Execute command in a specified directory.');

        foreach ($this->getOptions() as $option) {
            $this->addOption(...$option);
        }
    }

    protected function validateOptions(InputInterface $input, OutputInterface $output): PhpBinary|int
    {
        $this->localWPService->setOutput($output);

        if (! $input->getOption('use')) {
            $output->writeln('<error>Provide a version using the --use flag.</error>');

            return Command::FAILURE;
        }

        $version  = $input->getOption('use');
        $binaries = $this->localWPService->getAvailablePhpVersions();

        if (! isset($binaries[$version])) {
            $output->writeln('<error>No available versions found</error>');

            return Command::FAILURE;
        }

        return $binaries[$version];
    }

    final protected function list(InputInterface $input, OutputInterface $output): int
    {
        $versions = $this->localWPService->getAvailablePhpVersions();

        foreach ($versions as $version => $binary) {
            $output->writeln("* {$version}");
        }

        return Command::SUCCESS;
    }

    protected function getOptions(): array
    {
        return [];
    }

    final public function execute(InputInterface $input, OutputInterface $output): int
    {
        if ($input->getOption('list')) {
            return $this->list($input, $output);
        }

        return $this->do($input, $output);
    }
}
