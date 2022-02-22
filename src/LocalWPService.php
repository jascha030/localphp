<?php

declare(strict_types=1);

namespace Jascha030\Localphp;

use Symfony\Component\Finder\Finder;

class LocalPhpService
{
    private string $servicePath;

    public function __construct(
        private string $applicationPath,
        private $packageDirectories = []
    ) {
        $this->servicePath = $this->resolveLightningServicesPath();
    }

    private function resolveLightningServicesPath(): string
    {
        $path = sprintf('%s/Contents/Resource/extraResources/lightning-services', $this->applicationPath);

        return is_dir($path)
            ? $path
            : throw new \InvalidArgumentException(
                // todo: make Exception subclass
                "Could not find the lightning-services directory in: \"{$this->applicationPath}\"."
            );
    }

//    private function collection(): array
//    {
//        foreach ((new Finder())->in($this->servicePath)->directories()->getIterator() as $dir) {}
//    }
}
