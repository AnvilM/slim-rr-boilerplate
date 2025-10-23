<?php

declare(strict_types=1);

namespace Application\Bootstrappers;

use Application\Commands\MakeMigrationCommand\MigrationGenerateCommand;
use Application\Commands\MigrateCommand\MigrationUpCommand;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface;
use Psr\Container\NotFoundExceptionInterface;
use Symfony\Component\Console\Application as CliApp;
use Symfony\Component\Console\Command\Command;


final class CommandsBootstrapper
{
    /** @var array<class-string<Command>> */
    private static array $commands = [
        MigrationGenerateCommand::class,
        MigrationUpCommand::class,
    ];


    /**
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public static function registerCommands(CliApp $app, ContainerInterface $container): void
    {

        foreach (self::$commands as $commandClass) {
            /** @var Command $command */
            $command = $container->get($commandClass);
            
            $app->add($command);
        }
    }
}