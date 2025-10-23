<?php

declare(strict_types=1);

namespace Application\Bootstrappers;

use Application\Commands\MakeMigrationCommand\MigrationGenerateCommand;
use Application\Commands\MigrateCommand\MigrationUpCommand;
use DI\Container;
use Psr\Container\ContainerInterface;
use Symfony\Component\Console\Application as CliApp;


final class CommandsBootstrapper
{
    private static array $commands = [
        MigrationGenerateCommand::class,
        MigrationUpCommand::class,
    ];


    public static function registerCommands(CliApp $app, ContainerInterface $container): void
    {
        /** @var Container $container */

        foreach (self::$commands as $command) {
            $app->add($container->make($command));
        }
    }
}