<?php

/**
 * Copyright 2023 bariscodefx
 *
 * This file part of project Hiro 016 Discord Bot.
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *     http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */

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