<?php

declare(strict_types=1);

namespace App\Entity;

use Doctrine\ORM\EntityManagerInterface;

final class Flusher
{
    private EntityManagerInterface $entityManager;

    public function __construct(
        EntityManagerInterface $entityManager
    )
    {
        $this->entityManager = $entityManager;
    }

    public function flush(): void
    {
        $this->entityManager->flush();
    }
}
