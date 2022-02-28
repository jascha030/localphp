<?php

declare(strict_types=1);

namespace Jascha030\Localphp\Tests\Process\Binary;

use Jascha030\CLI\Shell\Binary\BinaryInterface;
use Jascha030\Localphp\Process\Binary\PhpBinary;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Process\PhpExecutableFinder;
use Symfony\Component\Process\Process;

/**
 * @covers \Jascha030\Localphp\Process\Binary\PhpBinary
 *
 * @internal
 */
class PhpBinaryTest extends TestCase
{
    public function testConstruct(): BinaryInterface
    {
        $php = new PhpBinary($this->getPath(), dirname(__FILE__, 3) . '/Fixtures/composer/composer.phar');

        $this->assertInstanceOf(BinaryInterface::class, $php);

        return $php;
    }

    /**
     * @depends testConstruct
     */
    public function testInvoke(PhpBinary $binary): void
    {
        $phpBinary = (new PhpExecutableFinder())->find();

        $this->assertEquals(
            substr(Process::fromShellCommandline("{$phpBinary} -v")->mustRun()->getOutput(), 0, -1),
            $binary('-v', silent: true)
        );
    }

    /**
     * @depends testConstruct
     */
    public function testGetPath(BinaryInterface $binary): void
    {
        $this->assertEquals($this->getPath(), $binary->getPath());
    }

    /**
     * @depends testConstruct
     */
    public function testGetVersion(BinaryInterface $binary): void
    {
        $this->assertNotEmpty($binary->getVersion());
    }

    private function getPath(): string
    {
        $process = Process::fromShellCommandline('which php')->mustRun();

        return str_replace(PHP_EOL, '', $process->getOutput());
    }
}
