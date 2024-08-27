<?php  
/**
 * DOTEBO bot
 * 
 * Licensed under the Simple Commercial License.
 * 
 * Copyright (c) 2024 Nikita Shkilov nikshkilov@yahoo.com
 * 
 * All rights reserved.
 * 
 * This file is part of DOTEBO bot. The use of this file is governed by the
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

class LanguageMenu extends InlineMenu
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
        $this->clearButtons()->menuText(msg('choose_language', $lang))
            ->addButtonRow(InlineKeyboardButton::make(msg('language', 'en'), callback_data: 'en@handleLanguage'))
            ->addButtonRow(InlineKeyboardButton::make(msg('language', 'uk'), callback_data: 'uk@handleLanguage'))
            ->addButtonRow(InlineKeyboardButton::make(msg('language', 'ru'), callback_data: 'ru@handleLanguage'))
            ->addButtonRow(InlineKeyboardButton::make(msg('cancel', $lang), callback_data: '@cancel'))
            ->orNext('none')
            ->showMenu();
    }

    public function handleLanguage(Nutgram $bot)
    {
        $language = $bot->callbackQuery()->data;
        changeLanguage($bot->userId(), $language);
        $lang = lang($bot->userId());
        $bot->sendMessage(msg('language_changed', $lang), reply_markup: constructMenuButtons(lang($bot->userId())));
        $this->end();
    }

    public function cancel(Nutgram $bot)
    {
        $lang = lang($bot->userId());
        $bot->sendMessage(msg('canceled', $lang));
        $this->end();
    }

    public function none(Nutgram $bot)
    {
        $lang = lang($bot->userId());
        $role = checkRole($bot->userId());
        $text = $bot->message()->text;
        if (str_contains($text, 'testMenu')) {
            $this->end();
            $colorMenu = new ChooseColorMenu($bot);
            $colorMenu->start($bot);
        }
        elseif (str_contains($text, msg('change_language', $lang))) {
            $this->end();
            $languageMenu = new LanguageMenu($bot);
            $languageMenu->start($bot);
        } 
        elseif (str_contains($text, msg('menu_support', $lang))) {
            $this->end();
            $supportMenu = new SupportMenu($bot);
            $supportMenu->start($bot);
        } 
        elseif (str_contains($text, msg('menu_survey', $lang))) {
            $this->end();
            $surveyMenu = new SurveyMenu($bot);
            $surveyMenu->start($bot);
        } 
        elseif (str_contains($text, msg('menu_ourProj', $lang))) {
            $this->end();
            $porjects = getProjects("active");
            $msg = "";
            $buttons = InlineKeyboardMarkup::make();
            foreach ($porjects as $porject) {
                $msg .= "<b><u>".$porject['title']."</u></b>\n".wordwrap($porject['description'], 54, "\n", true)."\n\n";
                $buttons->addRow(InlineKeyboardButton::make($porject['title'], url: $porject['link']));
            }
            $bot->sendMessage($msg, reply_markup: $buttons, parse_mode: "HTML");
        } 
        elseif (str_contains($text, msg('menu_solutions', $lang))) {
            $this->end();
            $solutions = getSolutions("active");
            foreach ($solutions as $solution) {
                $msg = "<b><u>".$solution['title']."</u></b>\n\n".$solution['description']."\n\n<b>💲 ".$solution['price']."</b>";
                $callback = 'callback_order_solution '.$solution['solutionId'];
                $button = InlineKeyboardMarkup::make()->addRow(InlineKeyboardButton::make(msg('order_btn', $lang), callback_data: $callback));
                $bot->sendMessage($msg, reply_markup: $button, parse_mode: "HTML");
                sleep(1);
            }
        } 
        else { 
            $this->end();
            createLog(TIME_NOW, 'user', $bot->userId(), 'message', $bot->message()->text);
            $msg = "You send: ".$bot->message()->text;
            $bot->sendMessage($msg);
        }
    }
}
?>
