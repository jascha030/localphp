<?php

declare(strict_types=1);

namespace Jascha030\Localphp\Tests;

use Exception;
use InvalidArgumentException;
use Jascha030\Localphp\LocalWPService;
use PHPUnit\Framework\TestCase;
use RuntimeException;

/**
 * @covers \Jascha030\Localphp\LocalWPService
 *
 * @internal
 */
final class LocalWPServiceTest extends TestCase
{
    public function testConstruct(): LocalWPService
    {
        $service = new LocalWPService('/Applications/Local.app');

        $this->assertInstanceOf(LocalWPService::class, $service);

        return $service;
    }

    /**
     * @depends testConstruct
     */
    public function testThrowsExceptionOnInvalidPath(): void
    {
        $this->expectException(InvalidArgumentException::class);

        new LocalWPService('/Applications/this/path/is/invalid');
    }

    /**
     * @depends testConstruct
     * @depends testGetPackages
     *
     * @throws Exception
     */
    public function testThrowsExceptionForMissingComposerPhar(): void
    {
        $this->expectException(InvalidArgumentException::class);

        new LocalWPService(__DIR__ . '/Fixtures/no-composer-app/Local.app');
    }

    /**
     * @depends testConstruct
     *
     * @throws Exception
     */
    public function testGetPackages(LocalWPService $service): void
    {
        $this->assertIsArray($service->getPackages());
    }

    /**
     * @depends testConstruct
     * @depends testGetPackages
     *
     * @throws Exception
     */
    public function testScanAndGetPackages(LocalWPService $service): void
    {
        $this->assertEquals($service->getPackages(), $service->getPackages(true));
    }

    /**
     * @depends testConstruct
     * @depends testGetPackages
     *
     * @throws Exception
     */
    public function testGetAvailablePhpVersions(LocalWPService $service): void
    {
        $this->assertIsArray($service->getAvailablePhpVersions());
    }

    /**
     * @depends testConstruct
     * @depends testGetPackages
     *
     * @throws Exception
     */
    public function testGetAvailablePhpVersionsWithoutFoundBinaryPath(): void
    {
        $service = new LocalWPService(__DIR__ . '/Fixtures/no-binary-path-app/Local.app');

        $this->assertEmpty($service->getAvailablePhpVersions());
    }

    /**
     * @depends testConstruct
     * @depends testGetPackages
     *
     * @throws Exception
     */
    public function testThrowsExceptionForMultipleBinaries(): void
    {
        $this->expectException(RuntimeException::class);

        (new LocalWPService(__DIR__ . '/Fixtures/double-binary-app/Local.app'))->getAvailablePhpVersions();
    }
}
