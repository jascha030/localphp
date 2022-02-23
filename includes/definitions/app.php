<?php

declare(strict_types=1);

use Jascha030\Localphp\Console\Application\Application;
use Jascha030\Localphp\LocalWPService;
use Symfony\Component\Console\Output\ConsoleOutput;
use Symfony\Component\Console\Output\OutputInterface;
use function DI\create;
use function DI\get;

/**
 * Application definitions.
 *
 * @return array
 *
 * @see \DI\Container
 * @see \Di\ContainerBuilder
 * @see https://php-di.org/doc/php-definitions.html
 */
return [
    OutputInterface::class => create(ConsoleOutput::class),
    'local_default_path'   => '/Applications/Local.app',
    'app'                  => create(Application::class)->method('addCommands', get('commands')),
    'local'                => create(LocalWPService::class)->constructor(get('local_default_path'), get(OutputInterface::class)),
];
