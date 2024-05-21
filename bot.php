<?php 

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
use Symfony\Component\Cache\Adapter\FilesystemAdapter;
use Symfony\Component\Cache\Psr16Cache;

$filesystemAdapter = new FilesystemAdapter();
$cache = new Psr16Cache($filesystemAdapter);
$bot = new Nutgram(BOT_TOKEN, new Configuration(cache: $cache));
$bot->setRunningMode(Webhook::class);
$bot->setWebhook(WEBHOOK_URL);

    // $inlineKeyboard = InlineKeyboardMarkup::make()->addRow(InlineKeyboardButton::make(msg('change_language', lang($bot->userId())), null, null, 'callback_change_lang'))->addRow(InlineKeyboardButton::make(msg('cancel', lang($bot->userId())), null,null, 'callback_cancel'));

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
    } elseif(str_contains($text, msg('menu_checkSub', $lang))) {
        $bot->sendMessage(msg('WIP', $lang));
    } elseif(str_contains($text, msg('menu_promote', $lang))) {
        $bot->sendMessage(msg('WIP', $lang));
    } elseif(str_contains($text, msg('menu_unlock', $lang))) {
        $bot->sendMessage(msg('WIP', $lang));
    } elseif(str_contains($text, msg('menu_info', $lang))) {
        $bot->sendMessage(msg('WIP', $lang));
    } else {
        $msg = "You send: ".$bot->message()->text;
        $bot->sendMessage($msg);
    }
});

$bot->run();

?>
