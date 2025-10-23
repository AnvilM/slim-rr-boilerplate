<?php

declare(strict_types=1);

namespace Application\Commands\MakeMigrationCommand;

use Application\Config\ApplicationConfig\ApplicationConfig;
use InvalidArgumentException;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;


#[AsCommand(name: 'migration:generate', description: 'Generate migration command')]
final class MigrationGenerateCommand extends Command
{

    protected function configure(): void
    {
        $this->addArgument('name', InputArgument::REQUIRED, 'Migration name e.g. create_users_table');
    }

    public function __invoke(InputInterface $input, OutputInterface $output): int
    {
        $name = $input->getArgument('name');

        if (!is_string($name)) {
            throw new InvalidArgumentException('Argument "name" must be a string.');
        }

        copy(__DIR__ . '/Assets/ExampleMigration', ApplicationConfig::baseDir() . '/database/migrations/' . date('Ymd.His_') . $name . '.php');

        return Command::SUCCESS;
    }
}