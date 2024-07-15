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
                $settings = getChanelSettings($id);
                if ($settings['access'] == 'admin') {
                    if ($role == 'admin' || $role == 'creator') {
                        $this->addButtonRow(InlineKeyboardButton::make($name, callback_data: $callback));
                    }
                } else {
                    if ($role == $settings['access']) {
                        $this->addButtonRow(InlineKeyboardButton::make($name, callback_data: $callback));
                    }
                }
                
            }
            $this
            ->addButtonRow(InlineKeyboardButton::make(msg('cancel', $lang), callback_data: '@cancel'))->orNext('none')->showMenu();
        }
    }

    protected function handleChanel(Nutgram $bot)
    {
        $lang = lang($bot->userId());
        //Обрабатываем даныне из колбэка 
        list($chanelId, $userRole) = explode("/", $bot->callbackQuery()->data);
        $chanelInfo = getChanelInfo($chanelId);
        $title = $chanelInfo['title'];
        if(strlen($title) > 35){$title = substr($title, 0, 32);$title .= "..."; }
        
        $access = $chanelInfo['access'] == 'creator' ? msg('creator', $lang) : msg('admin', $lang);
        $capcha = $chanelInfo['capcha'] == 'on' ? msg('stng_on', $lang) : msg('stng_off', $lang);
        $antispam = $chanelInfo['antispam'] == 'on' ? msg('stng_on', $lang) : msg('stng_off', $lang);
        $variables = [
            '{users}' => $chanelInfo['users'],
            '{access}' => $access,
            '{capcha}' => $capcha,
            '{antispam}' => $antispam,
            '{last_update}' => $chanelInfo['latest_updated_at'],
        ];

        $text = "👥   ".$title."\n======================"."\n\n".msg("chanel_settings", $lang, $variables)."\n======================";
        $this
            ->clearButtons()->menuText($text)
            ->addButtonRow(InlineKeyboardButton::make(msg('btn_subscription', $lang), callback_data: $chanelId.'@chanelSubscription'))
            ->addButtonRow(InlineKeyboardButton::make(msg('btn_statistic', $lang), callback_data: $chanelId.'@chanelStatistics'),InlineKeyboardButton::make(msg('btn_access', $lang), callback_data: $chanelId.'@chanelAccess'))
            ->addButtonRow(InlineKeyboardButton::make(msg('btn_capcha', $lang), callback_data: $chanelId.'@chanelCapcha'),InlineKeyboardButton::make(msg('btn_antispam', $lang), callback_data: $chanelId.'@chanelAntispam'))
            ->addButtonRow(InlineKeyboardButton::make(msg('btn_messages', $lang), callback_data: $chanelId.'@chanelMessages'))
            ->addButtonRow(InlineKeyboardButton::make(msg('btn_unlock', $lang), callback_data: $chanelId.'@chanelUnlock'))
            ->addButtonRow(InlineKeyboardButton::make(msg('back', $lang), callback_data: '@start'),InlineKeyboardButton::make(msg('cancel', $lang), callback_data: '@cancel'))->orNext('none')->showMenu();
    }

    protected function chanelStatistics(Nutgram $bot)
    {
        $lang = lang($bot->userId());
        $chanelId = $bot->callbackQuery()->data;
        $userRole = checkUserInChanelRole($bot->userId(), $chanelId);
        $callback = $chanelId.'/'.$userRole.'@handleChanel';
        $this->clearButtons()->menuText(msg('WIP', lang($bot->userId())))
            ->addButtonRow(InlineKeyboardButton::make(msg('back', $lang), callback_data: $callback),InlineKeyboardButton::make(msg('cancel', $lang), callback_data: '@cancel'))
            ->orNext('none')
            ->showMenu();
    }

    protected function chanelSubscription(Nutgram $bot)
    {
        $lang = lang($bot->userId());
        $chanelId = $bot->callbackQuery()->data;
        $userRole = checkUserInChanelRole($bot->userId(), $chanelId);
        $callback = $chanelId.'/'.$userRole.'@handleChanel';
        $msg = msg('subscription', $lang);
        $this->clearButtons()->menuText($msg)
            ->addButtonRow(InlineKeyboardButton::make(msg('back', $lang), callback_data: $callback),InlineKeyboardButton::make(msg('cancel', $lang), callback_data: '@cancel'))
            ->orNext('none')
            ->showMenu();
    }

    public function chanelMessages(Nutgram $bot, $chanelId = "")
    {
        $lang = lang($bot->userId());
        if ($chanelId == "") {
            $chanelId = $bot->callbackQuery()->data;
        }
        if ($chanelId == "") {
            $chanelId = $bot->getUserData('chanelId', $bot->userId());
        }
        $userRole = checkUserInChanelRole($bot->userId(), $chanelId);
        $callback = $chanelId.'/'.$userRole.'@handleChanel';
        $timedMessages = checkTimedMessages($chanelId);
        $existsMsgs = count($timedMessages['exists']);
        $msg = msg('timed_messages', $lang)."(".$existsMsgs."/".$timedMessages['all'].")";
        $this->clearButtons()->menuText($msg);
        if ($existsMsgs<$timedMessages['all']) {
            $this->addButtonRow(InlineKeyboardButton::make(msg('make_timed_msg', $lang), callback_data: $chanelId.'@newTimedMessage'));
        }
        if ($existsMsgs>0) {
            foreach ($timedMessages['exists'] as $msg) {
                $id = $msg['id'];
                $title = $msg['text'];
                $this->addButtonRow(InlineKeyboardButton::make("📃 ".$title, callback_data: $id.'@showTimedMessage'));
            }
        }
        $this->addButtonRow(InlineKeyboardButton::make(msg('back', $lang), callback_data: $callback),InlineKeyboardButton::make(msg('cancel', $lang), callback_data: '@cancel'))
            ->orNext('none')
            ->showMenu();


    }

    protected function chanelCapcha(Nutgram $bot)
    {
        $lang = lang($bot->userId());
        $chanelId = $bot->callbackQuery()->data;
        $userRole = checkUserInChanelRole($bot->userId(), $chanelId);
        $callback = $chanelId.'/'.$userRole.'@handleChanel';
        $this->clearButtons()->menuText(msg('WIP', lang($bot->userId())))
            ->addButtonRow(InlineKeyboardButton::make(msg('back', $lang), callback_data: $callback),InlineKeyboardButton::make(msg('cancel', $lang), callback_data: '@cancel'))
            ->orNext('none')
            ->showMenu();
    }

    protected function chanelAntispam(Nutgram $bot)
    {
        $lang = lang($bot->userId());
        $chanelId = $bot->callbackQuery()->data;
        $userRole = checkUserInChanelRole($bot->userId(), $chanelId);
        $callback = $chanelId.'/'.$userRole.'@handleChanel';
        $this->clearButtons()->menuText(msg('WIP', lang($bot->userId())))
            ->addButtonRow(InlineKeyboardButton::make(msg('back', $lang), callback_data: $callback),InlineKeyboardButton::make(msg('cancel', $lang), callback_data: '@cancel'))
            ->orNext('none')
            ->showMenu();
    }

    protected function chanelAccess(Nutgram $bot)
    {
        $lang = lang($bot->userId());
        $chanelId = $bot->callbackQuery()->data;
        $userRole = checkUserInChanelRole($bot->userId(), $chanelId);
        $callback = $chanelId.'/'.$userRole.'@handleChanel';
        $access = getChanelAccess($chanelId);
        $msg = msg('set_chanel_access', $lang).msg($access, $lang);
        $this->clearButtons()->menuText($msg)
            ->addButtonRow(InlineKeyboardButton::make(msg('admin', $lang), callback_data: $chanelId.'/access/admin@updateChanelSetting'),InlineKeyboardButton::make(msg('creator', $lang), callback_data: $chanelId.'/access/creator@updateChanelSetting'))
            ->addButtonRow(InlineKeyboardButton::make(msg('back', $lang), callback_data: $callback),InlineKeyboardButton::make(msg('cancel', $lang), callback_data: '@cancel'))
            ->orNext('none')
            ->showMenu();

    }

    protected function chanelUnlock(Nutgram $bot)
    {
        $lang = lang($bot->userId());
        $chanelId = $bot->callbackQuery()->data;
        $userRole = checkUserInChanelRole($bot->userId(), $chanelId);
        $callback = $chanelId.'/'.$userRole.'@handleChanel';
        $this->clearButtons()->menuText(msg('WIP', lang($bot->userId())))
            ->addButtonRow(InlineKeyboardButton::make(msg('back', $lang), callback_data: $callback),InlineKeyboardButton::make(msg('cancel', $lang), callback_data: '@cancel'))
            ->orNext('none')
            ->showMenu();
    }

    protected function newTimedMessage(Nutgram $bot)
    {
        $chanelId = $bot->callbackQuery()->data;
        $this->end();
        $createTimedMessage = new createTimedMessage($bot);
        $createTimedMessage->start($bot, $chanelId);
    }

    protected function showTimedMessage(Nutgram $bot)
    {
        $id = $bot->callbackQuery()->data;
        $this->end();
        $createTimedMessage = new createTimedMessage($bot);
        $createTimedMessage->showMessage($bot, $id);
    }

    protected function updateChanelSetting(Nutgram $bot)
    {
        $lang = lang($bot->userId());
        list($chanelId, $param, $value) = explode("/", $bot->callbackQuery()->data);
        superUpdater('chanel_settings', $param, $value, 'chanelId', $chanelId);
        $access = getChanelAccess($chanelId);
        $msg = msg('set_chanel_access', $lang).msg($access, $lang);
        $this->menuText($msg)->orNext('none')->showMenu();

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
