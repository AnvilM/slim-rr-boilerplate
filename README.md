<div align="center">
    <h1>Slim · RoadRunner Boilerplate</h1>
    <p>PHP Boilerplate Powered by <b>Slim Framework</b> and <b>RoadRunner</b></p>

[![php-version-shield]][php-version-link]
[![phpstan-shield]][phpstan-link]
[![][github-release-shield]][github-release-link]

[![status-shield]][status-link]
[![last-commit-shield]][last-commit-link]
[![][github-release-date-shield]][github-release-date-link]
[![github-license-shield]][github-license-link]

</div>

## Project Overview

This template is designed for the development of web applications utilizing
the [Slim Framework](https://www.slimframework.com/) and the high-performance [RoadRunner](https://roadrunner.dev/)
server. The project incorporates tools for building scalable PHP applications, including a dependency injection
container, logging system, Object-Relational Mapping (ORM), database migrations, and a Command Line Interface (CLI).

## Core Components

- **Container**: [PHP-DI](https://php-di.org/)
- **HTTP**: [nyholm/psr7](https://github.com/Nyholm/psr7)
- **Logger**: [Monolog](https://seldaek.github.io/monolog/)
- **ORM**: [CycleORM](https://cycle-orm.dev/)
- **Migrations**: [Cycle Migrations](https://cycle-orm.dev/docs/database-migrations/)
- **CLI**: [Symfony Console](https://symfony.com/doc/current/components/console.html)
- **Testing**: [Pest](https://pestphp.com/)
- **Static Analysis**: [PHPStan](https://phpstan.org/)

## Getting Started

### Installation

#### Via Composer

Create a new project using Composer:

```bash
composer create-project anvilm/slim-rr-boilerplate my-project
```

#### Via GitHub

Clone the repository and install dependencies:

```bash
git clone https://github.com/anvilm/slim-rr-boilerplate.git my-project
cd my-project
composer install
```

### Directory Structure

```
.
├── app/                          # Core application logic and infrastructure
│   ├── Bootloaders/              # Classes for initializing application components
│   │   ├── Application/          # Web application (Slim) bootloaders
│   │   ├── Console/               # Console application (Symfony Console) bootloaders
│   │   ├── Commands/              # CLI commands registration
│   │   ├── Endpoints/             # HTTP endpoints registration
│   │   ├── Environment/           # .env resolution and loading
│   │   ├── Infrastructure/        # DI container and providers
│   │   ├── BootloaderInterface.php
│   │   └── Context.php
│   ├── Commands/                 # Custom CLI commands
│   ├── Config/                   # Configuration files
│   ├── Endpoints/                # Definitions of HTTP endpoints
│   ├── Providers/                # Dependency injection container providers
│   │   ├── ApplicationProviders/ # Providers for application functionality
│   │   ├── Providers/            # Custom providers
│   │   └── Registry.php          # List of providers to load
│   └── Kernel.php                # Application entry point and bootstrap management
├── bin/                          # CLI entry point
│   └── console.php               # Entry point for Symfony Console
├── database/                     # Migrations and SQLite database files
├── logs/                         # Application logs
├── src/                          # Source code for custom logic
├── tests/                        # Pest tests
└── index.php                     # Entry point for RoadRunner
```

### Directory Organization

The boilerplate is structured to separate infrastructural logic from user-defined code:

- **Directory `app/`**: Contains the core infrastructure of the application,
  including [configurations](#configuration), [providers](#providers), [endpoints](#endpoints),
  and [bootloaders](#bootloaders). This directory is intended for foundational application setup and operation.
- **Directory `src/`**: Designated for user-defined source code, where developers can implement the primary business
  logic of the application.

### Configuration

Application configurations are organized in the `app/Config/` directory and provide type-safe access to settings via
classes with static methods. For further details, refer to the [Configuration](#configuration-1) section.

#### Environment Variables

Environment variables are loaded from a `.env` file during bootstrapping, inside the `EnvironmentBootloader`
(`app/Bootloaders/Environment/EnvironmentBootloader.php`), and are then read via the
[oscarotero/env](https://github.com/oscarotero/env) `env()` helper inside configuration classes.

**Which file gets loaded.** The current environment name is resolved from the real `APP_ENV` process/OS variable
(whatever was already set before PHP started — by the shell, Docker, systemd, the CI runner, etc.), defaulting to
`local` when it isn't set. That name is then used to pick an environment-specific file first, falling back to the
generic one:

1. `.env.{APP_ENV}` (e.g. `.env.production`, `.env.testing`, `.env.local`) — tried first.
2. `.env` — used if the environment-specific file doesn't exist.

Only the first file that is found is loaded; `safeLoad()` is used, so if neither file exists nothing is overwritten
and no error is thrown — the application simply keeps reading whatever real environment variables are already
available (e.g. injected by Docker/RoadRunner without a `.env` file at all).

**Reading the values back.** `oscarotero/env`'s `env()` helper reads from `getenv()` by default. Under most
PHP-FPM/RoadRunner setups `putenv()` is disabled (`disable_functions`) to stop one worker's environment leaking into
the next request — `phpdotenv`'s `PutenvAdapter` then silently no-ops, so values it loads land in `$_ENV`/`$_SERVER`
but never reach `getenv()`. To avoid depending on `putenv()` being available, `EnvironmentBootloader` configures
`Env\Env::$options` (right after loading the `.env` file) to include the `USE_ENV_ARRAY` flag, so `env()` reads from
`$_ENV` instead.

Predefined environment variables:

- **APP_ENV**: Defines the application environment (e.g., `production`, `development`, `testing`), affecting logging
  levels and which `.env.{APP_ENV}` file is loaded.
- **APP_DEBUG**: Enables or disables debug mode, influencing the display of detailed error information in Slim.

#### Application Configuration

The `ApplicationConfig` class provides the following parameters:

- **baseDir**: The root directory of the project.
- **appEnv**: The application environment, determined by the `APP_ENV` variable. Available environments are listed in
  the `ApplicationEnvironmentEnum` enumeration. To add a new environment, update this enumeration and the
  `ApplicationConfig` class.
- **appDebug**: Debug mode, determined by the `APP_DEBUG` variable. Enables detailed error messages.

#### Database Configuration

The `DatabaseConfig` class defines settings for CycleORM. By default, it is configured for SQLite, but other database
management systems (e.g., MySQL, PostgreSQL) are supported with appropriate configuration.

#### Logging Configuration

The `LoggerConfig` class configures logging parameters using Monolog. It includes the path to log files (in the `logs/`
directory) and the logging level, which depends on the `appEnv` value.

#### Migration Configuration

Database migration settings are defined for Cycle Migrations. Migrations are stored in the `database/migrations/`
directory and managed via [CLI commands](#commands).

## Architectural Concepts

### Configuration

Configurations are stored in the `app/Config/` directory. Each configuration is implemented as a class with static
methods, ensuring type-safe access to settings.

Example:

```php
namespace Application\Config\ApplicationConfig;

use function Env\env;

final readonly class ApplicationConfig
{
    public static function baseDir(): string
    {
        return dirname(__DIR__, 3);
    }
}
```

### Endpoints

HTTP endpoints are defined in the `app/Endpoints/` directory. Routing is handled using the standard Slim Framework
mechanism. Each endpoint class must implement the `EndpointInterface` and be registered in
the [EndpointsBootloader](#bootloaders).

Example of creating an endpoint:

```php
namespace App\Endpoints;

final readonly class ApiEndpoints implements EndpointInterface
{
    public static function register(App $app): void
    {
        $app->get('/', function (RequestInterface $request, ResponseInterface $response) {
            $response->getBody()->write('Example response');
            return $response;
        });
    }
}
```

Example of registration in `app/Bootloaders/Endpoints/EndpointsBootloader.php` ([EndpointsBootloader](#bootloaders)):

```php
private static array $endpoints = [
    \App\Endpoints\ApiEndpoints::class,
];
```

### Providers

Service providers, located in the `app/Providers/` directory, are responsible for registering dependencies in the PHP-DI
container, making them accessible to the application. Providers are categorized as follows:

- `ApplicationProviders/`: Providers essential for application functionality.
- `Providers/`: Custom providers for specific logic.

Each provider must implement the `ProviderInterface` and be registered in the `app/Providers/Registry.php` file.

`ApplicationProviders` must be registered in the `$appProviders` array, other providers in the `$providers` array.

Providers should return **lazy PHP-DI definitions** (`DI\factory()`, `DI\autowire()`, `DI\create()`, ...) rather than
plain, eagerly-built instances. A lazy definition means the service is only actually constructed the first time
something asks the container for it — instead of being built on every request whether it's used or not — and a
factory closure may type-hint the container itself (or any other registered service) to depend on whatever other
providers have registered, letting PHP-DI resolve the order instead of the provider having to know it up front.

Example of creating a provider:

```php
namespace App\Providers;

use function DI\factory;

final readonly class DBALProvider implements ProviderInterface
{
    public static function register(): array
    {
        return [
            DatabaseManager::class => factory(static function (): DatabaseManager {
                return new DatabaseManager(
                    new CycleDatabaseConfig(
                        DatabaseConfig::config()
                    )
                );
            }),
        ];
    }
}
```

Example of registration in `app/Providers/Registry.php`:

```php
public static array $appProviders = [
    \App\Providers\ApplicationProviders\LoggerProvider::class,
    \App\Providers\ApplicationProviders\DBALProvider::class,
];

public static array $providers = [
    // Custom providers
];
```

### Bootloaders

The application bootstrapping process is divided into distinct stages to ensure a scalable, ordered, and predictable
initialization of components. Each stage is managed by a dedicated bootloader, allowing components to be loaded
sequentially rather than in a single monolithic location.

All bootloaders are located in the `app/Bootloaders/` directory and must implement the `BootloaderInterface`. The
`Kernel` class orchestrates the entire bootstrapping process by executing bootloaders in a strictly defined order and
threading a single, accumulating `Context` object through the chain.

#### Directory Structure

```
app/Bootloaders/
├── Application/
│   ├── ApplicationBootloader.php
│   └── WebApplication.php
├── Console/
│   ├── ConsoleBootloader.php
│   └── ConsoleApplication.php
├── Commands/
│   └── CommandsBootloader.php
├── Endpoints/
│   └── EndpointsBootloader.php
├── Infrastructure/
│   ├── Container.php
│   ├── Providers.php
│   └── InfrastructureBootloader.php
├── Environment/
│   └── EnvironmentBootloader.php
├── BootloaderInterface.php
└── Context.php
```

#### The Context object

Each bootloader receives a `Context`, may read values a previous bootloader put into it, and returns a
(possibly enriched) `Context` for the next one. `Context` is immutable: `$context->get('key')` reads a value (and
throws if it's missing, which PHPStan can catch via the `Context<TShape>` generic), while `$context->with([...])`
returns a **new** `Context` containing everything the current one already had, plus whatever is passed in.

Bootloaders should always call `$context->with([...])` rather than constructing a brand-new `Context` from scratch —
that way a bootloader further down the chain that only needs, say, the container from two steps back still gets it,
without every intermediate bootloader having to know, by convention, which keys to manually carry forward.

#### Boot Sequence

Two independent chains exist — the Kernel exposes one entry point per chain:

**`Kernel::createApp()`** (the Slim/HTTP application):

1. **EnvironmentBootloader** — loads the appropriate `.env` file (see [Environment Variables](#environment-variables)).
2. **InfrastructureBootloader** — configures the PHP-DI container and [providers](#providers).
3. **ApplicationBootloader** — builds the Slim application from the container.
4. **EndpointsBootloader** — registers [endpoints](#endpoints) on the Slim application.

**`Kernel::createCliApp()`** (the Symfony Console application):

1. **EnvironmentBootloader** — same as above.
2. **InfrastructureBootloader** — same as above.
3. **ConsoleBootloader** — builds the bare Symfony Console application.
4. **CommandsBootloader** — resolves and registers [CLI commands](#commands) from the container.

Additional bootloaders can be inserted at appropriate positions in either sequence as the application evolves.

#### Extending the Boot Process

To add a new bootloader:

1. Create a new directory and class under `app/Bootloaders/`, for example:
   `app/Bootloaders/OneMore/OneMoreBootloader.php`

2. Implement the `BootloaderInterface` in the new class. Bootloaders are expected to be static, receive a `Context`
   object, perform configuration, and return a (possibly enriched) `Context` via `$context->with([...])`:

```php
<?php

namespace App\Bootloaders\OneMore;

use App\Bootloaders\BootloaderInterface;
use App\Bootloaders\Context;
use DI\Container as DIContainer;

/**
 * @implements BootloaderInterface<array{container: DIContainer}, array{container: DIContainer}>
 */
final readonly class OneMoreBootloader implements BootloaderInterface
{
    /**
     * @param Context<array{container: DIContainer}> $context
     * @return Context<array{container: DIContainer}>
     */
    public static function boot(Context $context): Context
    {
        $context->get('container')->get(LoggerInterface::class)->debug("OneMoreBootloader: booted");

        return $context->with([]);
    }
}
```

3. Register the new bootloader in `Kernel::createApp()` (or `createCliApp()`) by chaining it in the correct position:

```php
namespace App;

use App\Bootloaders\Context;

final readonly class Kernel
{
    public static function createApp(): App
    {
        /** @var Context<array{}> $environmentContext */
        $environmentContext = EnvironmentBootloader::boot(new Context());

        /** @var Context<array{container: DIContainer}> $infrastructureContext */
        $infrastructureContext = InfrastructureBootloader::boot($environmentContext);

        /** @var Context<array{container: DIContainer}> $oneMoreContext */
        $oneMoreContext = OneMoreBootloader::boot($infrastructureContext);

        /** @var Context<array{container: DIContainer, app: App}> $applicationContext */
        $applicationContext = ApplicationBootloader::boot($oneMoreContext);

        /** @var Context<array{container: DIContainer, app: App}> $endpointsContext */
        $endpointsContext = EndpointsBootloader::boot($applicationContext);

        return $endpointsContext->get('app');
    }
}
```

This chained, explicit approach ensures full control over the initialization order and makes dependencies between
stages transparent.

### Commands

CLI commands are defined in the `app/Commands/` directory. Each command must be registered in
the [CommandsBootloader](#bootloaders).

The Symfony Console library is used for CLI commands.

Example of registration in `app/Bootloaders/Commands/CommandsBootloader.php` ([CommandsBootloader](#bootloaders)):

```php
private static array $commands = [
    \App\Commands\MigrationGenerateCommand::class,
    \App\Commands\MigrationUpCommand::class,
];
```

Available commands:

- `migration:generate`: Generates a migration template in the `database/migrations/` directory.
- `migration:up`: Executes pending migrations.

Running commands:

```bash
php bin/console {commandName}
```

## License

The project is distributed under the MIT License. For details, refer to the [LICENSE](LICENSE) file.

<!-- LINKS -->

[github-release-link]: https://github.com/anvilm/slim-rr-boilerplate/releases

[github-release-shield]: https://img.shields.io/github/v/release/anvilm/slim-rr-boilerplate?style=flat-square&sort=semver&logo=github&labelColor=black

[github-release-date-link]: https://github.com/anvilm/slim-rr-boilerplate/releases

[github-release-date-shield]: https://img.shields.io/github/release-date/anvilm/slim-rr-boilerplate?labelColor=black&style=flat-square

[github-license-link]: https://github.com/anvilm/slim-rr-boilerplate/blob/master/LICENSE

[github-license-shield]: https://img.shields.io/github/license/anvilm/slim-rr-boilerplate?color=white&labelColor=black&style=flat-square

[status-link]: https://github.com/AnvilM/slim-rr-boilerplate/

[status-shield]: https://img.shields.io/badge/status-active-brightgreen?labelColor=black&style=flat-square

[last-commit-link]: https://github.com/AnvilM/slim-rr-boilerplate/commits

[last-commit-shield]: https://img.shields.io/github/last-commit/anvilm/slim-rr-boilerplate?labelColor=black&style=flat-square

[phpstan-link]: https://github.com/AnvilM/slim-rr-boilerplate/

[phpstan-shield]: https://img.shields.io/badge/PHPStan-Level%20max-blue?logo=php&labelColor=black&style=flat-square

[php-version-link]: https://github.com/AnvilM/slim-rr-boilerplate/

[php-version-shield]: https://img.shields.io/badge/PHP-8.4-blue?logo=php&labelColor=black&style=flat-square