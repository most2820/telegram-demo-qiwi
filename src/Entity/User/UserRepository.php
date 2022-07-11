<?php

declare(strict_types=1);

namespace App\Entity\User;

use App\Entity\NotFoundException;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;

final class UserRepository
{
    private EntityRepository $repository;
    private EntityManagerInterface $entityManager;

    public function __construct(
        EntityManagerInterface $entityManager,
        EntityRepository       $repository
    )
    {
        $this->entityManager = $entityManager;
        $this->repository = $repository;
    }

    public function get(
        int $id
    ): User|NotFoundException
    {
        return $this->repository->find($id) ?? throw new NotFoundException('User not found');
    }

    public function add(
        User $user
    )
    {
        $this->entityManager->persist($user);
    }
}
