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

class ReleaseItem extends Command
{
    /**
     * configure
     *
     * @return void
     */
    public function configure(): void
    {
        $this->command = "releaseitem";
        $this->description = "Releases an item from your uses.";
        $this->aliases = ["unuse", "unuseitem"];
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
        switch(strtolower($slot)) {
            case "boots":
                $slot = RPG::ITEM_ARMOR_BOOTS;
                break;
            case "gloves":
                $slot = RPG::ITEM_ARMOR_GLOVES;
                break;
            case "helmet":
                $slot = RPG::ITEM_ARMOR_HELMET;
                break;
            case "pants":
                $slot = RPG::ITEM_ARMOR_PANTS;
                break;
            case "pauldron":
                $slot = RPG::ITEM_ARMOR_PAULDRON;
                break;
            case "weapon":
                $slot = RPG::ITEM_WEAPON;
                break;
            default:
                $msg->reply('Available argument(s): boots, gloves, helmet, pants, pauldron');
                return;
                break;
        }

        $toslot = $database->findRPGEmptyInventorySlot($msg->author->id);
        if ($toslot === false) {
            $msg->reply('You inventory is full!');
            return;
        }

        if (!$database->releaseRPGUserItem($msg->author->id, $slot, $toslot)) {
            $msg->reply('Couldn\'t release item.');
            return;
        }
    }
}