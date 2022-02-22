<?php

declare(strict_types=1);

namespace Jascha030\Localphp\Process\Binary;

use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Process\Process;

abstract class BinaryAbstract implements BinaryInterface
{
    public function __construct(private OutputInterface $output)
    {
    }

    public function __invoke(?string $command = null, ?string $cwd = null): int
    {
        $process = $this->start($command, $cwd);

        foreach ($process as $type => $item) {
            $this->out($type, $item);
        }

        return $process->getReturn()->getExitCode();
    }

    public function getOutput(): OutputInterface
    {
        return $this->output;
    }

    public function createProcess(?string $command = null, ?string $cwd = null): Process
    {
        $command = $command ? "{$this->getPath()} {$command}" : $this->getPath();

        return Process::fromShellCommandline($command, $cwd);
    }

    private function start(string $command, ?string $cwd = null): \Generator
    {
        $process = $this->createProcess($command, $cwd);
        $process->start();

        foreach ($process as $type => $output) {
            yield $type => $output;
        }

        return $process;
    }

    private function out(string $type, string $output): void
    {
        $this->output->writeln(
            Process::ERR === $type
                ? sprintf('<error>%s</error>', $output)
                : $output
        );
    }
}
