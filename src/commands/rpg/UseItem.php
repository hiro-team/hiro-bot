<?php

namespace hiro\commands;

use hiro\consts\RPG;
use hiro\database\Database;

class UseItem extends Command {

    /**
     * configure
     *
     * @return void
     */
    public function configure(): void
    {
        $this->command = "useitem";
        $this->description = "Uses an item from your inventory.";
        $this->aliases = ["u", "use"];
        $this->category = "rpg";
    }

    /**
     * handle
     *
     * @param [type] $msg
     * @param [type] $args
     * @return void
     */
    public function handle($msg, $args): void
    {
        $database = new Database();
        if (!$database->isConnected) {
            $msg->channel->sendMessage("Couldn't connect to database.");
            return;
        }

        $slot = $args[0] ?? null;
        if(!$slot || !is_numeric($slot) || ($slot < 0 && $slot >= RPG::MAX_ITEM_SLOT)) {
            $msg->reply('You should give a valid slot number!');
            return;
        }

        $item = $database->getRPGUserItemBySlot($msg->author->id, $slot-1);
        if(!$item) {
            $msg->reply('Item not found.');
            return;
        }

        if ( $database->getRPGUsingItemByType($msg->author->id, $item['item_type'] ) ) {
            $msg->reply('You are already using an item for this type!');
            return;
        }

        if(!$database->useRPGUserItem($msg->author->id, $slot-1))
        {
            $msg->reply('Couldn\'t use item.');
            return;
        }
    }

}