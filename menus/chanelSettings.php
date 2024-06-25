<?php 
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
            $this->menuText("You don't have groups to administrate. Pls add one")->addButtonRow(InlineKeyboardButton::make(msg('add_chanel_btn', $lang), url: $deep_link))->orNext('none')->showMenu();
        } else {
            $this->menuText(msg('select_chanel', $lang))->addButtonRow(InlineKeyboardButton::make(msg('add_chanel_btn', $lang), url: $deep_link));
            foreach ($groups as $chanel) {
                $name = $chanel['name'];
                $role = $chanel['role'];
                $id = $chanel['chanelId'];
                $callback = $id.'/'.$role.'@handleChanel';
                $this->addButtonRow(InlineKeyboardButton::make($name, callback_data: $callback));
            }
            $this->orNext('none')->showMenu();
        }
    }

    public function addChanel(Nutgram $bot)
    {
        
    }

    public function handleChanel(Nutgram $bot)
    {
        
    }

    public function none(Nutgram $bot)
    {
        $this->end();
    }
}
?>
