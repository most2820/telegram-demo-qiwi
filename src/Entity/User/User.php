<?php

declare(strict_types=1);

namespace App\Entity\User;

use DateTimeImmutable;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: 'user')]
class User
{
    #[ORM\Column(type: 'bigint', unique: true)]
    #[ORM\Id]
    private $id;
    #[ORM\Column(type: 'string')]
    private string $firstName;
    #[ORM\Column(type: 'string', nullable: true)]
    private ?string $lastName;
    #[ORM\Column(type: 'string', nullable: true)]
    private ?string $username;
    #[ORM\Column(type: 'integer', nullable: true)]
    private int $balance;
    #[ORM\Column(type: 'datetime_immutable')]
    private DateTimeImmutable $createDate;

    public function __construct(
        int|string               $id,
        string            $firstName,
        ?string           $lastName,
        ?string           $username,
        DateTimeImmutable $create_date
    )
    {
        $this->id = $id;
        $this->firstName = $firstName;
        $this->lastName = $lastName;
        $this->username = $username;
        $this->balance = 0;
        $this->createDate = $create_date;
    }

    public function getId(): int|string
    {
        return $this->id;
    }

    public function getFirstName(): string
    {
        return $this->firstName;
    }

    public function getLastName(): ?string
    {
        return $this->lastName;
    }

    public function getUsername(): ?string
    {
        return $this->username;
    }

    public function getCreateDate(): DateTimeImmutable
    {
        return $this->createDate;
    }

    public function getBalance(): int
    {
        return $this->balance;
    }

    public function addToBalance(int $amount): void
    {
        $this->balance += $amount;
    }
}
