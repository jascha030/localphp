<?php

declare(strict_types=1);

namespace Jascha030\Localphp\Console\Application;

use Symfony\Component\Console\Application as ConsoleApplication;

final class Application extends ConsoleApplication
{
    public const APP_NAME = 'localphp';

    public const APP_VERSION = '0.0.1';

    public function __construct()
    {
        parent::__construct(self::APP_NAME, self::APP_VERSION);
    }
}
