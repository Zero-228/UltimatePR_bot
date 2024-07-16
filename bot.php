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
global $filesystemAdapter;
$bot = new Nutgram(BOT_TOKEN, new Configuration(cache: $cache));
$bot->setRunningMode(Webhook::class);
$bot->setWebhook(WEBHOOK_URL);

$data = file_get_contents('php://input');
writeLogFile($data, true);

$bot->onCommand('start', function(Nutgram $bot) {
    $checkUser = checkUser($bot->userId());
    if ($checkUser == 'no_such_user') {
        $user_info = get_object_vars($bot->user());
        $creating = createUser($user_info, true);
        if ($creating) {
            $lang = lang($bot->userId());
            $role = checkRole($bot->userId());
            $bot->sendMessage(msg('welcome', $lang), reply_markup: constructMenuButtons($lang));
            createLog(TIME_NOW, $role, $bot->userId(), 'registering', '/start');
        }
    } elseif ($checkUser == 'one_user') {
        if (checkUserStatus($bot->userId()) == 'deleted') {
            userActivatedBot($bot->userId());
        }
        if (checkUserInBot($bot->userId()) == false) {
            userStartedBot($bot->userId());
        }
        $lang = lang($bot->userId());
        $role = checkRole($bot->userId());
        createLog(TIME_NOW, $role, $bot->userId(), 'command', '/start');
        $bot->sendMessage(msg('welcome', $lang), reply_markup: constructMenuButtons($lang));
    } else {
        $bot->sendMessage('WTF are you?');
    }
});

$bot->onMyChatMember(function(Nutgram $bot){
    $lang = lang($bot->userId());
    $role = checkRole($bot->userId());
    $myChatMember = $bot->update()->my_chat_member;
    $isBot = $bot->message()->is_bot;
    $newStatus = $myChatMember->new_chat_member->status;
    $newStatus = json_encode($newStatus);
    error_log($newStatus);

    if (!$isBot) {
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
                            $userExistence = checkUser($administrator->user->id);
                            if ($userExistence == 'no_such_user') {
                                createUser($administrator->user);
                            }
                            $role = json_encode($administrator->status, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
                            $role = trim($role, '"');

                            $checkUserInChanel = checkUserInChanel($bot->userId(), $bot->chatId());
                            if ($checkUserInChanel == 'user_not_added' && $chatId != BOT_ID) {
                                addUserInChanel([
                                    'userId' => $administrator->user->id,
                                    'chanelId' => $bot->chatId(),
                                    'role' => $role,
                                ]);
                            } else {
                                updateUserRoleInChanel($bot->userId(), $bot->chatId(), $role);
                            }                                                
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
    }
});

$bot->onCallbackQueryData('callback_change_lang_to {param}', function (Nutgram $bot, $param) {
    changeLanguage($bot->userId(), $param);
    try {
        $bot->deleteMessage($bot->userId(), $bot->messageId());
    } catch (Exception $e) {
        error_log($e);
    }
    $bot->sendMessage(msg('language_changed', lang($bot->userId())), reply_markup: constructMenuButtons(lang($bot->userId())));
    $bot->answerCallbackQuery();
});

$bot->onCallbackQueryData('passCapcha', function (Nutgram $bot) {
    $chanelId = $bot->chat()->id;
    $capcha = checkCapcha($bot->userId(), $chanelId);
    if ($capcha['status'] == 'pending') {
        updateCapcha($bot->userId(), $chanelId, 'approved');
    }
    $log = getCapchaLog($bot->userId(), $chanelId);
    foreach ($log as $cpcha) {
        try {
            $bot->deleteMessage($chanelId, $cpcha['messageId']);
        } catch (Exception $e) {
            error_log($e);
        }
        superUpdater('chanel_log', 'status', 'deleted', 'messageId', $cpcha['messageId']);
    }
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
    $isBot = $bot->message()->is_bot;
    $chatId = $bot->chat()->id;
    $lang = lang($bot->userId());

    if (str_contains($text, 'testMenu')) {
        $colorMenu = new ChooseColorMenu($bot);
        $colorMenu->start($bot);
    } elseif(str_contains($text, msg('menu_config', $lang))) {
        $chanelConfigMenu = new ChanelSettings($bot);
        $chanelConfigMenu->start($bot);
    } elseif(str_contains($text, msg('menu_profile', $lang))) {
        $profileMenu = new ProfileMenu($bot);
        $profileMenu->start($bot);
    } elseif(str_contains($text, msg('menu_promote', $lang))) {
        $bot->sendMessage(msg('WIP', $lang));
    } elseif(str_contains($text, msg('change_language', $lang))) {
        $changeLangInlineKeyboard = InlineKeyboardMarkup::make()->addRow(InlineKeyboardButton::make(msg('language', 'en'), null, null, 'callback_change_lang_to en'))->addRow(InlineKeyboardButton::make(msg('language', 'uk'), null, null, 'callback_change_lang_to uk'))->addRow(InlineKeyboardButton::make(msg('language', 'ru'), null, null, 'callback_change_lang_to ru'));
        $bot->sendMessage(msg('choose_language', lang($bot->userId())), reply_markup: $changeLangInlineKeyboard);
    } elseif(str_contains($text, msg('menu_support', $lang))) {
        $supportMenu = new SupportMenu($bot);
        $supportMenu->start($bot);
    } else {
        // Check if the message sender is a bot
        if (!$isBot) {
            // Check if the user is already in the system
            $checkUser = checkUser($bot->userId());
            if ($checkUser == 'no_such_user') {
                // Register the user if not found in the system
                $user_info = get_object_vars($bot->user());
                if (createUser($user_info)) {
                    createLog(TIME_NOW, $role, $bot->userId(), 'registering', '/start');
                }
            }

            // Log messages if not from bot itself and not a direct message
            if ($bot->chatId() != BOT_ID && $bot->chatId() != $bot->userId()) {
                if (checkChanel($bot->chatId())) {
                    // Check if the user is added to the channel
                    if (checkUserInChanel($bot->userId(), $chatId) == 'user_not_added' && $chatId != BOT_ID) {
                        addUserInChanel([
                            'userId' => $bot->userId(),
                            'chanelId' => $chatId,
                            'role' => 'user',
                        ]);
                    }
                }
                $chanelId = $bot->chatId();
                $settings = getChanelSettings($chanelId);
                if ($settings['capcha'] == 'on') {
                    $checkCapcha = checkCapcha($bot->userId(), $chanelId);
                    if (!$checkCapcha) {
                        try {
                            $bot->deleteMessage($chanelId,$bot->messageId());
                        } catch (Exception $e) {
                            error_log($e);
                        }
                        $capchaKey = InlineKeyboardMarkup::make()->addRow(InlineKeyboardButton::make(msg('capcha_btn', $lang), callback_data: 'passCapcha'));
                        $username = getUsername($bot->userId());
                        $bot->sendMessage(chat_id: $chanelId,text: msg('capcha_msg', $lang, ['{username}'=>$username]), reply_markup: $capchaKey);
                        createChanelLog(TIME_NOW, 'bot', ADMIN_ID, $chanelId, 'capcha', $bot->userId(), $bot->messageId()+1);
                        createCapcha($bot->userId(), $chanelId);
                    }else {
                        if ($checkCapcha['status'] == 'pending') {
                            if ((time()-strtotime("3 MINUTE"))>$checkCapcha['created_at']) {
                                $bot->restrictChatMember($chanelId, $bot->userId(), [
                                    'permissions' => [
                                        'can_send_messages' => false,
                                        'can_send_media_messages' => false,
                                        'can_send_polls' => false,
                                        'can_send_other_messages' => false,
                                        'can_add_web_page_previews' => false,
                                        'can_change_info' => false,
                                        'can_invite_users' => false,
                                        'can_pin_messages' => false,
                                    ],
                                    'until_date' => time() + 30 * 60 // Текущий UNIX-времени + 10 минут
                                ]);
                                updateCapcha($bot->userId(), $chanelId, 'failed');
                            } else {
                                try {
                                    $bot->deleteMessage($chanelId,$bot->messageId());
                                } catch (Exception $e) {
                                    error_log($e);
                                }
                                $capchaKey = InlineKeyboardMarkup::make()->addRow(InlineKeyboardButton::make(msg('capcha_btn', $lang), callback_data: 'passCapcha'));
                                $username = getUsername($bot->userId());
                                $bot->sendMessage(chat_id: $chanelId,text: msg('capcha_msg', $lang, ['{username}'=>$username]), reply_markup: $capchaKey);
                                createChanelLog(TIME_NOW, 'bot', ADMIN_ID, $chanelId, 'capcha', $bot->userId(), $bot->messageId()+1);
                            }
                        }
                        if ($checkCapcha['status'] == 'failed') {
                            if ((time()-strtotime("10 MINUTE"))>$checkCapcha['updated_at']) {
                                try {
                                    $bot->deleteMessage($chanelId,$bot->messageId());
                                } catch (Exception $e) {
                                    error_log($e);
                                }
                                $capchaKey = InlineKeyboardMarkup::make()->addRow(InlineKeyboardButton::make(msg('capcha_btn', $lang), callback_data: 'passCapcha'));
                                $username = getUsername($bot->userId());
                                $bot->sendMessage(chat_id: $chanelId,text: msg('capcha_msg', $lang, ['{username}'=>$username]), reply_markup: $capchaKey);
                                createChanelLog(TIME_NOW, 'bot', ADMIN_ID, $chanelId, 'capcha', $bot->userId(), $bot->messageId()+1);
                                updateCapcha($bot->userId(), $chanelId, 'pending');
                            }
                        }
                        if ($checkCapcha['status'] == 'approved') {
                            createChanelLog(TIME_NOW, 'user', $bot->userId(), $chanelId, 'message', $bot->message()->text, $bot->messageId());
                        }
                    }
                } else {
                    createChanelLog(TIME_NOW, 'user', $bot->userId(), $chanelId, 'message', $bot->message()->text, $bot->messageId());
                }
                if ($settings['antispam'] == 'on') {
                    $logs = checkAntispam($bot->userId(), $chanelId, 3); // Получить последние 3 сообщения за последние 3 секунды
                    
                    if (count($logs) >= 3) {
                        $firstMessageTime = strtotime($logs[0]['created_at']);
                        $lastMessageTime = strtotime($logs[2]['created_at']);
                        $timeDiff = $firstMessageTime - $lastMessageTime;

                        if ($timeDiff <= 3) {
                                sleep(1);
                            $previousWarnings = checkPreviousWarnings($bot->userId(), $chanelId, 3600); // Проверка на наличие предупреждений за последний час
                            
                            if ($previousWarnings < 1) {
                                // Удалить сообщения
                                foreach ($logs as $log) {
                                    $bot->deleteMessage($chanelId, $log['messageId']);
                                }

                                // Отправить предупреждение
                                $username = getUsername($bot->userId());
                                $bot->sendMessage(chat_id: $chanelId, text: msg("spam_warn", $lang, ['{username}'=>$username]));

                                // Логировать предупреждение
                                createChanelLog(TIME_NOW, 'bot', ADMIN_ID, $chanelId, 'spam_warn', "Предупреждение за спам", $bot->messageId() + 1);
                            } else {
                                // Мут пользователя
                                $bot->restrictChatMember($chanelId, $bot->userId(), [
                                    'permissions' => [
                                        'can_send_messages' => false,
                                        'can_send_media_messages' => false,
                                        'can_send_polls' => false,
                                        'can_send_other_messages' => false,
                                        'can_add_web_page_previews' => false,
                                        'can_change_info' => false,
                                        'can_invite_users' => false,
                                        'can_pin_messages' => false,
                                    ],
                                    'until_date' => time() + 10 * 60 // Мут на 10 минут
                                ]);

                                // Логировать мут
                                createChanelLog(TIME_NOW, 'bot', ADMIN_ID, $chanelId, 'mute', "Мут за повторный спам", $bot->messageId() + 1);
                            }
                        }
                    }
                }
            }
        }
    }
});

$bot->run();

?>
