<?php

declare(strict_types=1);

namespace Application\Bootloaders\Commands;

use Application\Bootloaders\BootloaderInterface;
use Application\Bootloaders\Context;
use Application\Commands\MakeMigrationCommand\MigrationGenerateCommand;
use Application\Commands\MigrateCommand\MigrationUpCommand;
use DI\Container as DIContainer;
use Psr\Container\ContainerInterface;
use Symfony\Component\Console\Application as CliApp;
use Symfony\Component\Console\Command\Command;

/**
 * Bootloader that registers all console commands on the CLI app.
 * Requires the container and the CLI app instance in the input context, and
 * does not add anything new to it — it only registers commands on the
 * existing CLI app as a side effect.
 *
 * @implements BootloaderInterface<array{container: DIContainer, cliApp: CliApp}, array{container: DIContainer, cliApp: CliApp}>
 */
final class CommandsBootloader implements BootloaderInterface
{
    /**
     * List of command classes to register.
     *
     * @var array<class-string<Command>>
     */
    private static array $commands = [
        MigrationGenerateCommand::class,
        MigrationUpCommand::class,
    ];

    /**
     * Resolves every command from the container and registers it on the CLI app.
     *
     * @param Context<array{container: DIContainer, cliApp: CliApp}> $context Context containing the container and CLI app
     *
     * @return Context<array{container: DIContainer, cliApp: CliApp}> Unchanged context
     */
    public static function boot(Context $context): Context
    {
        /** @var ContainerInterface $container */
        $container = $context->get('container');

        /** @var CliApp $cliApp */
        $cliApp = $context->get('cliApp');

        foreach (self::$commands as $commandClass) {
            /** @var Command $command */
            $command = $container->get($commandClass);

            $cliApp->addCommand($command);
        }

        return $context;
    }
}
