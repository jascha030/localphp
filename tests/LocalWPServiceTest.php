<?php

declare(strict_types=1);

namespace Jascha030\Localphp\Tests;

use Exception;
use InvalidArgumentException;
use Jascha030\Localphp\LocalWPService;
use PHPUnit\Framework\TestCase;
use RuntimeException;
use Symfony\Component\Console\Output\ConsoleOutput;

/**
 * @covers \Jascha030\Localphp\LocalWPService
 *
 * @internal
 */
final class LocalWPServiceTest extends TestCase
{
    public function testConstruct(): LocalWPService
    {
        $service = new LocalWPService('/Applications/Local.app', new ConsoleOutput());

        $this->assertInstanceOf(LocalWPService::class, $service);

        return $service;
    }

    /**
     * @depends testConstruct
     */
    public function testThrowsExceptionOnInvalidPath(): void
    {
        $this->expectException(InvalidArgumentException::class);

        new LocalWPService('/Applications/this/path/is/invalid', new ConsoleOutput());
    }

    /**
     * @depends testConstruct
     */
    public function testGetPackages(LocalWPService $service): void
    {
        $this->assertIsArray($service->getPackages());
    }

    /**
     * @depends testConstruct
     * @depends testGetPackages
     */
    public function testScanAndGetPackages(LocalWPService $service): void
    {
        $this->assertEquals($service->getPackages(), $service->getPackages(true));
    }

    /**
     * @depends testConstruct
     * @depends testGetPackages
     */
    public function testGetAvailablePhpVersions(LocalWPService $service): void
    {
        $this->assertIsArray($service->getAvailablePhpVersions());
    }

    /**
     * @depends testConstruct
     * @depends testGetPackages
     * @throws Exception
     */
    public function testGetAvailablePhpVersionsWithoutFoundBinaryPath(): void
    {
        $service = new LocalWPService(__DIR__ . '/Fixtures/no-binary-path-app/Local.app', new ConsoleOutput());

        $this->assertEmpty($service->getAvailablePhpVersions());
    }

    /**
     * @depends testConstruct
     * @depends testGetPackages
     * @throws Exception
     */
    public function testThrowsExceptionForMultipleBinaries(): void
    {
        $this->expectException(RuntimeException::class);

        (new LocalWPService(__DIR__ . '/Fixtures/double-binary-app/Local.app', new ConsoleOutput()))->getAvailablePhpVersions();
    }
}
