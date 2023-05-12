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

use Discord\Builders\MessageBuilder;
use hiro\database\Database;
use hiro\helpers\RPGUI;

/**
 * Inventory
 */
class Inventory extends Command
{
    /**
     * configure
     *
     * @return void
     */
    public function configure(): void
    {
        $this->command = "inventory";
        $this->description = "Opens your inventory.";
        $this->aliases = ["inv", "i"];
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

        $user = $msg->member;
        $user_id = $database->getUserIdByDiscordId($user->id);
        $money =  $database->getUserMoney($user_id);
        $gender = $database->getRPGCharGenderAsText($user_id);
        $race = $database->getRPGCharRaceAsText($user_id);
        $type = $database->getRPGCharTypeAsText($user_id);
        $items = $database->getRPGUserItems($user->id);
        $character = $gender . "_" . $race . "_" . $type;

        $msg->reply(MessageBuilder::new()->addFile($filepath = RPGUI::drawRPGInventoryUI($user->nick ?? $user->username, $character, $items, $money)))->then(function () use ($filepath) {
            unlink($filepath);
        });
    }
}
