<?php

/**
 * Copyright 2022 bariscodefx
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

use hiro\database\Database;
use Discord\Parts\Channel\Message;

/**
 * Slots
 */
class Slots extends Command
{
    /**
     * configure
     *
     * @return void
     */
    public function configure(): void
    {
        $this->command = "slots";
        $this->description = "An economy game.";
        $this->aliases = ["slot"];
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
        $items = [
            ":strawberry:",
            ":fireworks:",
            ":gem:",
            ":heart:"
        ];
        $database = new Database();
        if (!$database->isConnected) {
            $msg->channel->sendMessage("Couldn't connect to database.");
            return;
        }
        if (!isset($args[0])) {
            $msg->reply("You have to write a pay amount.");
            return;
        }
        $payamount = $args[0];
        if (!is_numeric($payamount)) {
            $msg->reply("Pay amount should be numeric.");
            return;
        }
        if ($payamount < 1) {
            $msg->reply("Invalid pay amount.");
        }
        if (!$database->getUser($database->getUserIdByDiscordId($msg->author->id))) {
            if (!$database->addUser([
                'discord_id' => $msg->author->id
            ])) {
                $msg->reply("An error excepted when adding you to database.");
                return;
            }
        }
        if ($payamount > $database->getUserMoney($database->getUserIdByDiscordId($msg->author->id))) {
            $msg->reply("You can't pay this money, because u dont have it.");
            return;
        }
        if (!$database->setUserMoney($database->getUserIdByDiscordId($msg->author->id), $database->getUserMoney($database->getUserIdByDiscordId($msg->author->id)) - $payamount)) {
            $msg->reply("An error excepted when trying to pay.");
            return;
        }
        $chance = random_int(1, 3);
        if ($chance === 1) {
            if (!$database->setUserMoney($database->getUserIdByDiscordId($msg->author->id), $database->getUserMoney($database->getUserIdByDiscordId($msg->author->id)) + ($payamount * 3))) {
                $msg->reply("An error excepted when trying to give your money.");
                return;
            }
            $rand_emote = $items[random_int(0, sizeof($items) - 1)];
            $choosed = [
                $rand_emote,
                $rand_emote,
                $rand_emote
            ];
        } else {
            $rand_emotes = [
                random_int(0, sizeof($items) - 2),
                random_int(0, sizeof($items) - 2),
                random_int(0, sizeof($items) - 2)
            ];
            if ($rand_emotes[0] === $rand_emotes[1] && $rand_emotes[1] === $rand_emotes[2]) {
                if ($rand_emotes[0] === 0 || $rand_emotes[0] < sizeof($items) - 1) {
                    $rand_emotes[random_int(0, 2)] += 1;
                } else {
                    $rand_emotes[random_int(0, 2)] -= 1;
                }
            }
            $choosed = [
                $items[$rand_emotes[0]],
                $items[$rand_emotes[1]],
                $items[$rand_emotes[2]]
            ];
        }
        $msg->reply("Slot is spinning... :arrows_clockwise: \n:cd: :cd: :cd:")->then(function ($msg) use ($chance, $choosed, $payamount) {
            if (!($msg instanceof Message)) {
                $msg->reply("An error excepted.");
                return;
            }
            $this->discord->getLoop()->addTimer(3.0, function () use ($msg, $chance, $choosed, $payamount) {
                if ($chance === 1) $text = "**YOU WON!!! $ " . $payamount * 3 . "**";
                else $text = "You lose all of your pay :(";
                $msg->edit(\Discord\Builders\MessageBuilder::new()->setContent("Slot has been spinned. \n{$choosed[0]}{$choosed[1]}{$choosed[2]} \n$text"));
            });
        });
    }
}
