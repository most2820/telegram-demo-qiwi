<?php

declare(strict_types=1);

namespace App\Command;

use App\Callback\PayCallback;
use App\Repository\UserRepository;
use App\Service\UserService;
use SergiX44\Nutgram\Nutgram;
use SergiX44\Nutgram\Telegram\Types\Keyboard\InlineKeyboardButton;
use SergiX44\Nutgram\Telegram\Types\Keyboard\InlineKeyboardMarkup;

final class StartCommand
{
    private UserRepository $userRepository;
    private UserService $userService;

    public function __construct(
        UserRepository $userRepository,
        UserService    $userService
    )
    {
        $this->userRepository = $userRepository;
        $this->userService = $userService;
    }

    public function __invoke(Nutgram $nutgram): void
    {
        $user = $this->userRepository->get($nutgram->user()->id) ?: $this->userService->create($nutgram->user());

        $nutgram->sendMessage("Добро пожаловать!\nВаш счет {$user->getBalance()}",
            [
                'chat_id' => $nutgram->user()->id,
                'reply_markup' => InlineKeyboardMarkup::make()
                    ->addRow(InlineKeyboardButton::make("Пополнить", callback_data: PayCallback::CALLBACK_QIWI_PAY))
            ]
        );
    }
}