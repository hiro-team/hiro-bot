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

use Discord\Parts\Embed\Embed;
use hiro\database\Database;
use Discord\Builders\MessageBuilder;

/**
 * Coinflip
 */
class Coinflip extends Command
{
    /**
     * configure
     *
     * @return void
     */
    public function configure(): void
    {
        $this->command = "coinflip";
        $this->description = "An economy game";
        $this->aliases = ["cf"];
        $this->category = "economy";
        $this->cooldown = 10 * 1000;
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
        $usermoney = $database->getUserMoney($database->getUserIdByDiscordId($msg->author->id));
        if (!is_numeric($usermoney)) {
            if (!$database->addUser([
                "discord_id" => $msg->author->id
            ])) {
                $msg->reply("You are couldnt added to database.");
                return;
            } else {
                $usermoney = 0;
            }
        }
        if (!$args[0] || !is_numeric($args[0])) {
            $msg->reply("You should type payment amount.");
        } else {
            if ($args[0] <= 0) {
                $msg->reply("You should give a value greater than zero.");
            } else if ($args[0] > $usermoney) {
                $msg->reply("Your money isn't enough.");
            } else {
                $payamount = $args[0];
                $rand = random_int(0, 1);

                // delete user money from payamount
                $database->setUserMoney($database->getUserIdByDiscordId($msg->author->id), $usermoney - $payamount);
                $usermoney -= $payamount;

                $msg->reply("Coin is flipping... <a:hirocoinflip:1130395266105737256>")->then(function($botreply) use ($msg, $rand, $database, $usermoney, $payamount){
                    $this->discord->getLoop()->addTimer(2.0, function() use ($botreply, $msg, $rand, $database, $usermoney, $payamount){
                        setlocale(LC_MONETARY, 'en_US');
                        if ($rand) {
                            $database->setUserMoney($database->getUserIdByDiscordId($msg->author->id), $usermoney + $payamount * 2);
                            $botreply->edit(MessageBuilder::new()->setContent("You win :) <:hirocoin:1130392530677157898>"));
                        } else {
                            $botreply->edit(MessageBuilder::new()->setContent("You lose :( <:hirocoin:1130392530677157898>"));
                        }
                    });
                });
            }
        }
    }
}
