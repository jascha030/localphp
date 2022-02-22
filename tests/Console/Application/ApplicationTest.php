<?php

declare(strict_types=1);

namespace Jascha030\Localphp\Tests\Console\Application;

use Jascha030\Localphp\Console\Application\Application;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Tester\ApplicationTester;

/**
 * @covers \Jascha030\Localphp\Console\Application\Application
 *
 * @internal
 */
final class ApplicationTest extends TestCase
{
    public function testConstruct(): Application
    {
        $app = new Application();

        $this->assertInstanceOf(\Symfony\Component\Console\Application::class, $app);
        $app->setAutoExit(false);

        return $app;
    }

    /**
     * @depends testConstruct
     */
    public function testVersion(Application $application): void
    {
        $this->assertEquals(Application::APP_VERSION, $application->getVersion());
    }

    /**
     * @depends testConstruct
     */
    public function testRun(Application $application): void
    {
        $this->assertEquals(Command::SUCCESS, (new ApplicationTester($application))->run([]));
    }
}
