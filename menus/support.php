<?php 
// ChooseColorMenu.php

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

class SupportMenu extends InlineMenu
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
        $this->clearButtons()->menuText(msg('welcome_support_msg', $lang))
            ->addButtonRow(InlineKeyboardButton::make(msg('frequent_msgs', $lang), callback_data: '@frequentMsgs'))
            ->addButtonRow(InlineKeyboardButton::make(msg('contact_support', $lang), callback_data: '@contactSupport'))
            ->addButtonRow(InlineKeyboardButton::make(msg('cancel', $lang), callback_data: '@cancel'))
            ->orNext('none')
            ->showMenu();
    }

    protected function frequentMsgs(Nutgram $bot)
    {
        $lang = lang($bot->userId());
        $this->clearButtons()->menuText(msg('WIP', lang($bot->userId())))
            ->addButtonRow(InlineKeyboardButton::make(msg('back', $lang), callback_data: '@start'))
            ->addButtonRow(InlineKeyboardButton::make(msg('cancel', $lang), callback_data: '@cancel'))
            ->orNext('none')
            ->showMenu();
    }

    protected function contactSupport(Nutgram $bot)
    {
        $lang = lang($bot->userId());
        $update = $bot->update();
        $text = isset($update->message) ? $update->message->text : "";

        $msg = msg('contact_support_msg', $lang);
        if ($text != "") {
            $msg .= msg('current_support_msg', $lang) . $text;
        }
        $this->clearButtons()->menuText($msg);
        if ($text != "") {
            $this->addButtonRow(InlineKeyboardButton::make(msg('send_support_message', $lang), callback_data: '@sendMessage'));
        }
        $this->addButtonRow(InlineKeyboardButton::make(msg('back', $lang), callback_data: '@start'))
            ->addButtonRow(InlineKeyboardButton::make(msg('cancel', $lang), callback_data: '@cancel'))
            ->orNext('contactSupport')
            ->showMenu();
    }

    protected function sendMessage(Nutgram $bot)
    {
        $lang = lang($bot->userId());
        $this->clearButtons()->menuText(msg('WIP', lang($bot->userId())))
            ->addButtonRow(InlineKeyboardButton::make(msg('back', $lang), callback_data: '@start'))
            ->addButtonRow(InlineKeyboardButton::make(msg('cancel', $lang), callback_data: '@cancel'))
            ->orNext('none')
            ->showMenu();
    }

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
            $bot->sendMessage(msg('WIP', $lang));
        } elseif(str_contains($text, msg('menu_promote', $lang))) {
            $this->end();
            $bot->sendMessage(msg('WIP', $lang));
        } elseif(str_contains($text, msg('menu_unlock', $lang))) {
            $this->end();
            $bot->sendMessage(msg('WIP', $lang));
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
