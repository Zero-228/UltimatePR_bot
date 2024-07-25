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

class FilterMenu extends InlineMenu
{
    protected Nutgram $bot;
    protected ?string $chanelId = null;

    public function __construct(Nutgram $bot, $chanelId)
    {
        parent::__construct();
        $this->bot = $bot;
        if (str_contains($chanelId, '/')) {
            $parts = explode('/', $chanelId);
            $this->chanelId = $parts[0];
        } else {
            $this->chanelId = $chanelId;
        }
    }

    public function start(Nutgram $bot)
    {
        $lang = lang($bot->userId());
        $chanelId = $this->chanelId;
        $chanelInfo = getChanelInfo($chanelId);
        $antispam = $chanelInfo['antispam'] == 'on' ? msg('stng_on', $lang) : msg('stng_off', $lang);
        $antiflood = $chanelInfo['antiflood'] == 'on' ? msg('stng_on', $lang) : msg('stng_off', $lang);
        $antilink = $chanelInfo['antilink'] == 'on' ? msg('stng_on', $lang) : msg('stng_off', $lang);
        $antibot = $chanelInfo['antibot'] == 'on' ? msg('stng_on', $lang) : msg('stng_off', $lang);
        $variables = [
            '{antispam}' => $antispam,
            '{antiflood}' => $antiflood,
            '{antilink}' => $antilink,
            '{antibot}' => $antibot,
        ];
        $this->clearButtons()->menuText(msg('filter_menu', $lang, $variables))
            ->addButtonRow(InlineKeyboardButton::make(msg('btn_botfilter', $lang), callback_data: $chanelId.'@filterAntibot'))
            ->addButtonRow(InlineKeyboardButton::make(msg('btn_link', $lang), callback_data: $chanelId.'@filterAntilink'), InlineKeyboardButton::make(msg('btn_flood', $lang), callback_data: $chanelId.'@filterAntiflood'))
            ->addButtonRow(InlineKeyboardButton::make(msg('btn_antispam', $lang), callback_data: $chanelId.'@WIP'))
            ->addButtonRow(InlineKeyboardButton::make(msg('back', $lang), callback_data: '@back'))
            ->orNext('none')
            ->showMenu();
    }

    protected function WIP(Nutgram $bot)
    {
        $lang = lang($bot->userId());
        $chanelId = $bot->callbackQuery()->data;
        $userRole = checkUserInChanelRole($bot->userId(), $chanelId);
        $this->clearButtons()->menuText(msg('WIP', lang($bot->userId())))
            ->addButtonRow(InlineKeyboardButton::make(msg('back', $lang), callback_data: '@start'),InlineKeyboardButton::make(msg('cancel', $lang), callback_data: '@cancel'))
            ->orNext('none')
            ->showMenu();
    }

    protected function filterAntispam(Nutgram $bot)
    {
        $lang = lang($bot->userId());
        $chanelId = $bot->callbackQuery()->data;
        $userRole = checkUserInChanelRole($bot->userId(), $chanelId);
        $callback = $chanelId.'/'.$userRole.'@start';
        $chanelInfo = getChanelInfo($chanelId);
        $antispam = $chanelInfo['antispam'];
        $msg = msg('set_chanel_antispam', $lang).msg('stng_'.$antispam, $lang);
        $this->clearButtons()->menuText($msg)
            ->addButtonRow(InlineKeyboardButton::make(msg('stng_off', $lang)."", callback_data: $chanelId.'/antispam/off@updateChanelSetting'),InlineKeyboardButton::make(msg('stng_on', $lang)."", callback_data: $chanelId.'/antispam/on@updateChanelSetting'))
            ->addButtonRow(InlineKeyboardButton::make(msg('back', $lang), callback_data: $callback),InlineKeyboardButton::make(msg('cancel', $lang), callback_data: '@cancel'))
            ->orNext('none')
            ->showMenu();
    }

    protected function filterAntiflood(Nutgram $bot)
    {
        $lang = lang($bot->userId());
        $chanelId = $bot->callbackQuery()->data;
        $userRole = checkUserInChanelRole($bot->userId(), $chanelId);
        $callback = $chanelId.'/'.$userRole.'@start';
        $chanelInfo = getChanelInfo($chanelId);
        $antiflood = $chanelInfo['antiflood'];
        $msg = msg('set_chanel_antiflood', $lang).msg('stng_'.$antiflood, $lang);
        $this->clearButtons()->menuText($msg)
            ->addButtonRow(InlineKeyboardButton::make(msg('stng_off', $lang)."", callback_data: $chanelId.'/antiflood/off@updateChanelSetting'),InlineKeyboardButton::make(msg('stng_on', $lang)."", callback_data: $chanelId.'/antiflood/on@updateChanelSetting'))
            ->addButtonRow(InlineKeyboardButton::make(msg('back', $lang), callback_data: $callback),InlineKeyboardButton::make(msg('cancel', $lang), callback_data: '@cancel'))
            ->orNext('none')
            ->showMenu();
    }

    protected function filterAntilink(Nutgram $bot)
    {
        $lang = lang($bot->userId());
        $chanelId = $bot->callbackQuery()->data;
        $userRole = checkUserInChanelRole($bot->userId(), $chanelId);
        $callback = $chanelId.'/'.$userRole.'@start';
        $chanelInfo = getChanelInfo($chanelId);
        $antilink = $chanelInfo['antilink'];
        $msg = msg('set_chanel_antilink', $lang).msg('stng_'.$antilink, $lang);
        $this->clearButtons()->menuText($msg)
            ->addButtonRow(InlineKeyboardButton::make(msg('stng_off', $lang)."", callback_data: $chanelId.'/antilink/off@updateChanelSetting'),InlineKeyboardButton::make(msg('stng_on', $lang)."", callback_data: $chanelId.'/antilink/on@updateChanelSetting'))
            ->addButtonRow(InlineKeyboardButton::make(msg('back', $lang), callback_data: $callback),InlineKeyboardButton::make(msg('cancel', $lang), callback_data: '@cancel'))
            ->orNext('none')
            ->showMenu();
    }

    protected function filterAntibot(Nutgram $bot)
    {
        $lang = lang($bot->userId());
        $chanelId = $bot->callbackQuery()->data;
        $userRole = checkUserInChanelRole($bot->userId(), $chanelId);
        $callback = $chanelId.'/'.$userRole.'@start';
        $chanelInfo = getChanelInfo($chanelId);
        $antibot = $chanelInfo['antibot'];
        $msg = msg('set_chanel_antibot', $lang).msg('stng_'.$antibot, $lang);
        $this->clearButtons()->menuText($msg)
            ->addButtonRow(InlineKeyboardButton::make(msg('stng_off', $lang)."", callback_data: $chanelId.'/antibot/off@updateChanelSetting'),InlineKeyboardButton::make(msg('stng_on', $lang)."", callback_data: $chanelId.'/antibot/on@updateChanelSetting'))
            ->addButtonRow(InlineKeyboardButton::make(msg('back', $lang), callback_data: $callback),InlineKeyboardButton::make(msg('cancel', $lang), callback_data: '@cancel'))
            ->orNext('none')
            ->showMenu();
    }

    public function handleColor(Nutgram $bot)
    {
        $color = $bot->callbackQuery()->data;
        $this->menuText("Choosen: $color!")->showMenu();
        error_log($color);
    }

    protected function updateChanelSetting(Nutgram $bot)
    {
        $lang = lang($bot->userId());
        list($chanelId, $param, $value) = explode("/", $bot->callbackQuery()->data);
        superUpdater('chanel_settings', $param, $value, 'chanelId', $chanelId);
        $chanelInfo = getChanelInfo($chanelId);
        $status = '';
        if ($param == 'access') {
            $status = msg($chanelInfo['access'], $lang);
        } else {
            $status = msg("stng_".$chanelInfo[$param], $lang);
        }
        $msg = msg('set_chanel_'.$param, $lang).$status;
        $this->menuText($msg)->orNext('none')->showMenu();

    }

    public function cancel(Nutgram $bot)
    {
        $bot->sendMessage(msg('canceled', lang($bot->userId())));
        $this->end();
    }

    public function back(Nutgram $bot)
    {
        $userRole = checkUserInChanelRole($bot->userId(), $this->chanelId);
        $this->end();
        $chanelConfigMenu = new ChanelSettings($bot);
        $chanelConfigMenu->handleChanel($bot, $this->chanelId, $userRole);
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
