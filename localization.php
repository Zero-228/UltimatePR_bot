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
		'menu_profile' => '👤 Profile',
		'menu_promote' => 'Promote 💸',
		'menu_support' => 'Support ℹ️',
		'add_chanel_btn' => 'Add chanel/group 🏵',
		'chanel_added' => 'Chanel added',
		'chanel_exists' => 'Chanel reconnected to bot',
		'bot_kicked' => 'Bot kicked from chanel',
		'no_chanel' => "You don't have groups to administrate. \nPls add one!",
		'select_chanel' => "Select the group which you want to \nadministrate from the list below.",
		'welcome_support_msg' => "Welcome to support page! \n\nPlease check frequent questions \nbefor asking your's.",
		'frequent_msgs' => "Frequent messages 💬",
		'contact_support' => "Contact with support 🛂",
		'back' => "↩️ Back",
		'contact_support_msg' => "Write the message which will be \nsend to support team.",
		'current_support_msg' => "\n\nYour current message:\n",
		'send_support_message' => "Send message ✅",
		'support_msg_sent' => "ℹ️ Your message was sent to support.\nThank you for your fedback!\n\n💬 Message:\n",
		'btn_statistic' => 'Statistics 📊',
		'btn_subscription' => "Forced Subscription 💹",
		'btn_messages' => "Timed messages 🗯",
		'btn_capcha' => 'Capcha ☑️',
		'btn_antispam' => 'Antispam 🚯',
		'btn_unlock' => 'Unlock 🔓',
		'btn_access' => "Access 👁",
		'subscription' => "Forced Subscription 💹\n\nThe bot will check if the user of your chat is subscribed to another channel (chat) and delete their messages until they subscribe.\n\n❗️To activate this function, the bot must be an administrator in both groups (the bot can be added through the main menu or manually).\n\n\nTo start the check, type in the group chat:\n   - /setup @group 1d*\n       * - optional parameter\n\nTo stop the check, use the command:\n   - /unset @group",

		/*
		'' => "",
		*/
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
		'menu_profile' => '👤 Профиль',
		'menu_promote' => 'Продвижение 💸',
		'menu_support' => 'Поддержка ℹ️',
		'add_chanel_btn' => 'Добавить группу/канал 🏵',
		'chanel_added' => 'Группа добавлена',
		'chanel_exists' => 'Канал переподключён к боту',
		'bot_kicked' => 'Бот исключён из группы',
		'no_chanel' => "У вас нет групп для администрирования. \nПожалуйста добавьте одну!",
		'select_chanel' => "Выберите группу которую вы хотите \nмодерировать из списка ниже.",
		'welcome_support_msg' => "Welcome to support page! \n\nPlease check frequent questions \nbefor asking your's.",
		'frequent_msgs' => "Frequent messages 💬",
		'contact_support' => "Contact with support 🛂",
		'back' => "↩️ Back",
		'contact_support_msg' => "Write the message which will be \nsend to support team.",
		'current_support_msg' => "\n\nYour current message:\n",
		'send_support_message' => "Send message ✅",
		'support_msg_sent' => "ℹ️ Your message was sent to support.\nThank you for your fedback!\n\n💬 Message:\n",
		'btn_statistic' => 'Статистика 📊',
		'btn_subscription' => "Forced Subscription 💹",
		'btn_messages' => "Timed messages 🗯",
		'btn_capcha' => 'Capcha ☑️',
		'btn_antispam' => 'Antispam 🚯',
		'btn_unlock' => 'Разблокировать 🔓',
		'btn_access' => "Access 👁",
		'subscription' => "Обязательная подписка 💹\n\nБот проверит, подписан ли пользователь вашего чата на какой-либо другой канал (чат) и будет удалять его сообщения до тех пор, пока он не подпишется.\n\n❗️Чтобы активировать эту функцию, бот должен быть администратором в обеих группах (бота можно добавить через главное меню или вручную).\n\n\nДля запуска проверки наберите в чате группы:\n   - /setup @group 1d*\n       * - необязательный параметр\n\nДля остановки проверки используйте команду:\n   - /unset @group",

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
		'menu_profile' => '👤 Профiль',
		'menu_promote' => 'Просування 💸',	
		'menu_support' => 'Пiдтримка ℹ️',
		'add_chanel_btn' => 'Додати групу/канал 🏵',
		'chanel_added' => 'Групу додано',
		'chanel_exists' => 'Група переприєднана до боту',
		'bot_kicked' => 'Бота видалено з групи',
		'no_chanel' => "У вас немає груп для адмiнiстрування. \nБудьласка додайте одну!",
		'select_chanel' => "Виберiть групу яку ви хочите \nадмiнiструвати зi списку нижче.",
		'welcome_support_msg' => "Welcome to support page! \n\nPlease check frequent questions \nbefor asking your's.",
		'frequent_msgs' => "Frequent messages 💬",
		'contact_support' => "Contact with support 🛂",
		'back' => "↩️ Back",
		'contact_support_msg' => "Write the message which will be \nsend to support team.",
		'current_support_msg' => "\n\nYour current message:\n",
		'send_support_message' => "Send message ✅",
		'support_msg_sent' => "ℹ️ Your message was sent to support.\n     Thank you for your fedback!\n\n💬 Message:\n",
		'btn_statistic' => 'Статистика 📊',
		'btn_subscription' => "Forced Subscription 💹",
		'btn_messages' => "Timed messages 🗯",
		'btn_capcha' => 'Capcha ☑️',
		'btn_antispam' => 'Antispam 🚯',
		'btn_unlock' => 'Разблокувати 🔓',	
		'btn_access' => "Access 👁",
		'subscription' => "Обов'язкова підписка 💹\n\nБот перевірить, чи підписаний користувач вашого чату на якийсь інший канал (чат), і буде видаляти його повідомлення доти, поки він не підпишеться.\n\n❗️Щоб активувати цю функцію, бот повинен бути адміністратором в обох групах (бота можна додати через головне меню або вручну).\n\n\nДля запуску перевірки наберіть у чаті групи:\n   - /setup @group 1d*\n       * - необов'язковий параметр\n\nДля зупинки перевірки використовуйте команду:\n   - /unset @group",

	),
);

function msg($message_key, $user_language) {
	global $languages;
	if (!isset($languages[$user_language])) {$user_language = 'en';}
	$res = isset($languages[$user_language][$message_key]) ? $languages[$user_language][$message_key] : "Unknown key";
	return $res;
}

?>