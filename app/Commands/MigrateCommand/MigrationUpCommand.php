<?php

declare(strict_types=1);

namespace Application\Commands\MigrateCommand;

use Application\Config\MigrationConfig\MigrationConfig;
use Cycle\Database\DatabaseManager;
use Cycle\Migrations\Config\MigrationConfig as CycleMigrationConfig;
use Cycle\Migrations\FileRepository;
use Cycle\Migrations\Migrator;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;


#[AsCommand(name: 'migration:up', description: 'Migrate command')]
final class MigrationUpCommand extends Command
{
    public function __construct(private readonly DatabaseManager $dbal, ?string $name = null)
    {
        parent::__construct($name);
    }

    public function __invoke(InputInterface $input, OutputInterface $output): int
    {
        $config = new CycleMigrationConfig(
            MigrationConfig::config()
        );

        $migrator = new Migrator(
            $config,
            $this->dbal,
            new FileRepository($config)
        );

        $migrator->configure();

        $migrator->run();

        return Command::SUCCESS;
    }
}