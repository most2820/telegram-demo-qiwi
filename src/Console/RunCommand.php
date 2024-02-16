<?php

declare(strict_types=1);

namespace App\Console;

use SergiX44\Nutgram\Nutgram;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(name: 'nutgram:run', description: 'Nutgram run')]
final class RunCommand extends Command
{
    private Nutgram $nutgram;

    public function __construct(Nutgram $nutgram, string $name = null)
    {
        parent::__construct($name);
        $this->nutgram = $nutgram;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $this->nutgram->run();
        return Command::SUCCESS;
    }
}