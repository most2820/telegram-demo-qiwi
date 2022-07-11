<?php

declare(strict_types=1);

use League\Container\Container;

use Doctrine\Migrations;
use Doctrine\Migrations\DependencyFactory;
use Doctrine\Migrations\Tools\Console\Command;
use Doctrine\ORM\EntityManagerInterface;

return function (Container $container) {
    $container->addShared(DependencyFactory::class)
        ->setConcrete(function () use ($container) {
            $entityManager = $container->get(EntityManagerInterface::class);

            $configuration = new Doctrine\Migrations\Configuration\Configuration();
            $configuration->addMigrationsDirectory('App\Data\Migration', __DIR__ . '/../src/Data/Migration');
            $configuration->setAllOrNothing(true);
            $configuration->setCheckDatabasePlatform(false);

            $storageConfiguration = new Migrations\Metadata\Storage\TableMetadataStorageConfiguration();
            $storageConfiguration->setTableName('migrations');

            $configuration->setMetadataStorageConfiguration($storageConfiguration);

            return DependencyFactory::fromEntityManager(
                new Migrations\Configuration\Migration\ExistingConfiguration($configuration),
                new Migrations\Configuration\EntityManager\ExistingEntityManager($entityManager)
            );
        });

    $container->addShared(Command\ExecuteCommand::class)
        ->setConcrete(function () use ($container) {
            $factory = $container->get(DependencyFactory::class);
            return new Command\ExecuteCommand($factory);
        });

    $container->addShared(Command\MigrateCommand::class)
        ->setConcrete(function () use ($container) {
            $factory = $container->get(DependencyFactory::class);
            return new Command\MigrateCommand($factory);
        });

    $container->addShared(Command\LatestCommand::class)
        ->setConcrete(function () use ($container) {
            $factory = $container->get(DependencyFactory::class);
            return new Command\LatestCommand($factory);
        });

    $container->addShared(Command\ListCommand::class)
        ->setConcrete(function () use ($container) {
            $factory = $container->get(DependencyFactory::class);
            return new Command\ListCommand($factory);
        });

    $container->addShared(Command\StatusCommand::class)
        ->setConcrete(function () use ($container) {
            $factory = $container->get(DependencyFactory::class);
            return new Command\StatusCommand($factory);
        });

    $container->addShared(Command\UpToDateCommand::class)
        ->setConcrete(function () use ($container) {
            $factory = $container->get(DependencyFactory::class);
            return new Command\UpToDateCommand($factory);
        });

    $container->addShared(Command\DiffCommand::class)
        ->setConcrete(function () use ($container) {
            $factory = $container->get(DependencyFactory::class);
            return new Command\DiffCommand($factory);
        });

    $container->addShared(Command\GenerateCommand::class)
        ->setConcrete(function () use ($container) {
            $factory = $container->get(DependencyFactory::class);
            return new Command\GenerateCommand($factory);
        });
};
