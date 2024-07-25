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

use SergiX44\Nutgram\Nutgram;
use SergiX44\Nutgram\Conversations\InlineMenu;
use SergiX44\Nutgram\Telegram\Types\Keyboard\InlineKeyboardButton;
use SergiX44\Nutgram\Telegram\Types\Keyboard\InlineKeyboardMarkup;

class createTimedMessage extends InlineMenu
{
    protected Nutgram $bot;

    public function __construct(Nutgram $bot)
    {
        parent::__construct();
        $this->bot = $bot;
    }

    public function start(Nutgram $bot, $chanelId = "")
    {
        if ($chanelId == "") {
            $chanelId = $bot->callbackQuery()->data;
        }
        $lang = lang($bot->userId());
        $bot->setUserData('chanelId', $chanelId, $bot->userId());
        $this->clearButtons()->menuText(msg('enter_timed_msg', $lang))
            ->addButtonRow(InlineKeyboardButton::make(msg('back', $lang), callback_data: '@back'),InlineKeyboardButton::make(msg('cancel', $lang), callback_data: '@cancel'))
            ->orNext('handleMessage')
            ->showMenu();
    }

    protected function handleMessage(Nutgram $bot)
    {
        try {
            $bot->deleteMessage($bot->userId(), $bot->messageId());
        } catch (Exception $e) {
            error_log($e);
        }
        $chanelId = $bot->getUserData('chanelId', $bot->userId());
        $text = $bot->message()->text;
        $lang = lang($bot->userId());
        $msg = msg('your_timed_msg', $lang).$text;
        $bot->setUserData('messageText', $text, $bot->userId());
        $this->clearButtons()->menuText($msg)
            ->addButtonRow(InlineKeyboardButton::make(msg('back', $lang), callback_data: $chanelId.'@start'),InlineKeyboardButton::make(msg('cancel', $lang), callback_data: '@cancel'),InlineKeyboardButton::make(msg('confirm', $lang), callback_data: '@showMessage'))
            ->orNext('none')->showMenu();
    }

    public function showMessage(Nutgram $bot, $timedMessageId = 0)
    {
        $chanelId = ""; $text = ""; $timer = ""; $status = "";
        $lang = lang($bot->userId());
        $userId = $bot->userId();
        if ($timedMessageId == 0) {
            $timedMessageId = $bot->callbackQuery()->data;
        }
        $chanelId = $bot->getUserData('chanelId', $userId);
        $text = $bot->getUserData('messageText', $userId);
        $timer = $bot->getUserData('timer', $userId);
        $status = $bot->getUserData('status', $userId);
        if ($status == NULL) {
            $status = "unsaved";
        }
        $bot->setUserData('status', $status, $userId);
        if (is_numeric($timedMessageId) && $timedMessageId != 0) {
            $timedMessage = getTimedMessage($timedMessageId);
            if ($timedMessage) {
                if ($chanelId == NULL) {
                    $chanelId = $timedMessage['chanelId'];
                    $bot->setUserData('chanelId', $chanelId, $userId);
                }
                if ($text == NULL) {
                    $text = $timedMessage['msg'];
                    $bot->setUserData('messageText', $text, $userId);
                }
                if ($timer == NULL) {
                    $timer = $timedMessage['timer'];
                    $bot->setUserData('timer', $timer, $userId);
                }
                if ($status == NULL) {
                    $status = $timedMessage['status'];
                    $bot->setUserData('status', $status, $userId);
                }
            }
        }
        if ($status == NULL) {
            $status = "unsaved";
            $bot->setUserData('status', $status, $userId);
        }
        if ($timer == NULL) {
            $timer = "1h";
            $bot->setUserData('timer', $timer, $userId);
        }
        
        $title = substr($text, 0, 66); 
        if(strlen($text) > 65){ $title .= "..."; }
        $msg = msg('your_timed_msg', $lang) . $title . "\n\n" . msg('timed_msg_timer', $lang) . $timer . "\n" . msg('timed_msg_status', $lang) . $status;
        
        $this->clearButtons()->menuText($msg)
            ->addButtonRow(
                InlineKeyboardButton::make("💬", callback_data: $chanelId.'@start'),
                InlineKeyboardButton::make("⏱", callback_data: $timedMessageId.'@setTimer'),
                InlineKeyboardButton::make(msg('on_off', $lang), callback_data: $timedMessageId.'@setStatus')
            );
        $btnBack = msg('cancel', $lang);
        $saveCallback = '@saveMessage';
        if (is_numeric($timedMessageId) && $timedMessageId != 0) {
            $this->addButtonRow(
                InlineKeyboardButton::make(msg('delete_timed_msg', $lang), callback_data: $timedMessageId.'/status/deleted@updateTimedMessage'));
            $btnBack = msg('back', $lang);
            $saveCallback = $timedMessageId.'@updateMessage';
        }
        $this->addButtonRow(
                InlineKeyboardButton::make($btnBack, callback_data: '@back'),
                InlineKeyboardButton::make(msg('save_timed_msg', $lang), callback_data: $saveCallback)
            )->orNext('none')->showMenu();
    }

    protected function saveMessage(Nutgram $bot)
    {
        $userId = $bot->userId();
        $chanelId = $bot->getUserData('chanelId', $userId);
        $text = $bot->getUserData('messageText', $userId);
        $timer = $bot->getUserData('timer', $userId);
        $status = $bot->getUserData('status', $userId);
        createTimedMessage($chanelId, $text, $status, $timer);
        $this->clearButtons()->menuText(msg('msg_saved', $lang))->addButtonRow(InlineKeyboardButton::make(msg('confirm', $lang), callback_data: '@back'))->orNext('none')->showMenu();
    }

    protected function updateMessage(Nutgram $bot)
    {
        $userId = $bot->userId();
        $msgId = $bot->callbackQuery()->data;
        $text = $bot->getUserData('messageText', $userId);
        $timer = $bot->getUserData('timer', $userId);
        $status = $bot->getUserData('status', $userId);
        updateTimedMessage($msgId, $text, $status, $timer);
        $this->clearButtons()->menuText(msg('msg_saved', lang($userId)))->addButtonRow(InlineKeyboardButton::make(msg('confirm', $lang), callback_data: '@back'))->orNext('none')->showMenu();
    }

    protected function setTimer(Nutgram $bot)
    {
        $lang = lang($bot->userId());
        $msgId = $bot->callbackQuery()->data;
        $timer = $bot->getUserData('timer', $bot->userId());
        $msg = msg('set_timer', $lang).$timer;
        $this->clearButtons()->menuText($msg)
        ->addButtonRow(InlineKeyboardButton::make("-----------------", callback_data: 'yep'))
        ->addButtonRow(
            InlineKeyboardButton::make("24h", callback_data: $msgId.'/timer/24h@updateTimedMessage'),
            InlineKeyboardButton::make("12h", callback_data: $msgId.'/timer/12h@updateTimedMessage'),
            InlineKeyboardButton::make("6h", callback_data: $msgId.'/timer/6h@updateTimedMessage'),
            InlineKeyboardButton::make("1h", callback_data: $msgId.'/timer/1h@updateTimedMessage'),
        )
        ->addButtonRow(
            InlineKeyboardButton::make("30m", callback_data: $msgId.'/timer/30m@updateTimedMessage'),
            InlineKeyboardButton::make("15m", callback_data: $msgId.'/timer/15m@updateTimedMessage'),
            InlineKeyboardButton::make("10m", callback_data: $msgId.'/timer/10m@updateTimedMessage'),
            InlineKeyboardButton::make("5m", callback_data: $msgId.'/timer/5m@updateTimedMessage'),
            InlineKeyboardButton::make("3m", callback_data: $msgId.'/timer/3m@updateTimedMessage'),
        )
        ->addButtonRow(InlineKeyboardButton::make("-----------------", callback_data: 'yep'))
        ->addButtonRow(InlineKeyboardButton::make(msg('back', $lang), callback_data: $msgId.'@showMessage'),InlineKeyboardButton::make(msg('cancel', $lang), callback_data: '@cancel'))
        ->orNext('none')
        ->showMenu();
    }

    protected function setStatus(Nutgram $bot)
    {
        $lang = lang($bot->userId());
        $msgId = $bot->callbackQuery()->data;
        $status = $bot->getUserData('status', $bot->userId());
        if ($status == "unsaved") {
            $this->clearButtons()->menuText(msg('unsaved_status', $lang))
                ->addButtonRow(InlineKeyboardButton::make(msg('back', $lang), callback_data: $msgId.'@showMessage'))->orNext('none')->showMenu();
        } else {
            $msg = msg('set_status', $lang).$status;
            $this->clearButtons()->menuText($msg);
            if ($status == 'on') {
                $this->addButtonRow(InlineKeyboardButton::make(msg('msg_off', $lang), callback_data: $msgId.'/status/off@updateTimedMessage'));
            } else {
                $this->addButtonRow(InlineKeyboardButton::make(msg('msg_on', $lang), callback_data: $msgId.'/status/on@updateTimedMessage'));
            }
            $this->addButtonRow(InlineKeyboardButton::make(msg('back', $lang), callback_data: $msgId.'@showMessage'),InlineKeyboardButton::make(msg('cancel', $lang), callback_data: '@cancel'))
                ->orNext('none')
                ->showMenu();
        }
    }

    protected function updateTimedMessage(Nutgram $bot)
    {
        list($msgId, $param, $value) = explode("/", $bot->callbackQuery()->data);
        $lang = lang($bot->userId());
        if ($param == "status" && $value == "deleted") {
            $this->clearButtons()->menuText(msg('delete_msg', $lang))
                ->addButtonRow(InlineKeyboardButton::make(msg('cancel', $lang), callback_data: $msgId.'@showMessage'),InlineKeyboardButton::make(msg('confirm', $lang), callback_data: $msgId.'@deleteMsg'))
                ->orNext('none')
                ->showMenu();
        } else {
            $bot->setUserData($param, $value, $bot->userId());
            $this->clearButtons()->menuText(msg($param.'_changed', $lang))
                ->addButtonRow(InlineKeyboardButton::make(msg('confirm', $lang), callback_data: $msgId.'@showMessage'))
                ->orNext('none')
                ->showMenu();
        }
    }

    protected function deleteMsg(Nutgram $bot)
    {
        $msgId = $bot->callbackQuery()->data;
        superUpdater('timed_message', 'status', 'deleted', 'id', $msgId);
        $bot->sendMessage(msg('msg_deleted', lang($bot->userId())));
        $this->end();
        $chanelId = $bot->getUserData('chanelId', $bot->userId());
        $chanelConfigMenu = new ChanelSettings($bot);
        $chanelConfigMenu->chanelMessages($bot, $chanelId);
        $bot->deleteUserData('chanelId', $bot->userId());
        $bot->deleteUserData('messageText', $bot->userId());
        $bot->deleteUserData('timer', $bot->userId());
        $bot->deleteUserData('status', $bot->userId());
    }

    protected function back(Nutgram $bot)
    {
        $this->end();
        $chanelId = $bot->getUserData('chanelId', $bot->userId());
        $chanelConfigMenu = new ChanelSettings($bot);
        $chanelConfigMenu->chanelMessages($bot, $chanelId);
        $bot->deleteUserData('chanelId', $bot->userId());
        $bot->deleteUserData('messageText', $bot->userId());
        $bot->deleteUserData('timer', $bot->userId());
        $bot->deleteUserData('status', $bot->userId());
    }

    protected function cancel(Nutgram $bot)
    {
        $bot->deleteUserData('chanelId', $bot->userId());
        $bot->deleteUserData('messageText', $bot->userId());
        $bot->deleteUserData('timer', $bot->userId());
        $bot->deleteUserData('status', $bot->userId());
        $bot->sendMessage(msg('canceled', lang($bot->userId())));
        $this->end();
    }

    protected function none(Nutgram $bot)
    {
        $bot->deleteUserData('chanelId', $bot->userId());
        $bot->deleteUserData('messageText', $bot->userId());
        $bot->deleteUserData('timer', $bot->userId());
        $bot->deleteUserData('status', $bot->userId());
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
            $paymentMenu = new PaymentMenu($bot);
            $paymentMenu->start($bot);
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
