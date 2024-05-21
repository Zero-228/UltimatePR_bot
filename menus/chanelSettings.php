<?php 
require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../localization.php';
require_once __DIR__ . '/../functions.php';

use SergiX44\Nutgram\Nutgram;
use SergiX44\Nutgram\Conversations\InlineMenu;
use SergiX44\Nutgram\Telegram\Types\Keyboard\InlineKeyboardButton;
use SergiX44\Nutgram\Telegram\Types\Keyboard\InlineKeyboardMarkup;

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
        if ($groups == 'chanel_not_found') {
            $this->next('addChanel');
        } else {
            $this->menuText(msg('select_chanel', $lang))->addButtonRow(InlineKeyboardButton::make(msg('add_chanel_btn', $lang), callback_data: '@addChanel'));
            foreach ($groups as $chanel) {
                $name = $chanel['name'];
                $role = $chanel['role'];
                $id = $chanel['chanelId'];
                $callback = $id.'/'.$role.'@handleGroup';
                $this->addButtonRow(InlineKeyboardButton::make($name, callback_data: $callback));
            }
            $this->orNext('none')->showMenu();
        }
    }

    public function addChanel(Nutgram $bot)
    {
        $this->menuText("You don't have groups to administrate. Pls add one")->showMenu();
    }

    public function none(Nutgram $bot)
    {
        $bot->sendMessage('Bye!');
        $this->end();
    }
}
?>
