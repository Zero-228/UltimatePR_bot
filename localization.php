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
		'btn_botfilter' => "Bot filter 🤖",
		'btn_link' => "Link 🔗",
		'btn_flood' => "Flood 😷",
		'btn_filters' => 'Filters 🧮',
		'btn_unlock' => 'Unlock 🔓',
		'btn_access' => "Access 👁",
		'subscription' => "Forced Subscription 💹\n\nThe bot will check if the user of your chat is subscribed to another channel (chat) and delete their messages until they subscribe.\n\n❗️To activate this function, the bot must be an administrator in both groups (the bot can be added through the main menu or manually).\n\n\nTo start the check, type in the group chat:\n   - /setup @group 1d*\n       * - optional parameter\n\nTo stop the check, use the command:\n   - /unset @group",
		'timed_messages' => "💬 Here you can add some auto messages\n\n",
		'make_timed_msg' => "Create new message ➕",
		'enter_timed_msg' => "🗯 Please enter the message below:",
		'your_timed_msg' => "📃   Your timed message   📃\n\n",
		'confirm' => "Confirm ✅",
		'timed_msg_timer' => "🔁   Repeat every: ",
		'timed_msg_status' => "🔰   Status: ",
		'save_timed_msg' => "Save ✅",
		'delete_timed_msg' => "Delete 🗑",
		'on_off' => "ON / OFF",
		'unsaved_status' => "After saving the message your status \nwill be automatically set to \"ON\"",
		'status_changed' => "Status changed!",
		'message_changed' => "Message changed!",
		'timer_changed' => "Timer changed!",
		'set_status' => "Change your status.\n\n Your current status: ",
		'set_timer' => "Change your timer.\n\n Your current timer: ",
		'msg_on' => "Enable timed message 📬",
		'msg_off' => "Disable timed message 📭",
		'shure_delete_msg' => "🗑 Are your shure you want to \ndelete this timed message?",
		'msg_saved' => "Message succesfully saved ✅",
		'msg_deleted' => "Your timed message was deleted succesfully.",
		'delete_msg' => "Are you shure you want to \ndelete this timed message?",
		'set_chanel_access' => "⚙️ Here you can modify accessto \nchanel's settings via this bot. ⚙️\n\nCurrent setting: ",
		'creator' => "Creator",
		'admin' => "Admin",
		'chanel_settings' => "🤷🏻‍♂️ Users: {users}   (➕{users_new} / {users_left}➖)\n🔓 Access: {access}\n☑️ Capcha: {capcha}\n🧮 Filters: {filters}\n\n__________________________\nLast update: \n{last_update}",
		'stng_on' => "on 🟢",
		'stng_off' => "off 🔴",
		'capcha_msg' => "@{username}, press the button below\nto pass the caphca ❗️",
		'capcha_btn' => "I'm not a bot!",
		'set_chanel_capcha' => "☑️ Simple anti-bot verification. ☑️\nUser must press the button in \na 3 minutes or will be muted for a \nhalf an hour \n\nCurrent setting: ",
		'set_chanel_antispam' => "🚯 Antispam filter 🚯\nMutes user after second attempt \nof sending 3 messages within a \n2 seconds\n\nCurrent setting: ",
		'user' => "User",
		'setting_updated' => " updated. \n\nCurrently set to: ",
		'spam_warn' => "❗️@{username}, please write slower \nor you'll be muted",
		'set_chanel_antiflood' => "😷 Antiflood filter 😷\nMutes user after sending same message 3 times\n\nCurrent setting: ",
		'set_chanel_antilink' => "🔗 Antilink filter 🔗\nMutes user after trying to send a link\n\nCurrent setting: ",
		'set_chanel_antibot' => "🤖 Antibot filter 🤖\nMutes user after tryign to add a bot in a group\n\nCurrent setting: ",
		'filter_menu' => "Here you can adjust some \nadditional filters for your \ngroup.\n======================\n\n 🚯   Antispam: {antispam}\n 😷   Antiflood:  {antiflood}\n 🔗   Antilink:     {antilink}\n 🤖   Antibot:     {antibot}\n\n======================",
		'flood_msg' => "❗️@{username}, don't flood please❗️",
		'link_msg' => "❗️@{username}, very likely the message you sent contains a link. Please don't send them. ❗️",
		'set_chanel_unlocked' => "🔓 Here you can unlock additional 🔓\nfeatures for your group.\n\n<b>Basic</b>\nSimplest version that allows you to use \nautosubscription and 1 timed message\n\n<b>VIP</b>\nAllowing infrequent advertisingin your\ngroup you gane access to different filter\nsettings and capcha validation feature.\nTimed messages limit increases to 3\n\n",
		'unlock_basic' => "Basic ",
		'unlock_vip' => "VIP ",
		'unlock_prem' => "Premium ",
		'set_chanel_unlock' => "Channel level upgraded!\n\nThank you for your collaboration!",
		'no_chanel_found' => "❗️Bot isn't added to the group you needed. Correct that and then we'll talk",
		'no_permissions' => "❗️You don't have permissions to do that",
		'subscription_added' => "✅ Autosubscription to {chanelUsername} sucessfully added",




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
		'welcome_support_msg' => "Добро пожаловать на страницу поддержки! \n\nПожалуйста проверь частые вопросы \nперед тем как писать свой.",
		'frequent_msgs' => "Частые вопросы 💬",
		'contact_support' => "Связаться с поддержкой 🛂",
		'back' => "↩️ Назад",
		'contact_support_msg' => "Напиши сообщение которое будет \nотправленно команде поддержки.",
		'current_support_msg' => "\n\nТвоё текущее сообщение:\n",
		'send_support_message' => "Отправить сообщение ✅",
		'support_msg_sent' => "ℹ️ Твоё сообщение было отправленно в поддержку.\nСпасибо за твой отзыв!\n\n💬 Сообщение:\n",
		'btn_statistic' => 'Статистика 📊',
		'btn_subscription' => "Обязательная подписка 💹",
		'btn_messages' => "Заплнированные сообщения 🗯",
		'btn_capcha' => 'Капча ☑️',
		'btn_antispam' => 'Антиспам 🚯',
		'btn_botfilter' => "Bot filter 🤖",
		'btn_link' => "Link 🔗",
		'btn_flood' => "Flood 😷",
		'btn_filters' => 'Фильтры 🧮',
		'btn_unlock' => 'Разблокировать 🔓',
		'btn_access' => "Доступ 👁",
		'subscription' => "Обязательная подписка 💹\n\nБот проверит, подписан ли пользователь вашего чата на какой-либо другой канал (чат) и будет удалять его сообщения до тех пор, пока он не подпишется.\n\n❗️Чтобы активировать эту функцию, бот должен быть администратором в обеих группах (бота можно добавить через главное меню или вручную).\n\n\nДля запуска проверки наберите в чате группы:\n   - /setup @group 1d*\n       * - необязательный параметр\n\nДля остановки проверки используйте команду:\n   - /unset @group",
		'timed_messages' => "💬 Here you can add some auto messages\n\n",
		'make_timed_msg' => "Create new message ➕",
		'enter_timed_msg' => "🗯 Please enter the message below:",
		'your_timed_msg' => "📃   Your timed message   📃\n\n",
		'confirm' => "Confirm ✅",
		'timed_msg_timer' => "🔁   Repeat every: ",
		'timed_msg_status' => "🔰   Status: ",
		'save_timed_msg' => "Save ✅",
		'delete_timed_msg' => "Delete 🗑",
		'on_off' => "ON / OFF",
		'status_changed' => "Status changed!",
		'message_changed' => "Message changed!",
		'timer_changed' => "Timer changed!",
		'set_status' => "Change your status.\n\n Your current status: ",
		'set_timer' => "Change your timer.\n\n Your current timer: ",
		'msg_on' => "Enable timed message 📬",
		'msg_off' => "Disable timed message 📭",
		'shure_delete_msg' => "🗑 Are your shure you want to \ndelete this timed message?",
		'msg_saved' => "Message succesfully saved ✅",
		'msg_deleted' => "Your timed message was deleted succesfully.",
		'delete_msg' => "Are you shure you want to \ndelete this timed message?",
		'set_chanel_access' => "⚙️ Here you can modify accessto \nchanel's settings via this bot. ⚙️\n\nCurrent setting: ",
		'creator' => "Creator",
		'admin' => "Admin",
		'chanel_settings' => "🤷🏻‍♂️ Users: {users}   (➕{users_new} / {users_left}➖)\n🔓 Access: {access}\n☑️ Capcha: {capcha}\n🧮 Filters: {filters}\n\n__________________________\nLast update: \n{last_update}",
		'stng_on' => "on 🟢",
		'stng_off' => "off 🔴",
		'capcha_msg' => "@{username}, press the button below\nto pass the caphca ❗️",
		'capcha_btn' => "I'm not a bot!",
		'set_chanel_capcha' => "☑️ Simple anti-bot verification. ☑️\nUser must press the button in \na 3 minutes or will be muted for a \nhalf an hour \n\nCurrent setting: ",
		'set_chanel_antispam' => "🚯 Antispam filter 🚯\nMutes user after second attempt \nof sending 3 messages within a \n2 seconds\n\nCurrent setting: ",
		'user' => "User",
		'setting_updated' => " updated. \n\nCurrently set to: ",
		'spam_warn' => "❗️@{username}, please write slower \nor you'll be muted",
		'set_chanel_antiflood' => "😷 Antiflood filter 😷\nMutes user after sending same message 3 times\n\nCurrent setting: ",
		'set_chanel_antilink' => "🔗 Antilink filter 🔗\nMutes user after trying to send a link\n\nCurrent setting: ",
		'set_chanel_antibot' => "🤖 Antibot filter 🤖\nMutes user after tryign to add a bot in a group\n\nCurrent setting: ",
		'filter_menu' => "Here you can adjust some \nadditional filters for your \ngroup.\n======================\n\n 🚯   Antispam: {antispam}\n 😷   Antiflood:  {antiflood}\n 🔗   Antilink:     {antilink}\n 🤖   Antibot:     {antibot}\n\n======================",
		'flood_msg' => "❗️@{username}, don't flood please❗️",
		'link_msg' => "❗️@{username}, very likely the message you sent contains a link. Please don't send them. ❗️",
		'set_chanel_unlocked' => "🔓 Here you can unlock additional 🔓\nfeatures for your group.\n\n<b>Basic</b>\nSimplest version that allows you to use \nautosubscription and 1 timed message\n\n<b>VIP</b>\nAllowing infrequent advertisingin your\ngroup you gane access to different filter\nsettings and capcha validation feature.\nTimed messages limit increases to 3\n\n",
		'unlock_basic' => "Basic ",
		'unlock_vip' => "VIP ",
		'unlock_prem' => "Premium ",
		'set_chanel_unlock' => "Channel level upgraded!\n\nThank you for your collaboration!",
		'no_chanel_found' => "❗️Bot isn't added to the group you needed. Correct that and then we'll talk",
		'no_permissions' => "❗️You don't have permissions to do that",
		'subscription_added' => "✅ Autosubscription to {chanelUsername} sucessfully added",
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
		'canceled' => '❌ Дiя вiдмiнена ',
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
		'welcome_support_msg' => "Ласкаво просимо до сторiнки пiдтримки \n\nБудьласка перевірь частi запитання \nперед тим як писати своє.",
		'frequent_msgs' => "Частi запитання 💬",
		'contact_support' => "Зв'язатися з пiдтримкою 🛂",
		'back' => "↩️ Назад",
		'contact_support_msg' => "Напиши повiдомлення яке буде\nвiдправлено до команди пiдтримки.",
		'current_support_msg' => "\n\nТвоє поточне повiдомлення:\n",
		'send_support_message' => "Вiдправити повiдомлення ✅",
		'support_msg_sent' => "ℹ️ Твоє повiдомлення було вiдправлено до команди пiдтримки.\n     Дякуємо за твiй вдгук!\n\n💬 Повiдомлення:\n",
		'btn_statistic' => 'Статистика 📊',
		'btn_subscription' => "Обов'язкова пiдписка 💹",
		'btn_messages' => "Запланованi повiдомлення 🗯",
		'btn_capcha' => 'Капча ☑️',
		'btn_antispam' => 'Антiспам 🚯',
		'btn_botfilter' => "Bot filter 🤖",
		'btn_link' => "Link 🔗",
		'btn_flood' => "Flood 😷",
		'btn_filters' => 'Фiльтри 🧮',
		'btn_unlock' => 'Разблокувати 🔓',	
		'btn_access' => "Доступ 👁",
		'subscription' => "Обов'язкова підписка 💹\n\nБот перевірить, чи підписаний користувач вашого чату на якийсь інший канал (чат), і буде видаляти його повідомлення доти, поки він не підпишеться.\n\n❗️Щоб активувати цю функцію, бот повинен бути адміністратором в обох групах (бота можна додати через головне меню або вручну).\n\n\nДля запуску перевірки наберіть у чаті групи:\n   - /setup @group 1d*\n       * - необов'язковий параметр\n\nДля зупинки перевірки використовуйте команду:\n   - /unset @group",
		'timed_messages' => "💬 Here you can add some auto messages\n\n",
		'make_timed_msg' => "Create new message ➕",
		'enter_timed_msg' => "🗯 Please enter the message below:",
		'your_timed_msg' => "📃   Your timed message   📃\n\n",
		'confirm' => "Confirm ✅",
		'timed_msg_timer' => "🔁   Repeat every: ",
		'timed_msg_status' => "🔰   Status: ",
		'save_timed_msg' => "Save ✅",
		'delete_timed_msg' => "Delete 🗑",
		'on_off' => "ON / OFF",
		'status_changed' => "Status changed!",
		'message_changed' => "Message changed!",
		'timer_changed' => "Timer changed!",
		'set_status' => "Change your status.\n\n Your current status: ",
		'set_timer' => "Change your timer.\n\n Your current timer: ",
		'msg_on' => "Enable timed message 📬",
		'msg_off' => "Disable timed message 📭",
		'shure_delete_msg' => "🗑 Are your shure you want to \ndelete this timed message?",
		'msg_saved' => "Message succesfully saved ✅",
		'msg_deleted' => "Your timed message was deleted succesfully.",
		'delete_msg' => "Are you shure you want to \ndelete this timed message?",
		'set_chanel_access' => "⚙️ Here you can modify access to \nchanel's settings via this bot. ⚙️\n\nCurrent setting: ",
		'creator' => "Creator",
		'admin' => "Admin",
		'chanel_settings' => "🤷🏻‍♂️ Users: {users}   (➕{users_new} / {users_left}➖)\n🔓 Access: {access}\n☑️ Capcha: {capcha}\n🧮 Filters: {filters}\n\n__________________________\nLast update: \n{last_update}",
		'stng_on' => "on 🟢",
		'stng_off' => "off 🔴",
		'capcha_msg' => "@{username}, press the button below\nto pass the caphca ❗️",
		'capcha_btn' => "I'm not a bot!",
		'set_chanel_capcha' => "☑️ Simple anti-bot verification. ☑️\nUser must press the button in \na 3 minutes or will be muted for a \nhalf an hour \n\nCurrent setting: ",
		'set_chanel_antispam' => "🚯 Antispam filter 🚯\nMutes user after second attempt \nof sending 3 messages within a \n2 seconds\n\nCurrent setting: ",
		'user' => "User",
		'setting_updated' => " updated. \n\nCurrently set to: ",
		'spam_warn' => "❗️@{username}, please write slower \nor you'll be muted",
		'set_chanel_antiflood' => "😷 Antiflood filter 😷\nMutes user after sending same message 3 times\n\nCurrent setting: ",
		'set_chanel_antilink' => "🔗 Antilink filter 🔗\nMutes user after trying to send a link\n\nCurrent setting: ",
		'set_chanel_antibot' => "🤖 Antibot filter 🤖\nMutes user after tryign to add a bot in a group\n\nCurrent setting: ",
		'filter_menu' => "Here you can adjust some \nadditional filters for your \ngroup.\n======================\n\n 🚯   Antispam: {antispam}\n 😷   Antiflood:  {antiflood}\n 🔗   Antilink:     {antilink}\n 🤖   Antibot:     {antibot}\n\n======================",
		'flood_msg' => "❗️@{username}, don't flood please❗️",
		'link_msg' => "❗️@{username}, very likely the message you sent contains a link. Please don't send them. ❗️",
		'set_chanel_unlocked' => "🔓 Here you can unlock additional 🔓\nfeatures for your group.\n\n<b>Basic</b>\nSimplest version that allows you to use \nautosubscription and 1 timed message\n\n<b>VIP</b>\nAllowing infrequent advertisingin your\ngroup you gane access to different filter\nsettings and capcha validation feature.\nTimed messages limit increases to 3\n\n",
		'unlock_basic' => "Basic ",
		'unlock_vip' => "VIP ",
		'unlock_prem' => "Premium ",
		'set_chanel_unlock' => "Channel level upgraded!\n\nThank you for your collaboration!",
		'no_chanel_found' => "❗️Bot isn't added to the group you needed. Correct that and then we'll talk",
		'no_permissions' => "❗️You don't have permissions to do that",
		'subscription_added' => "✅ Autosubscription to {chanelUsername} sucessfully added",
	),
);

function msg($message_key, $user_language, $variables = []) {
    global $languages;
    
    // 'en' - standart language
    if (!isset($languages[$user_language])) {$user_language = 'en';}

    $message = isset($languages[$user_language][$message_key]) ? $languages[$user_language][$message_key] : "Unknown key";

    // Replacing variables
    if (!empty($variables)) {$message = strtr($message, $variables);}

    return $message;
}

?>