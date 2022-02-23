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

final class RunCommand extends Command
{
    private const COMMAND_NAME = 'run';

    public function __construct(private LocalWPService $localWPService)
    {
        parent::__construct(self::COMMAND_NAME);
    }

    public function configure(): void
    {
        $this->setName(self::COMMAND_NAME)
            ->setDescription('Run commands with a specified version available to LocalWP, similar to `php -r [subcommand]`.')
            ->addArgument('subcommand', InputArgument::OPTIONAL, 'The php command to execute.')
            ->addOption('use', 'u', InputOption::VALUE_REQUIRED, 'The php version to use.')
            ->addOption('list', 'l', InputOption::VALUE_NONE, 'List available versions')
            ->addOption('silent', 's', InputOption::VALUE_NONE, 'Don\'t write command output to console.')
            ->addOption('working-dir', 'w', InputOption::VALUE_OPTIONAL, 'Execute command in a specified directory.');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        if ($input->getOption('list')) {
            return $this->list($input, $output);
        }

        return $this->do($input, $output);
    }

    private function do(InputInterface $input, OutputInterface $output): int
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

        $subcommand = $input->getArgument('subcommand');

        if (! $subcommand) {
            $subcommand = '-v';
        }

        /** @var PhpBinary $php */
        $php = $binaries[$version];

        return $php(
            $php::sanitizeForRunFlag($subcommand),
            $input->getOption('working-dir'),
            $input->getOption('silent') ?? false
        );
    }

    private function list(InputInterface $input, OutputInterface $output): int
    {
        $versions = $this->localWPService->getAvailablePhpVersions();

        foreach ($versions as $version => $binary) {
            $output->writeln("* {$version}");
        }

        return Command::SUCCESS;
    }
}
