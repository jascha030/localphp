<?php

declare(strict_types=1);

namespace Jascha030\Localphp\Process\Binary;

use Jascha030\Localphp\Process\ExecutableInterface;
use Jascha030\Localphp\Process\VersionableInterface;

interface BinaryInterface extends ExecutableInterface, VersionableInterface
{
    /**
     * Execute process with the binary.
     *
     * @param null|string $command subcommand to binary, e.g. for a php binary object, pass `-v`, not `php -v`.
     *
     * @return int|string returns exitcode by default, enable outputToString, to return the process output instead
     * @see BinaryInterface::outputToString
     */
    public function __invoke(?string $command = null, ?string $cwd = null, bool $silent = false): int|string;

    /**
     * Path to the binary, used to reference it in executing processes.
     */
    public function getPath(): string;

    /**
     * When enabled, nothing is written to the OutputInterface, however __invoke() will return the output string
     * instead of the exitcode. If you only want to disable writing to the OutputInterface, use the `$silent` parameter
     * of the __invoke() method.
     *
     * Method can be chained fluently.
     */
    public function outputToString(bool $enabled): BinaryInterface;
}
