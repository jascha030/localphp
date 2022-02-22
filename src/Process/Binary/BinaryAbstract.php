<?php

declare(strict_types=1);

namespace Jascha030\Localphp\Process\Binary;

use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Process\Process;

abstract class BinaryAbstract implements BinaryInterface
{
    private bool $outputAsString;

    public function __construct(private OutputInterface $output)
    {
        $this->outputAsString = false;
    }

    final public function stringOutputEnabled(): bool
    {
        return $this->outputAsString;
    }

    final public function outputToString(bool $enabled): BinaryInterface
    {
        $this->outputAsString = $enabled;

        return $this;
    }

    public function __invoke(?string $command = null, ?string $cwd = null, bool $silent = false): int|string
    {
        if ($this->outputAsString || $silent) {
            return $this->run($command, $cwd)->{$this->returnMethod()}();
        }

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
        $command = $command
            ? "{$this->getPath()} {$command}"
            : $this->getPath();

        return Process::fromShellCommandline($command, $cwd);
    }

    private function run(?string $command = null, ?string $cwd = null): Process
    {
        return $this->createProcess($command, $cwd)->mustRun();
    }

    private function start(?string $command = null, ?string $cwd = null): \Generator
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

    private function returnMethod(): string
    {
        return $this->outputAsString
            ? 'getOutput'
            : 'getExitCode';
    }
}
