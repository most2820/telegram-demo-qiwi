<?php

namespace App\Data\Fixture;

use App\Entity\Payment\Amount;
use App\Entity\Payment\Payment;
use App\Entity\Payment\Status;
use DateTimeImmutable;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Faker\Generator;

final class PaymentFixture extends AbstractFixture implements DependentFixtureInterface
{
    private Generator $faker;

    public function __construct()
    {
        $this->faker = Factory::create();
    }

    public function load(ObjectManager $manager): void
    {
        $payment = new Payment(
            $this->getReference(UserFixture::REFERENCE_FIRST),
            $this->faker->regexify('[A-Za-z0-9]{20}'),
            $this->faker->regexify('[A-Za-z0-9]{20}'),
            new Amount(
                Amount::CURRENCY_RUB,
                $this->faker->randomNumber(9)
            ),
            new Status(
                $this->faker->regexify('[a-z]{20}'),
                new DateTimeImmutable(),
            ),
            $this->faker->text(10),
            new DateTimeImmutable(),
            new DateTimeImmutable(),
            $this->faker->url,
            $this->faker->phoneNumber(),
        );

        $manager->persist($payment);

        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [
            UserFixture::class
        ];
    }
}