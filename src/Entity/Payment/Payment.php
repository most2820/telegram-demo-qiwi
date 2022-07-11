<?php

declare(strict_types=1);

namespace App\Entity\Payment;

use App\Entity\User\User;
use DateTimeImmutable;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\HasLifecycleCallbacks]
#[ORM\Table(name: 'payment')]
final class Payment
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private int $id;
    #[ORM\ManyToOne(targetEntity: User::class, inversedBy: 'user')]
    private User $user;
    #[ORM\Column(type: 'string')]
    private string $siteId;
    #[ORM\Column(type: 'string')]
    private string $billId;
    #[ORM\Column(type: 'string')]
    private string $amountCurrency;
    #[ORM\Column(type: 'integer')]
    private int $amountValue;
    #[ORM\Column(type: 'string')]
    private string $statusValue;
    #[ORM\Column(type: 'datetime_immutable')]
    private DateTimeImmutable $changedDateTime;
    #[ORM\Column(type: 'string')]
    private string $comment;
    #[ORM\Column(type: 'datetime_immutable')]
    private DateTimeImmutable $creationDateTime;
    #[ORM\Column(type: 'datetime_immutable')]
    private DateTimeImmutable $expirationDateTime;
    #[ORM\Column(type: 'string')]
    private string $payUrl;
    #[ORM\Column(type: 'string')]
    private string $recipientPhoneNumber;

    public function __construct(
        User              $user,
        string            $siteId,
        string            $billId,
        Amount            $amount,
        Status            $status,
        string            $comment,
        DateTimeImmutable $creationDateTime,
        DateTimeImmutable $expirationDateTime,
        string            $payUrl,
        string            $recipientPhoneNumber
    )
    {
        $this->user = $user;
        $this->siteId = $siteId;
        $this->billId = $billId;
        $this->amountCurrency = $amount->getCurrency();
        $this->amountValue = $amount->getValue();
        $this->statusValue = $status->getValue();
        $this->changedDateTime = $status->getChangedDateTime();
        $this->comment = $comment;
        $this->creationDateTime = $creationDateTime;
        $this->expirationDateTime = $expirationDateTime;
        $this->payUrl = $payUrl;
        $this->recipientPhoneNumber = $recipientPhoneNumber;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getBillId(): string
    {
        return $this->billId;
    }

    public function getAmountValue(): int
    {
        return $this->amountValue;
    }

    public function getComment(): string
    {
        return $this->comment;
    }

    public function getPayUrl(): string
    {
        return $this->payUrl;
    }

    public function getUser(): User
    {
        return $this->user;
    }

    public function payed()
    {
        $this->statusValue = 'PAID';
    }

    public function isPaid(): bool
    {
        return $this->statusValue == 'PAID';
    }
}
