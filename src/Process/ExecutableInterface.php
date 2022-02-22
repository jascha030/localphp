<?php

declare(strict_types=1);

namespace Jascha030\Localphp\Process;

use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Process\Process;

interface ExecutableInterface
{
    public function createProcess(?string $command, ?string $cwd = null): Process;

    public function getOutput(): OutputInterface;
}
