<?php

declare(strict_types=1);

namespace App\Service;

use App\Entity\Payment\Amount;
use App\Entity\Payment\Payment;
use App\Entity\Payment\Status;
use App\Repository\PaymentRepository;
use App\Repository\UserRepository;
use DateTimeImmutable;
use Doctrine\ORM\EntityManagerInterface;
use Qiwi\Api\BillPayments;

final class PaymentService
{
    private PaymentRepository $paymentRepository;
    private EntityManagerInterface $entityManager;
    private UserRepository $userRepository;
    private BillPayments $billPayments;

    public function __construct(
        PaymentRepository      $paymentRepository,
        EntityManagerInterface $entityManager,
        UserRepository         $userRepository,
        BillPayments           $billPayments
    )
    {
        $this->paymentRepository = $paymentRepository;
        $this->entityManager = $entityManager;
        $this->userRepository = $userRepository;
        $this->billPayments = $billPayments;
    }

    public function create(
        int $userId,
        int $amount
    ): Payment
    {
        if ($amount < 5) {
            throw new \DomainException('Минимальная сумма для пополнения 5 руб.');
        }

        $user = $this->userRepository->get($userId);

        $response = $this->billPayments->createBill(
            $user->getId() . "_" . rand(1000, 9999) . "_" . time(),
            [
                'amount' => $amount,
                'currency' => Amount::CURRENCY_RUB,
                'comment' => $userId . "_" . rand(1000, 9999),
                'account' => $user->getId()
            ]
        );

        $check = new Payment(
            $user,
            $response['siteId'],
            $response['billId'],
            new Amount(
                $response['amount']['currency'],
                $amount
            ),
            new Status(
                $response['status']['value'],
                new DateTimeImmutable($response['status']['changedDateTime']),
            ),
            $response['comment'],
            new DateTimeImmutable($response['creationDateTime']),
            new DateTimeImmutable($response['expirationDateTime']),
            $response['payUrl'],
            $response['recipientPhoneNumber'],
        );
        $this->paymentRepository->add($check);
        $this->entityManager->flush();
        return $check;
    }

    public function payed(int $id): Payment
    {
        $payment = $this->paymentRepository->get($id);
        if ($payment->isPaid()) {
            throw new \DomainException('Счет уже оплачен!');
        }
        $billInfo = $this->billPayments->getBillInfo($payment->getBillId());
        if ($billInfo['status'] != 'PAID') {
            throw new \DomainException("Вы не оплатили счет!");
        }
        $payment->payed();
        $user = $this->userRepository->get($payment->getUser()->getId());
        $user->addToBalance($payment->getAmountValue());
        $this->entityManager->flush();
        return $payment;
    }
}
