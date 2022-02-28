<?php

declare(strict_types=1);

namespace Jascha030\Localphp;

use Exception;
use InvalidArgumentException;
use Jascha030\Localphp\Process\Binary\PhpBinary;
use RuntimeException;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Finder\Finder;
use Symfony\Component\Finder\SplFileInfo;

final class LocalWPService
{
    private string $servicePath;

    private string $composerPath;

    private ?array $packageDirectories;

    private ?array $phpVersions;

    public function __construct(private string $applicationPath)
    {
        $this->resolveLightningServicesPath();
        $this->resolveComposerPath();

        $this->phpVersions = [];
    }

    /**
     * @param bool $scan enable to re-scan package dir for changes between calls
     *
     * @throws RuntimeException
     * @throws Exception
     */
    public function getAvailablePhpVersions(bool $scan = false): array
    {
        foreach ($this->getPackages($scan) as $name => $packagePath) {
            if (str_starts_with($name, 'php')) {
                $path = $this->findBinary($packagePath);

                if (! $path) {
                    continue;
                }

                $binary = new PhpBinary($path, $this->composerPath);

                if (! isset($this->phpVersions[$binary->getVersion()])) {
                    $this->phpVersions[$binary->getVersion()] = $binary;
                }
            }
        }

        return $this->phpVersions;
    }

    /**
     * @param bool $scan enable to re-scan package dir for changes between calls
     *
     * @throws Exception
     */
    public function getPackages(bool $scan = false): array
    {
        return $scan
            ? $this->fetchPackages()->packageDirectories
            : $this->packageDirectories ?? $this->fetchPackages()->packageDirectories;
    }

    /**
     * @throws InvalidArgumentException
     * @todo: make Exception subclass
     */
    private function resolveLightningServicesPath(): void
    {
        $path = sprintf('%s/Contents/Resources/extraResources/lightning-services', $this->applicationPath);

        if (! is_dir($path)) {
            throw new InvalidArgumentException("Could not find the lightning-services directory in: \"{$this->applicationPath}\".");
        }

        $this->servicePath = $path;
    }

    private function resolveComposerPath(): void
    {
        $path = sprintf('%s/Contents/Resources/extraResources/bin/composer/composer.phar', $this->applicationPath);

        if (! file_exists($path)) {
            throw new InvalidArgumentException("Could not find the composer.phar at: \"{$this->applicationPath}\".");
        }

        $this->composerPath = $path;
    }

    /**
     * @throws Exception
     */
    private function fetchPackages(): self
    {
        if (! isset($this->packageDirectories)) {
            $this->packageDirectories = [];
        }

        $dirs = (new Finder())
            ->in($this->servicePath)
            ->directories()
            ->depth(0)
            ->getIterator();

        /** @var SplFileInfo $dir */
        foreach ($dirs as $dir) {
            if (! isset($this->packageDirectories[$dir->getFilename()])) {
                $this->packageDirectories[$dir->getFilename()] = $dir->getRealPath();
            }
        }

        return $this;
    }

    /**
     * @throws RuntimeException
     * @throws Exception
     */
    private function findBinary(string $path): ?string
    {
        $finder = (new Finder())
            ->in("{$path}/bin")
            ->files()
            ->name('php');

        if ($finder->count() > 1) {
            throw new RuntimeException('Multiple \'php\' binaries found');
        }

        $array    = iterator_to_array($finder->getIterator());
        $fileInfo = reset($array);

        return $fileInfo instanceof SplFileInfo
            ? $fileInfo->getRealPath()
            : null;
    }
}
