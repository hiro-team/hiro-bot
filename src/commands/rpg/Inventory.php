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

use hiro\helpers\RPGUI;
use hiro\database\Database;
use Discord\Parts\Embed\Embed;
use Discord\Builders\MessageBuilder;
use Discord\Parts\Channel\Attachment;
use Discord\Parts\Guild\Member;

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
     * @param  [type] $msg
     * @param  [type] $args
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

        $embed = new Embed($this->discord);
        $embed->setTitle($user->username . " Inventory");
        $embed->setAuthor($user->username, $msg->author->avatar);
        $embed->setDescription(
            "<:g_level:1107035586994389062> Level:      " . $database->getUserLevel($user_id) . 
            "\n" . 
            "<a:g_exp:1107035947494805584> Experience:      " . $database->getUserExperience($user_id) .
            "\n" .
            "<:race_Z:1107036549255790592> Race:        " . $race .
            "\n" .
            "<:gender:1107036557271113728> Gender:      " . $gender . 
            "\n" .
            "<:skill:1107037343610835006> Type:   asd  " . $type
        );

        $msg->reply(MessageBuilder::new()->addEmbed($embed));
    }
}
