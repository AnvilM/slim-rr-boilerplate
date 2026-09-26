<?php

declare(strict_types=1);

namespace Application\Bootloaders\Console;

use Application\Bootloaders\BootloaderInterface;
use Application\Bootloaders\Context;
use DI\Container as DIContainer;
use Symfony\Component\Console\Application as CliApp;

/**
 * Bootloader that sets up the console (CLI) application.
 * Requires a DI container in the input context and produces a CLI app instance.
 *
 * @implements BootloaderInterface<array{container: DIContainer}, array{container: DIContainer, cliApp: CliApp}>
 */
final readonly class ConsoleBootloader implements BootloaderInterface
{
    /**
     * Creates the CLI app instance.
     *
     * @param Context<array{container: DIContainer}> $context Context containing the DI container
     *
     * @return Context<array{container: DIContainer, cliApp: CliApp}> Context enriched with the CLI app instance
     */
    public static function boot(Context $context): Context
    {
        $cliApp = new CliApp();

        /** @var Context<array{container: DIContainer, cliApp: CliApp}> $result */
        $result = $context->with([
            'cliApp' => $cliApp,
        ]);

        return $result;
    }
}