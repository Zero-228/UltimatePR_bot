<?php 
/**
 * UltimatePR Chatbot
 * 
 * Licensed under the Simple Commercial License.
 * 
 * Copyright (c) 2024 Nikita Shkilov nikshkilov@yahoo.com
 * 
 * All rights reserved.
 * 
 * This file is part of PenaltyPuff bot. The use of this file is governed by the
 * terms of the Simple Commercial License, which can be found in the LICENSE file
 * in the root directory of this project.
 */
require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../localization.php';
require_once __DIR__ . '/../functions.php';

use SergiX44\Nutgram\Nutgram;
use SergiX44\Nutgram\Conversations\InlineMenu;
use SergiX44\Nutgram\Telegram\Types\Keyboard\InlineKeyboardButton;
use SergiX44\Nutgram\Telegram\Types\Keyboard\InlineKeyboardMarkup;

class PaymentMenu extends InlineMenu
{
    protected Nutgram $bot;

    public function __construct(Nutgram $bot)
    {
        parent::__construct();
        $this->bot = $bot;
    }

    public function start(Nutgram $bot)
    {
        $lang = lang($bot->userId());
        $this->menuText('Choose a color:')
            ->addButtonRow(InlineKeyboardButton::make('Opt.1 - 3$', callback_data: '3@paymentMethod'))
            ->addButtonRow(InlineKeyboardButton::make('Opt.2 - 5$', callback_data: '5@paymentMethod'))
            ->addButtonRow(InlineKeyboardButton::make('Opt.3 - 8$', callback_data: '8@paymentMethod'))
            ->orNext('none')
            ->showMenu();
    }

    # TODO Add paymentHandler
    ## 3 invoice options:
    ### 1. One-time post to groups
    ### 2. Repeated post to groups
    ### 3. One-time post to bot users
    ## 1 subscription options:
    ### access to all features for 75c/week

    public function paymentMethod(Nutgram $bot, $chanelId = null)
    {
        $lang = lang($bot->userId());
        $amount = $bot->callbackQuery()->data;
        $callback = $amount."@handlePayment";
        if ($chanelId != null && $chanelId < 0) {
            error_log("chanelId: ".$chanelId);
            $callback = $chanelId."@handleSubscription";
        } else {
            $chanelId = 0;
        }
        if (str_contains($amount, '-100')) {
            $chanelId = $amount;
            error_log("chanelId: ".$chanelId);
            $callback = $chanelId."@handleSubscription";
        }
        $this->clearButtons()->menuText('Select the payment method:')
            ->addButtonRow(InlineKeyboardButton::make('Visa/Mastercard', callback_data: "card/".$callback))
            ->addButtonRow(InlineKeyboardButton::make('Telegram Stars', callback_data: "stars/".$callback));
        $this->addButtonRow(InlineKeyboardButton::make(msg('cancel', $lang), callback_data: '@cancel'));
        $this->orNext('none')
            ->showMenu();
    }

    public function handlePayment(Nutgram $bot)
    {
        $lang = lang($bot->userId());
        list($paymentType, $amount) = explode("/", $bot->callbackQuery()->data);
        $payment_token_provider = PAYMENT_TOKEN_PROVIDER;
        switch ($amount) {
            case 2:
                $payload = "1 option";
                $description = "Paid " . $payload;
                break;
            case 3:
                $payload = "2 option";
                $description = "Paid " . $payload;
                break;
            case 5:
                $payload = "3 option";
                $description = "Paid " . $payload;
                break;
        }
        $title = "Payment for " . $payload;
        $currency = "USD";
        $prices = [
            [
                'label' => $title,
                'amount' => $amount * 100
            ]
        ];
        $bot->sendInvoice(
            title: $title,
            description: $description,
            payload: $payload,
            provider_token: $payment_token_provider,
            currency: $currency,
            prices: $prices,
            need_name: True,
            need_phone_number: True,
            need_email: True,
            need_shipping_address: false
        );
        $this->clearButtons()->menuText("Amount: $amount!")
            ->addButtonRow(InlineKeyboardButton::make(msg('back', $lang), callback_data: $amount."@paymentMethod"),InlineKeyboardButton::make(msg('cancel', $lang), callback_data: '@cancel'))
            ->showMenu();
    }

    public function handleSubscription(Nutgram $bot)
    {
        $lang = lang($bot->userId());
        list($paymentType, $chanelId) = explode("/", $bot->callbackQuery()->data);
        
        $this->clearButtons()->menuText("Channel ID: $chanelId!")
            ->addButtonRow(InlineKeyboardButton::make(msg('back', $lang), callback_data: $chanelId."@paymentMethod"),InlineKeyboardButton::make(msg('cancel', $lang), callback_data: '@cancel'))
            ->showMenu();
    }
    /*
    $payment_token_provider = PAYMENT_TOKEN_PROVIDER;
    switch ($amount) {
        case 200:
            $payload = "2 weeks";
            $description = msg("description_option") . $payload;
            break;
        case 800:
            $payload = "6 months";
            $description = msg("description_option") . $payload;
            break;
        case 1200:
            $payload = "12 months";
            $description = msg("description_option") . $payload;
            break;
    }
    $title = "Access to the private channel for " . $payload;
    $currency = "USD";
    $prices = [
        [
            'label' => $title,
            'amount' => $amount * 100
        ]
    ];
    $bot->sendInvoice(
        title: $title,
        description: $description,
        payload: $payload,
        provider_token: $payment_token_provider,
        currency: $currency,
        prices: $prices,
        need_name: True,
        need_phone_number: True,
        need_email: True,
        need_shipping_address: false
    );*/

    public function cancel(Nutgram $bot)
    {
        $bot->sendMessage(msg('canceled', lang($bot->userId())));
        $this->end();
    }

    protected function none(Nutgram $bot)
    {
        $text = $bot->message()->text;
        $lang = lang($bot->userId());
        if (str_contains($text, 'testMenu')) {
            $this->end();
            $colorMenu = new ChooseColorMenu($bot);
            $colorMenu->start($bot);
        } elseif(str_contains($text, msg('menu_config', $lang))) {
            $this->end();
            $chanelConfigMenu = new ChanelSettings($bot);
            $chanelConfigMenu->start($bot);
        } elseif(str_contains($text, msg('menu_profile', $lang))) {
            $this->end();
            $profileMenu = new ProfileMenu($bot);
            $profileMenu->start($bot);
        } elseif(str_contains($text, msg('menu_promote', $lang))) {
            $this->end();
            $bot->sendMessage(msg('WIP', $lang));
        } elseif(str_contains($text, msg('change_language', $lang))) {
            $this->end();
            $changeLangInlineKeyboard = InlineKeyboardMarkup::make()->addRow(InlineKeyboardButton::make(msg('language', 'en'), null, null, 'callback_change_lang_to en'))->addRow(InlineKeyboardButton::make(msg('language', 'uk'), null, null, 'callback_change_lang_to uk'))->addRow(InlineKeyboardButton::make(msg('language', 'ru'), null, null, 'callback_change_lang_to ru'));
            $bot->sendMessage(msg('choose_language', lang($bot->userId())), reply_markup: $changeLangInlineKeyboard);
        } elseif(str_contains($text, msg('menu_support', $lang))) {
            $this->end();
            $supportMenu = new SupportMenu($bot);
            $supportMenu->start($bot);
        } else {
            $msg = "You send: ".$text;
            $this
            ->clearButtons()->menuText($msg)
            ->addButtonRow(InlineKeyboardButton::make(msg('cancel', $lang), callback_data: '@cancel'))->orNext('none')->showMenu();
        }
    }
}
?>
