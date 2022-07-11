<?php

declare(strict_types=1);

namespace App\Command;

use App\Entity\NotFoundException;
use App\Entity\User\UserRepository;
use App\Entity\User\UserService;
use SergiX44\Nutgram\Nutgram;
use SergiX44\Nutgram\Telegram\Attributes\ParseMode;
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

    public function __invoke(Nutgram $nutgram)
    {
        try {
            $user = $this->userRepository->get($nutgram->user()->id);
        } catch (NotFoundException $e) {
            $user = $this->userService->create($nutgram->user());
        }
        $nutgram->sendMessage("Добро пожаловать!\nВаш счет {$user->getBalance()}",
            [
                'chat_id' => $nutgram->chatId(),
                'parse_mode' => ParseMode::MARKDOWN_LEGACY,
                'reply_markup' => InlineKeyboardMarkup::make()
                    ->addRow(InlineKeyboardButton::make("Пополнить", callback_data: 'pay'))
            ]
        );
    }
}
