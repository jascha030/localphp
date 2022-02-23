<?php

declare(strict_types=1);

namespace Jascha030\Localphp\Process\Binary;

use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Process\Exception\ProcessFailedException;

final class PhpBinary extends BinaryAbstract
{
    private ?string $version;

    public function __construct(private string $path, OutputInterface $output, private string $composerPath)
    {
        parent::__construct($output);
    }

    public function __invoke(?string $command = null, ?string $cwd = null, bool $silent = false): int|string
    {
        if (str_starts_with($command, 'composer')) {
            $command = str_replace('composer', $this->composerPath, $command);
        }

        return parent::__invoke($command, $cwd, $silent);
    }

    public static function sanitizeForRunFlag(string $subcommand): string
    {
        if (! str_ends_with($subcommand, ';')) {
            $subcommand .= ';';
        }

        return sprintf(' -r "%s"', str_replace('"', '\"', $subcommand));
    }

    /**
     * @throws ProcessFailedException
     */
    public function getVersion(): string
    {
        return $this->version ?? $this->version = $this->matchVersion();
    }

    public function getPath(): string
    {
        return $this->path;
    }

    /**
     * @throws ProcessFailedException
     */
    private function matchVersion(): string
    {
        $process = $this->createProcess('-v')->mustRun();

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
