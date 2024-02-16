<?php

declare(strict_types=1);

namespace App\Service;

use App\Entity\User\User;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use SergiX44\Nutgram\Telegram\Types\User\User as UserType;

final class UserService
{
    private UserRepository $userRepository;
    private EntityManagerInterface $entityManager;

    public function __construct(
        UserRepository         $userRepository,
        EntityManagerInterface $entityManager
    )
    {
        $this->userRepository = $userRepository;
        $this->entityManager = $entityManager;
    }

    public function create(
        UserType $user
    ): User
    {
        $user = new User(
            $user->id,
            $user->first_name,
            $user->last_name,
            $user->username,
            new \DateTimeImmutable()
        );
        $this->userRepository->add($user);
        $this->entityManager->flush();
        return $user;
    }
}
