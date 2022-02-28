<?php

declare(strict_types=1);

namespace Jascha030\Localphp\Process\Binary;

use Exception;
use Jascha030\CLI\Shell\Binary\BinaryInterface;
use Jascha030\CLI\Shell\Binary\Traits\SelfResolvingVersionTrait;
use Jascha030\CLI\Shell\Binary\Traits\ShellDecoratorTrait;
use Jascha030\CLI\Shell\Shell;
use Jascha030\CLI\Shell\ShellInterface;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;
use function Jascha030\Localphp\container;

final class PhpBinary implements BinaryInterface
{
    use SelfResolvingVersionTrait;
    use ShellDecoratorTrait;

    public function __construct(
        private string $path,
        private string $composerPath,
        private ?ShellInterface $shell = null
    ) {
    }

    public function __invoke(string $command, ?string $cwd = null, bool $silent = false): ?string
    {
        if (str_starts_with($command, 'composer')) {
            $command = str_replace('composer', $this->composerPath, $command);
        }

        if ($silent) {
            $this->quietly($command, $cwd);

            return null;
        }

        return $this->run($command, $cwd);
    }

    public function getName(): string
    {
        return 'php';
    }

    /**
     * {@inheritDoc}
     */
    public function getPath(): string
    {
        return $this->path;
    }

    /**
     * @throws ContainerExceptionInterface|NotFoundExceptionInterface
     * @throws Exception
     */
    public function getShell(): ShellInterface
    {
        if (! isset($this->shell)) {
            $this->shell = container()->get(Shell::class);
        }

        return $this->shell;
    }

    public static function sanitizeForRunFlag(string $subcommand): string
    {
        if (! str_ends_with($subcommand, ';')) {
            $subcommand .= ';';
        }

        return sprintf(' -r "%s"', str_replace('"', '\"', $subcommand));
    }
}
