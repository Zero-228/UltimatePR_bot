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
require_once __DIR__ . '/vendor/autoload.php';
require_once 'config.php';
require_once 'functions.php';
require_once 'localization.php';
foreach (glob("menus/*.php") as $filename)
{
    require $filename;
}

use SergiX44\Nutgram\Nutgram;
use SergiX44\Nutgram\Configuration;
use SergiX44\Nutgram\RunningMode\Webhook;
use SergiX44\Nutgram\Conversations\InlineMenu;
use SergiX44\Nutgram\Conversations\Conversation;
use SergiX44\Nutgram\Telegram\Types\Keyboard\InlineKeyboardMarkup;
use SergiX44\Nutgram\Telegram\Types\Keyboard\InlineKeyboardButton;
use SergiX44\Nutgram\Telegram\Types\Chat\Chat;
use Symfony\Component\Cache\Adapter\FilesystemAdapter;
use Symfony\Component\Cache\Psr16Cache;

$filesystemAdapter = new FilesystemAdapter();
$cache = new Psr16Cache($filesystemAdapter);
$bot = new Nutgram(BOT_TOKEN, new Configuration(cache: $cache));
$bot->setRunningMode(Webhook::class);
$bot->setWebhook(WEBHOOK_URL);

    // $inlineKeyboard = InlineKeyboardMarkup::make()->addRow(InlineKeyboardButton::make(msg('change_language', lang($bot->userId())), null, null, 'callback_change_lang'))->addRow(InlineKeyboardButton::make(msg('cancel', lang($bot->userId())), null,null, 'callback_cancel'));

$data = file_get_contents('php://input');
writeLogFile($data, true);

$bot->onCommand('start', function(Nutgram $bot) {
    $checkUser = checkUser($bot->userId());
    if ($checkUser == 'no_such_user') {
        $user_info = get_object_vars($bot->user());
        $creating = createUser($user_info);
        if ($creating) {
            $lang = lang($bot->userId());
            $role = checkRole($bot->userId());
            $bot->sendMessage(msg('welcome', $lang), reply_markup: constructMenuButtons($lang));
            createLog(TIME_NOW, $role, $bot->userId(), 'registering', '/start');
        }
    } elseif ($checkUser == 'one_user') {
        if (checkUserStatus($bot->userId() == 'deleted')) {
            userActivatedBot($bot->userId());
        }
        $lang = lang($bot->userId());
        $role = checkRole($bot->userId());
        createLog(TIME_NOW, $role, $bot->userId(), 'command', '/start');
        $bot->sendMessage(msg('welcome_back', $lang), reply_markup: constructMenuButtons($lang));
    } else {
        $bot->sendMessage('WTF are you?');
    }
});

$bot->onMyChatMember(function(Nutgram $bot){
    $lang = lang($bot->userId());
    $role = checkRole($bot->userId());
    $myChatMember = $bot->update()->my_chat_member;
    $newStatus = $myChatMember->new_chat_member->status;
    $newStatus = json_encode($newStatus);
    error_log($newStatus);

    if ($newStatus == '"kicked"' || $newStatus == '"left"' ) {
        updateChanelStatus($bot->chatId(), 'unactive');
    } elseif($newStatus == '"administrator"' || $newStatus == '"user"') {
        $chanelStatus = checkChanel($bot->chatId());
        sleep(1);
        if ($chanelStatus == 'no_such_chanel') {
            $chanel_info = get_object_vars($bot->chat());
            createChanel($chanel_info);
            sleep(1);
            $admins = $bot->getChatAdministrators($bot->chatId());
            writeLogFile($admins);
            if ($admins) {
                foreach ($admins as $administrator) {
                    if (!$administrator->user->is_bot) {
                        sleep(1);
                        $userExistence = checkUser($administrator->user->id);
                        if ($userExistence == 'no_such_user') {
                            createUser($administrator->user);
                        }
                        $role = json_encode($administrator->status, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
                        $role = trim($role, '"');
                        addUserInChanel([
                            'userId' => $administrator->user->id,
                            'chanelId' => $bot->chatId(),
                            'role' => $role,
                        ]);
                    }
                }
            }
            $bot->sendMessage(msg('chanel_added', $lang), chat_id: $bot->userId());
            createLog(TIME_NOW, $role, $bot->userId(), 'added chanel', $bot->chatId());
        } elseif ($chanelStatus == 'one_chanel') {
            if ($bot->userId() != BOT_ID) {
                updateChanelStatus($bot->chatId(), 'active');
                $bot->sendMessage(msg('chanel_exists', $lang), chat_id: $bot->userId());
            }
        } else {
            $bot->sendMessage(text: 'Twin chanel. ID - '.$bot->chatId(), chat_id: ADMIN_ID);
        }
    } else {
        $bot->sendMessage(text: 'So why?', chat_id: ADMIN_ID);
    }
});

$bot->onCallbackQueryData('callback_change_lang', function (Nutgram $bot) {
    $role = checkRole($bot->userId());
    createLog(TIME_NOW, $role, $bot->userId(), 'callback', 'change language');
    $changeLangInlineKeyboard = InlineKeyboardMarkup::make()->addRow(InlineKeyboardButton::make(msg('language', 'en'), null, null, 'callback_change_lang_to en'))->addRow(InlineKeyboardButton::make(msg('language', 'uk'), null, null, 'callback_change_lang_to uk'))->addRow(InlineKeyboardButton::make(msg('language', 'ru'), null, null, 'callback_change_lang_to ru'));
    $bot->sendMessage(msg('choose_language', lang($bot->userId())), reply_markup: $changeLangInlineKeyboard);
    $bot->answerCallbackQuery();
});

$bot->onCallbackQueryData('callback_change_lang_to {param}', function (Nutgram $bot, $param) {
    changeLanguage($bot->userId(), $param);
    $bot->sendMessage(msg('language_changed', lang($bot->userId())));
    $bot->answerCallbackQuery();
});

$bot->onCallbackQueryData('callback_cancel', function (Nutgram $bot) {
    $role = checkRole($bot->userId());
    if (checkUserStatus($bot->userId() == 'deleted')) {
        userActivatedBot($bot->userId());
    }
    createLog(TIME_NOW, $role, $bot->userId(), 'callback', 'cancel');
    try {
        $bot->deleteMessage($bot->userId(),$bot->messageId());
    } catch (Exception $e) {
        error_log($e);
    }
    $bot->sendMessage(msg('canceled', lang($bot->userId())), reply_markup: constructMenuButtons(lang($bot->userId())));
    $bot->answerCallbackQuery();
});

$bot->onMessage(function (Nutgram $bot) {
    $role = checkRole($bot->userId());
    $text = $bot->message()->text;
    $lang = lang($bot->userId());
    createLog(TIME_NOW, $role, $bot->userId(), 'message', $text);

    if (str_contains($text, 'testMenu')) {
        $colorMenu = new ChooseColorMenu($bot);
        $colorMenu->start($bot);
    } elseif(str_contains($text, msg('menu_config', $lang))) {
        $chanelConfigMenu = new ChanelSettings($bot);
        $chanelConfigMenu->start($bot);
    } elseif(str_contains($text, msg('menu_profile', $lang))) {
        $bot->sendMessage(msg('WIP', $lang));
    } elseif(str_contains($text, msg('menu_promote', $lang))) {
        $bot->sendMessage(msg('WIP', $lang));
    } elseif(str_contains($text, msg('menu_unlock', $lang))) {
        $bot->sendMessage(msg('WIP', $lang));
    } elseif(str_contains($text, msg('menu_support', $lang))) {
        $bot->sendMessage(msg('WIP', $lang));
    } else {
        $msg = "You send: ".$bot->message()->text;
        //$bot->sendMessage($msg);
    }
});

$bot->run();

?>
