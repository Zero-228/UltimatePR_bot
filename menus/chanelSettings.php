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
use SergiX44\Nutgram\Support\DeepLink;

class ChanelSettings extends InlineMenu
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
        $groups = checkUsersChanel($bot->userId());
        $deeplink = new DeepLink();
        $adminConf = ['post_messages', 'edit_messages', 'delete_messages', 'restrict_members', 'invite_users', 'pin_messages', 'manage_topics', 'manage_chat', 'anonymous'];
        $deep_link = $deeplink->startGroup(BOT_USERNAME, '', $adminConf);
        if ($groups == 'chanel_not_found') {
            //$this->next('addChanel');
            $this->clearButtons()->menuText(msg('no_chanel', $lang))->addButtonRow(InlineKeyboardButton::make(msg('add_chanel_btn', $lang), url: $deep_link))
            ->addButtonRow(InlineKeyboardButton::make(msg('cancel', $lang), callback_data: '@cancel'))->orNext('none')->showMenu();
        } else {
            $this->clearButtons()->menuText(msg('select_chanel', $lang))->addButtonRow(InlineKeyboardButton::make(msg('add_chanel_btn', $lang), url: $deep_link));
            foreach ($groups as $chanel) {
                $name = $chanel['name'];
                $role = $chanel['role'];
                $id = $chanel['chanelId'];
                $callback = $id.'/'.$role.'@handleChanel';
                $this->addButtonRow(InlineKeyboardButton::make($name, callback_data: $callback));
            }
            $this
            ->addButtonRow(InlineKeyboardButton::make(msg('cancel', $lang), callback_data: '@cancel'))->orNext('none')->showMenu();
        }
    }

    public function handleChanel(Nutgram $bot)
    {
        $lang = lang($bot->userId());
        //Обрабатываем даныне из колбэка 
        list($chanelId, $userRole) = explode("/", $bot->callbackQuery()->data);
        $chanelInfo = getChanelInfo($chanelId);
        $text = $chanelInfo['title']."\n\n"."chanel settings";
        $this
            ->clearButtons()->menuText($text)
            ->addButtonRow(InlineKeyboardButton::make(msg('back', $lang), callback_data: '@start'),InlineKeyboardButton::make(msg('cancel', $lang), callback_data: '@cancel'))->orNext('none')->showMenu();
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
            $bot->sendMessage(msg('WIP', $lang));
        } else {
            $msg = "You send: ".$text;
            $this
            ->clearButtons()->menuText($msg)
            ->addButtonRow(InlineKeyboardButton::make(msg('cancel', $lang), callback_data: '@cancel'))->orNext('none')->showMenu();
        }
    }
}
?>
