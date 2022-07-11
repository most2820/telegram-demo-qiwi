<?php

declare(strict_types=1);

namespace App\Callback;

use App\Entity\Payment\PaymentRepository;
use App\Entity\Payment\PaymentService;
use SergiX44\Nutgram\Nutgram;
use SergiX44\Nutgram\Telegram\Attributes\ParseMode;
use SergiX44\Nutgram\Telegram\Types\Keyboard\InlineKeyboardButton;
use SergiX44\Nutgram\Telegram\Types\Keyboard\InlineKeyboardMarkup;

final class PayCallback
{
    private PaymentService $paymentService;
    private PaymentRepository $paymentRepository;

    public function __construct(
        PaymentService    $paymentService,
        PaymentRepository $paymentRepository
    )
    {
        $this->paymentService = $paymentService;
        $this->paymentRepository = $paymentRepository;
    }

    public function __invoke(Nutgram $nutgram)
    {
        $nutgram->deleteMessage(
            $nutgram->update()->callback_query->message->chat->id,
            $nutgram->update()->callback_query->message->message_id
        );
        $nutgram->sendMessage("Введите сумму для пополнения!",
            [
                'chat_id' => $nutgram->chatId(),
                'parse_mode' => ParseMode::MARKDOWN_LEGACY,
            ]
        );
    }

    public function bill(Nutgram $nutgram)
    {
        $payment = $this->paymentService->create($nutgram->user()->id, (int)$nutgram->message()->text);
        $nutgram->sendMessage("Вам нужно отправить {$payment->getAmountValue()} руб. на наш счет QIWI\nссылку: {$payment->getPayUrl()}\nУказав комментарий к оплате: {$payment->getComment()}",
            [
                'chat_id' => $nutgram->chatId(),
                'reply_markup' => InlineKeyboardMarkup::make()
                    ->addRow(InlineKeyboardButton::make("Ссылка на оплату", url: $payment->getPayUrl()))
                    ->addRow(InlineKeyboardButton::make("Проверить оплату", callback_data: "check {$payment->getBillId()}"))
            ]
        );
    }

    public function check(Nutgram $nutgram, string $param)
    {
        $payment = $this->paymentService->payed(
            $this->paymentRepository->findByBillId($param)->getId()
        );
        $nutgram->sendMessage("На ваш счет начислен платеж в размере {$payment->getAmountValue()} руб.",
            [
                'chat_id' => $nutgram->chatId(),
                'parse_mode' => ParseMode::MARKDOWN_LEGACY,
            ]
        );
    }
}
