<?php

declare(strict_types=1);

namespace Application\Bootloaders\Environment;

use Application\Bootloaders\BootloaderInterface;
use Application\Bootloaders\Context;
use Dotenv\Dotenv;
use Env\Env;

/**
 * Loads environment variables from a .env file.
 *
 * The application environment is resolved from the real APP_ENV process/OS
 * environment variable (i.e. whatever was already set before PHP started —
 * by the shell, Docker, systemd, the CI runner, etc.), defaulting to "local"
 * when it is not set at all.
 *
 * That environment name is then used to pick the .env file to load:
 * ".env.{environment}" is tried first (e.g. ".env.production", ".env.testing"),
 * and ".env" is used as a fallback when the environment-specific file does
 * not exist. Only the first file found is loaded.
 *
 * Uses safeLoad(), so no error is thrown if neither file is present — in that
 * case nothing is overwritten and the application keeps reading configuration
 * from the real environment variables that are already available.
 *
 * @implements BootloaderInterface<array{}, array{}>
 */
final readonly class EnvironmentBootloader implements BootloaderInterface
{
    /**
     * Loads environment variables from the resolved .env file, if any.
     *
     * @param Context<array{}> $context Empty context
     *
     * @return Context<array{}> Empty context
     */
    public static function boot(Context $context): Context
    {
        $environment = self::resolveEnvironment();

        Dotenv::createImmutable(
            dirname(__DIR__, 3),
            file_exists(".env.$environment") ? ".env.$environment" : ".env",
        )->safeLoad();

        self::configureEnvReader();

        return $context;
    }

    /**
     * Makes the `env()` helper (Env\Env) read from $_ENV instead of getenv().
     *
     * phpdotenv writes loaded values to $_ENV, $_SERVER and, when available,
     * to the real process environment via putenv(). Many php-fpm setups
     * disable putenv() (disable_functions) to stop one worker's environment
     * leaking into the next request, so phpdotenv's PutenvAdapter silently
     * no-ops there — the values land in $_ENV but never reach getenv().
     * Reading $_ENV directly avoids depending on putenv() being available.
     */
    private static function configureEnvReader(): void
    {
        Env::$options = Env::CONVERT_BOOL
            | Env::CONVERT_NULL
            | Env::CONVERT_INT
            | Env::STRIP_QUOTES
            | Env::USE_ENV_ARRAY;
    }

    /**
     * Resolves the current environment name from the real process environment,
     * i.e. before any .env file has been loaded.
     */
    private static function resolveEnvironment(): string
    {
        $environment = getenv('APP_ENV');

        return $environment !== false && $environment !== '' ? $environment : 'local';
    }
}