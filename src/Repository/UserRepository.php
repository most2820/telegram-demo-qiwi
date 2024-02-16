<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\User\User;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;

final class UserRepository
{
    private EntityManagerInterface $entityManager;
    private EntityRepository $entityRepository;

    public function __construct(
        EntityManagerInterface $entityManager,
        EntityRepository       $entityRepository
    )
    {
        $this->entityManager = $entityManager;
        $this->entityRepository = $entityRepository;
    }

    public function get(int $id): ?User
    {
        return $this->entityRepository->find($id);
    }

    public function add(User $user): void
    {
        $this->entityManager->persist($user);
    }
}
