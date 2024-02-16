<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\Payment\Payment;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;

final class PaymentRepository
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

    public function get(int $id): ?Payment
    {
        return $this->entityRepository->find($id);
    }

    public function add(Payment $payment): void
    {
        $this->entityManager->persist($payment);
    }

    public function findByBillId(string $billId): ?Payment
    {
        return $this->entityRepository->findOneBy(['billId' => $billId]);
    }
}
