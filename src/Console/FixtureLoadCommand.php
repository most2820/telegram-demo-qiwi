<?php

declare(strict_types=1);

namespace App\Console;

use Doctrine\Common\DataFixtures\Executor\ORMExecutor;
use Doctrine\Common\DataFixtures\Loader;
use Doctrine\Common\DataFixtures\Purger\ORMPurger;
use Doctrine\ORM\EntityManagerInterface;
use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(name: 'fixture:load', description: 'Fixture load')]
final class FixtureLoadCommand extends Command
{
    private EntityManagerInterface $entityManager;

    private array $paths;

    public function __construct(EntityManagerInterface $entityManager, array $paths, ?string $name = null)
    {
        parent::__construct($name);
        $this->entityManager = $entityManager;
        $this->paths = $paths;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $output->writeln('<comment>Loading fixtures</comment>');

        $loader = new Loader();

        foreach ($this->paths as $path) {
            $loader->loadFromDirectory($path);
        }

        $executor = new ORMExecutor($this->entityManager, new ORMPurger());

        $executor->setLogger(static function (string $message) use ($output): void {
            $output->writeln($message);
        });

        $executor->execute($loader->getFixtures());

        $output->writeln('<info>Done!</info>');

        return 0;
    }
}