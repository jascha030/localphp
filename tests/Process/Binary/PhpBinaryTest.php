<?php

declare(strict_types=1);

namespace Jascha030\Localphp\Tests\Process\Binary;

use Jascha030\Localphp\Process\Binary\BinaryInterface;
use Jascha030\Localphp\Process\Binary\PhpBinary;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Output\ConsoleOutput;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Process\Process;

/**
 * @covers \Jascha030\Localphp\Process\Binary\BinaryAbstract
 * @covers \Jascha030\Localphp\Process\Binary\PhpBinary
 *
 * @internal
 */
class PhpBinaryTest extends TestCase
{
    public function testConstruct(): BinaryInterface
    {
        $php = new PhpBinary($this->getPath(), new ConsoleOutput(), dirname(__FILE__, 3) . '/Fixtures/composer/composer.phar');

        $this->assertInstanceOf(BinaryInterface::class, $php);

        return $php;
    }

    /**
     * @depends testConstruct
     */
    public function testCreateProcess(BinaryInterface $binary): void
    {
        $this->assertInstanceOf(Process::class, $binary->createProcess('-v'));
    }

    /**
     * @depends testConstruct
     * @depends testCreateProcess
     */
    public function testInvoke(BinaryInterface $binary): void
    {
        $this->assertEquals(Command::SUCCESS, $binary('-v', silent: true));
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

    /**
     * @depends testConstruct
     */
    public function testGetOutput(BinaryInterface $binary): void
    {
        $this->assertInstanceOf(OutputInterface::class, $binary->getOutput());
    }

    private function getPath(): string
    {
        /**
         * In this case, we use the version that also runs this code,
         * which would normally not be the case.
         */
        $process = Process::fromShellCommandline('echo $(which php)');
        $process->run();

        return str_replace(PHP_EOL, '', $process->getOutput());
    }
}
