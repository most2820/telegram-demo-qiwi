<?php

declare(strict_types=1);

namespace App\Entity\User;

use App\Entity\Flusher;
use SergiX44\Nutgram\Telegram\Types\User\User as UserType;

final class UserService
{
    private UserRepository $userRepository;
    private Flusher $flusher;

    public function __construct(
        UserRepository $userRepository,
        Flusher        $flusher
    )
    {
        $this->userRepository = $userRepository;
        $this->flusher = $flusher;
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
        $this->flusher->flush();
        return $user;
    }
}
