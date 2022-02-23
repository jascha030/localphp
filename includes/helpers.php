<?php

declare(strict_types=1);

namespace Jascha030\Localphp;

use DI\ContainerBuilder;
use Exception;
use Generator;
use Jascha030\Localphp\Console\Application\Application;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface;
use Psr\Container\NotFoundExceptionInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Output\OutputInterface;

function definitions(): Generator
{
    $dir   = __DIR__ . '/definitions';
    $files = array_diff(scandir($dir), ['..', '.']);

    foreach ($files as $file) {
        yield str_replace('.php', '', $file) => "{$dir}/{$file}";
    }
}

/**
 * @throws Exception
 */
function container(): ContainerInterface
{
    static $container;

    if (isset($container)) {
        return $container;
    }

    $builder = (new ContainerBuilder())
        ->useAnnotations(false)
        ->useAutowiring(false);

    foreach (definitions() as $definition) {
        $builder->addDefinitions($definition);
    }

    /** @noinspection PhpUnnecessaryLocalVariableInspection */
    $container = $builder->build();

    return $container;
}

/**
 * @throws ContainerExceptionInterface
 * @throws NotFoundExceptionInterface
 * @throws Exception
 */
function app(): Application
{
    return container()->get('app');
}

/**
 * @throws ContainerExceptionInterface
 * @throws NotFoundExceptionInterface
 * @throws Exception
 */
function output(): OutputInterface
{
    return container()->get(OutputInterface::class);
}

/**
 * Write error to output.
 *
 * @throws ContainerExceptionInterface
 * @throws NotFoundExceptionInterface
 * @throws Exception
 */
function error(string $message): void
{
    output()->writeln(sprintf('<error>%s</error>', $message));
}

/**
 * Error handler function, writes the message to Console output and returns exit code.
 *
 * Can be used to return or use inside exit(fail($msg)).
 *
 * @throws ContainerExceptionInterface
 * @throws NotFoundExceptionInterface
 */
function fail(string $message, int $code = Command::FAILURE): int
{
    error($message);

    return $code;
}

function user(): string
{
    return $_SERVER['SUDO_USER'] ?? $_SERVER['USER'];
}
