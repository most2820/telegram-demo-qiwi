<?php

declare(strict_types=1);

namespace App\Entity\Payment;

use App\Entity\NotFoundException;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;

final class PaymentRepository
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
    ): Payment|NotFoundException
    {
        return $this->repository->find($id) ?? throw new NotFoundException('Платеж не найден');
    }

    public function add(
        Payment $payment
    )
    {
        $this->entityManager->persist($payment);
    }

    public function findByBillId(
        string $billId
    ): Payment|NotFoundException
    {
        return $this->repository->findOneBy(['billId' => $billId]) ?? throw new NotFoundException('Платеж не найден');
    }
}
