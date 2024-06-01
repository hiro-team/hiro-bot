<?php

/**
 * Copyright 2021-2024 bariscodefx
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
        $this->category = "utility";
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
        global $language;
        $database = new Database();
        if (!$database->isConnected) {
            $msg->channel->sendMessage($language->getTranslator()->trans('database.notconnect'));
            return;
        }
        $usermoney = $database->getUserMoney($database->getUserIdByDiscordId($msg->author->id));
        if (!is_numeric($usermoney)) {
            if (!$database->addUser([
                "discord_id" => $msg->author->id
            ])) {
                $msg->reply($language->getTranslator()->trans('database.user.couldnt_added'));
                return;
            } else {
                $usermoney = 0;
            }
        }
        if (!$args[0] || !is_numeric($args[0])) {
            $msg->reply($language->getTranslator()->trans('commands.coinflip.no_amount'));
        } else {
            if ($args[0] <= 0) {
                $msg->reply($language->getTranslator()->trans('commands.coinflip.too_less_amount'));
            } else if ($args[0] > $usermoney) {
                $msg->reply($language->getTranslator()->trans('global.not_enough_money'));
            } else {
                $payamount = $args[0];
                $rand = random_int(0, 1);

                // delete user money from payamount
                $database->setUserMoney($database->getUserIdByDiscordId($msg->author->id), $usermoney - $payamount);
                $usermoney -= $payamount;

                $msg->reply($language->getTranslator()->trans('commands.coinflip.coin_spinning') . " <a:hirocoinflip:1130395266105737256>")->then(function($botreply) use ($msg, $rand, $database, $usermoney, $payamount, $language){
                    $this->discord->getLoop()->addTimer(2.0, function() use ($botreply, $msg, $rand, $database, $usermoney, $payamount, $language){
                        setlocale(LC_MONETARY, 'en_US');
                        if ($rand) {
                            $database->setUserMoney($database->getUserIdByDiscordId($msg->author->id), $usermoney + $payamount * 2);
                            $botreply->edit(MessageBuilder::new()->setContent($language->getTranslator()->trans('commands.coinflip.win') . " +" . $payamount*2 . " <:hirocoin:1130392530677157898>"));
                        } else {
                            $botreply->edit(MessageBuilder::new()->setContent($language->getTranslator()->trans('commands.coinflip.lose') . " -" . $payamount . " <:hirocoin:1130392530677157898>"));
                        }
                    });
                });
            }
        }
    }
}
