<?php 

/*
require 'localization.php';
*/

/*
$inlineKeyboard = InlineKeyboardMarkup::make()
        ->addRow(InlineKeyboardButton::make(msg('change_language', lang($bot->userId())), null, null, 'callback_change_lang'));
$bot->sendMessage(msg('welcome', lang($bot->userId())), reply_markup: $inlineKeyboard);
*/

/*
$bot->onCallbackQueryData('callback_change_lang', function (Nutgram $bot) {
    createLog(TIME_NOW, 'user', $bot->userId(), 'callback', 'change language');
    $changeLangInlineKeyboard = InlineKeyboardMarkup::make()->addRow(InlineKeyboardButton::make(msg('language', 'en'), null, null, 'callback_change_lang_to en'))->addRow(InlineKeyboardButton::make(msg('language', 'uk'), null, null, 'callback_change_lang_to uk'))->addRow(InlineKeyboardButton::make(msg('language', 'ru'), null, null, 'callback_change_lang_to ru'));
    $bot->sendMessage(msg('choose_language', lang($bot->userId())), reply_markup: $changeLangInlineKeyboard);
    $bot->answerCallbackQuery();
});
*/

/*
$bot->onCallbackQueryData('callback_change_lang_to {param}', function (Nutgram $bot, $param) {
    changeLanguage($bot->userId(), $param);
    $bot->sendMessage(msg('language_changed', lang($bot->userId())));
    $bot->answerCallbackQuery();
});
*/

$languages = array(
	'en' => array(
		'WIP' => "Development of this feature still in \nprogress.Thank you for your patience. 🧑‍💻",
		'welcome' => 'Greetengs',
		'welcome_back' => 'Welcome back to main menu',
		'change_language' => '🌐 Change language',
		'choose_language' => 'Choose language',
		'language_changed' => 'Language changed',
		'language' => '🇬🇧 English',
		'cancel' => 'Cancel ❌',
		'canceled' => '❌ Action canceled ',
		'menu_config' => 'Settings of group/chanel ⚙️',
		'menu_checkSub' => '🔺 Subscribes',
		'menu_promote' => 'Promote 💸',
		'menu_unlock' => '🔓 Unlock',
		'menu_info' => 'Info 📢',
		'btn_statistic' => 'Statistics 📊',
		'add_chanel_btn' => 'Add chanel/group 🏵',
		'chanel_added' => 'Chanel added',
		'chanel_exists' => 'Chanel reconnected to bot',
		'bot_kicked' => 'Bot kicked from chanel',
	),
	'ru' => array(
		'WIP' => "Данная функция находится в процессе \nразработки.Спасибо за ваше терпение. 🧑‍💻",
		'welcome' => 'Добро пожаловать',
		'welcome_back' => 'С возвращением в главное меню',
		'change_language' => '🌐 Сменить язык',
		'choose_language' => 'Выберите язык',
		'language_changed' => 'Язык изменён',
		'language' => '🇷🇺 Русский',
		'cancel' => 'Отменить ❌',
		'canceled' => '❌ Действие отменено ',
		'menu_config' => 'Настройки группы/канала ⚙️',
		'menu_checkSub' => '🔺 Подписки',
		'menu_promote' => 'Продвижение 💸',
		'menu_unlock' => '🔓 Разблокировать',
		'menu_info' => 'Инфо 📢',
		'btn_statistic' => 'Статистика 📊',
		'add_chanel_btn' => 'Добавить группу/канал 🏵',
		'chanel_added' => 'Chanel added',
		'chanel_exists' => 'Chanel reconnected to bot',
		'bot_kicked' => 'Bot kicked from chanel',
	),
	'uk' => array(
		'WIP' => "Ця функція знаходиться в процессi \nрозробки.Дякую за ваше терпіння. 🧑‍💻",
		'welcome' => 'Ласкаво просимо',
		'welcome_back' => 'З поверненням до головного меню',
		'change_language' => '🌐 Змiнити мову',
		'choose_language' => 'Оберiть мову',
		'language_changed' => 'Мову змiнено',
		'language' => '🇺🇦 Українська',
		'cancel' => 'Вiдмiнити ❌',
		'canceled' => '❌ Подiя вiдмiнена ',
		'menu_config' => 'Налаштування групи/каналу ⚙️',
		'menu_checkSub' => '🔺 Пiдписки',
		'menu_promote' => 'Просування 💸',
		'menu_unlock' => '🔓 Разблокувати',
		'menu_info' => 'Iнфо 📢',
		'btn_statistic' => 'Статистика 📊',
		'add_chanel_btn' => 'Додати групу/канал 🏵',
		'chanel_added' => 'Chanel added',
		'chanel_exists' => 'Chanel reconnected to bot',
		'bot_kicked' => 'Bot kicked from chanel',
	),
);

function msg($message_key, $user_language) {
	global $languages;
	$res = isset($languages[$user_language][$message_key]) ? $languages[$user_language][$message_key] : "Unknown key";
	return $res;
}

?>