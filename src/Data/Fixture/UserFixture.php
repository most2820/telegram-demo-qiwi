<?php

declare(strict_types=1);

namespace App\Data\Fixture;

use App\Entity\User\User;
use DateTimeImmutable;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Faker\Generator;

final class UserFixture extends AbstractFixture
{
    public const REFERENCE_FIRST = 'user-first';

    private Generator $faker;

    public function __construct()
    {
        $this->faker = Factory::create();
    }

    public function load(ObjectManager $manager): void
    {
        $user = new User(
            $this->faker->randomNumber(9),
            $this->faker->firstName,
            $this->faker->lastName,
            $this->faker->userName,
            DateTimeImmutable::createFromMutable($this->faker->dateTime)
        );

        $manager->persist($user);

        $this->addReference(self::REFERENCE_FIRST, $user);

        $manager->flush();
    }
}