<?php

declare(strict_types=1);

namespace Jascha030\Localphp\Binary;

interface BinaryInterface
{
    public function getPath(): string;

    public function __invoke(string $command, string $cwd);
}
