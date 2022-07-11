<?php

declare(strict_types=1);

use App\Entity\Payment\Payment;
use App\Entity\Payment\PaymentRepository;
use App\Entity\User\User;
use App\Entity\User\UserRepository;
use League\Container\Container;
use Qiwi\Api\BillPayments;
use Symfony\Component\Cache\Adapter\ArrayAdapter;
use Symfony\Component\Cache\Adapter\FilesystemAdapter;
use Doctrine\Common\Cache\Psr6\DoctrineProvider;
use Doctrine\Common\EventManager;
use Doctrine\DBAL\Connection;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Mapping\Driver\AttributeDriver;
use Doctrine\ORM\Mapping\UnderscoreNamingStrategy;
use Doctrine\ORM\Tools\Setup;

return function (Container $container) {
    $container->addShared('config', require __DIR__ . '/config.php');

    $container->addShared(EntityManagerInterface::class)
        ->setConcrete(function () use ($container) {
            $settings = $container->get('config')['doctrine'];
            $config = Setup::createConfiguration(
                $settings['dev_mode'],
                $settings['proxy_dir'],
                $settings['cache_dir']
                    ? DoctrineProvider::wrap(new FilesystemAdapter('', 0, $settings['cache_dir']))
                    : DoctrineProvider::wrap(new ArrayAdapter())
            );

            $config->setMetadataDriverImpl(new AttributeDriver($settings['metadata_dirs']));

            $config->setNamingStrategy(new UnderscoreNamingStrategy());

            $eventManager = new EventManager();

            foreach ($settings['subscribers'] as $name) {
                $subscriber = $container->get($name);
                $eventManager->addEventSubscriber($subscriber);
            }

            return EntityManager::create(
                $settings['connection'],
                $config,
                $eventManager
            );
        });

    $container->addShared(Connection::class)
        ->setConcrete(function () use ($container) {
            $em = $container->get(EntityManagerInterface::class);
            return $em->getConnection();
        });

    $container->addShared(BillPayments::class)
        ->setConcrete(function () {
            return new BillPayments($_ENV['QIWI_TOKEN']);
        });

    $container->addShared(UserRepository::class)
        ->setConcrete(function () use ($container) {
            $em = $container->get(EntityManagerInterface::class);
            $repo = $em->getRepository(User::class);
            return new UserRepository($em, $repo);
        });

    $container->addShared(PaymentRepository::class)
        ->setConcrete(function () use ($container) {
            $em = $container->get(EntityManagerInterface::class);
            $repo = $em->getRepository(Payment::class);
            return new PaymentRepository($em, $repo);
        });
};
