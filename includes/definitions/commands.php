<?php

declare(strict_types=1);

use Jascha030\Localphp\Console\Command\ComposerCommand;
use Jascha030\Localphp\Console\Command\RunCommand;
use Psr\Container\ContainerInterface;
use function DI\create;
use function DI\get;

/**
 * Console command definitions.
 *
 * @return array
 *
 * @see \DI\Container
 * @see \Di\ContainerBuilder
 * @see https://php-di.org/doc/php-definitions.html
 */
return [
    'commands.run'      => create(RunCommand::class)->constructor(get('local')),
    'commands.composer' => create(ComposerCommand::class)->constructor(get('local')),
    'commands'          => static fn (ContainerInterface $container): array          => [
        'run'      => $container->get('commands.run'),
        'composer' => $container->get('commands.run'),
    ],
];
