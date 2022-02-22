<?php

declare(strict_types=1);

namespace Jascha030\Localphp\Process\Binary;

use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Process\Exception\ProcessFailedException;
use Symfony\Component\Process\Process;

final class PhpBinary extends BinaryAbstract
{
    private ?string $version;

    public function __construct(private string $path, OutputInterface $output)
    {
        parent::__construct($output);
    }

    /**
     * @throws ProcessFailedException
     */
    public function getVersion(): string
    {
        return $this->version ?? $this->version = $this->matchVersion($this->createProcess('-v'));
    }

    public function getPath(): string
    {
        return $this->path;
    }

    /**
     * @throws ProcessFailedException
     */
    private function matchVersion(Process $process): string
    {
        $process->mustRun();

        $match = preg_match(
            '/[Pp][Hh][Pp] (\d.\d.\d)/',
            $process->getOutput(),
            $matches
        );

        return in_array($match, [0, false], true)
            ? throw new ProcessFailedException($process)
            : $matches[1];
    }
}
