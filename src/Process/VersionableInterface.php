<?php

declare(strict_types=1);

namespace Jascha030\Localphp\Process;

interface VersionableInterface
{
    public function getVersion(): string;
}
