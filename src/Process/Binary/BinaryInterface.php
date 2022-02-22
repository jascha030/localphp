<?php

declare(strict_types=1);

namespace Jascha030\Localphp\Process\Binary;

use Jascha030\Localphp\Process\ExecutableInterface;
use Jascha030\Localphp\Process\VersionableInterface;

interface BinaryInterface extends ExecutableInterface, VersionableInterface
{
    public function getPath(): string;

    public function __invoke(?string $command = null, ?string $cwd = null): int;
}
