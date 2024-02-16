<?php

declare(strict_types=1);

use App\Console\FixtureLoadCommand;
use App\Entity\Payment\Payment;
use App\Entity\User\User;
use App\Repository\PaymentRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\ORMSetup;
use League\Container\Container;
use Psr\Log\LoggerInterface;
use Qiwi\Api\BillPayments;
use Symfony\Component\Cache\Adapter\ArrayAdapter;
use Symfony\Component\Cache\Adapter\FilesystemAdapter;
use Doctrine\Common\EventManager;
use Doctrine\DBAL\Connection;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Mapping\UnderscoreNamingStrategy;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;
use Monolog\Processor\ProcessorInterface;
use Doctrine\Migrations;
use Doctrine\Migrations\DependencyFactory;
use Doctrine\Migrations\Tools\Console\Command;

return function (Container $container) {
    $container->addShared('config', require __DIR__ . '/config.php');

    $container->addShared(LoggerInterface::class)->setConcrete(function () use ($container) {
        $config = $container->get('config')['logger'];

        $level = $config['debug'] ? Logger::DEBUG : Logger::INFO;

        $log = new Logger('API');

        if ($config['stderr']) {
            $log->pushHandler(new StreamHandler('php://stderr', $level));
        }

        if (!empty($config['file'])) {
            $log->pushHandler(new StreamHandler($config['file'], $level));
        }

        if (!empty($config['processors'])) {
            foreach ($config['processors'] as $class) {
                /** @var ProcessorInterface $processor */
                $processor = $container->get($class);
                $log->pushProcessor($processor);
            }
        }

        return $log;
    });

    $container->addShared(EntityManagerInterface::class)->setConcrete(function () use ($container) {
        $settings = $container->get('config')['doctrine'];

        $config = ORMSetup::createAttributeMetadataConfiguration(
            $settings['metadata_dirs'],
            $settings['dev_mode'],
            $settings['proxy_dir'],
            $settings['cache_dir'] ? new FilesystemAdapter('', 0, $settings['cache_dir']) : new ArrayAdapter()
        );

        $config->setAutoGenerateProxyClasses(true);

        $config->setNamingStrategy(new UnderscoreNamingStrategy());

        $eventManager = new EventManager();

        return EntityManager::create($settings['connection'], $config, $eventManager);
    });

    $container->addShared(Connection::class)->setConcrete(function () use ($container) {
        $entityManager = $container->get(EntityManagerInterface::class);
        return $entityManager->getConnection();
    });

    $container->addShared(DependencyFactory::class)->setConcrete(function () use ($container) {
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

    $container->addShared(Command\ExecuteCommand::class)->setConcrete(function () use ($container) {
        $factory = $container->get(DependencyFactory::class);
        return new Command\ExecuteCommand($factory);
    });

    $container->addShared(Command\MigrateCommand::class)->setConcrete(function () use ($container) {
        $factory = $container->get(DependencyFactory::class);
        return new Command\MigrateCommand($factory);
    });

    $container->addShared(Command\LatestCommand::class)->setConcrete(function () use ($container) {
        $factory = $container->get(DependencyFactory::class);
        return new Command\LatestCommand($factory);
    });

    $container->addShared(Command\ListCommand::class)->setConcrete(function () use ($container) {
        $factory = $container->get(DependencyFactory::class);
        return new Command\ListCommand($factory);
    });

    $container->addShared(Command\StatusCommand::class)->setConcrete(function () use ($container) {
        $factory = $container->get(DependencyFactory::class);
        return new Command\StatusCommand($factory);
    });

    $container->addShared(Command\UpToDateCommand::class)->setConcrete(function () use ($container) {
        $factory = $container->get(DependencyFactory::class);
        return new Command\UpToDateCommand($factory);
    });

    $container->addShared(Command\DiffCommand::class)->setConcrete(function () use ($container) {
        $factory = $container->get(DependencyFactory::class);
        return new Command\DiffCommand($factory);
    });

    $container->addShared(Command\GenerateCommand::class)->setConcrete(function () use ($container) {
        $factory = $container->get(DependencyFactory::class);
        return new Command\GenerateCommand($factory);
    });

    $container->addShared(FixtureLoadCommand::class)->setConcrete(function () use ($container) {
        return new FixtureLoadCommand(
            $container->get(EntityManagerInterface::class),
            $container->get('config')['console']['fixture_paths'],
        );
    });

    $container->addShared(BillPayments::class)->setConcrete(function () use ($container){
        return new BillPayments($container->get('config')['qiwi_token']);
    });

    $container->addShared(UserRepository::class)->setConcrete(function () use ($container) {
        $entityManager = $container->get(EntityManagerInterface::class);
        return new UserRepository($entityManager, $entityManager->getRepository(User::class));
    });

    $container->addShared(PaymentRepository::class)->setConcrete(function () use ($container) {
        $entityManager = $container->get(EntityManagerInterface::class);
        return new PaymentRepository($entityManager, $entityManager->getRepository(Payment::class));
    });
};