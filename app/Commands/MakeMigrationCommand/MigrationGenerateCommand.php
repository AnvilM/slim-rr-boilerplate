<?php

declare(strict_types=1);

namespace Application\Commands\MakeMigrationCommand;

use Application\Config\ApplicationConfig\ApplicationConfig;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;


#[AsCommand(name: 'migration:generate', description: 'Generate migration command')]
final class MakeMigrationCommand extends Command
{

    protected function configure(): void
    {
        $this->addArgument('name', InputArgument::REQUIRED, 'Migration name e.g. create_users_table');
    }

    public function __invoke(InputInterface $input, OutputInterface $output): int
    {
        copy(__DIR__ . '/Assets/ExampleMigration', ApplicationConfig::baseDir() . '/database/migrations/' . date('Ymd.His_') . $input->getArgument('name') . '.php');

        return Command::SUCCESS;
    }
}