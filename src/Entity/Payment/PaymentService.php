<?php

declare(strict_types=1);

namespace App\Entity\Payment;

use App\Entity\Flusher;
use App\Entity\User\UserRepository;
use App\Entity\User\UserService;
use DateTimeImmutable;
use Qiwi\Api\BillPayments;

final class PaymentService
{
    private PaymentRepository $paymentRepository;
    private Flusher $flusher;
    private UserRepository $userRepository;
    private UserService $userService;
    private BillPayments $billPayments;

    public function __construct(
        PaymentRepository $paymentRepository,
        Flusher           $flusher,
        UserRepository    $userRepository,
        UserService       $userService,
        BillPayments      $billPayments
    )
    {
        $this->paymentRepository = $paymentRepository;
        $this->flusher = $flusher;
        $this->userRepository = $userRepository;
        $this->userService = $userService;
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
            $userId . "_" . rand(1000, 9999) . "_" . time(),
            [
                'amount' => $amount,
                'currency' => 'RUB',
                'comment' => $userId . "_" . rand(1000, 9999),
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
        $this->flusher->flush();
        return $check;
    }

    public function payed(
        int $id
    ): Payment
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
        $this->flusher->flush();
        return $payment;
    }
}
