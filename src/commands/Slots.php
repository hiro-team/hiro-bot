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

use Discord\DiscordCommandClient;
use hiro\database\Database;
use hiro\interfaces\HiroInterface;
use hiro\interfaces\CommandInterface;
use Discord\Parts\Channel\Message;

/**
 * Slots command class
 */
class Slots implements CommandInterface
{
    
    /**
     * command $category
     */
    private $category;
    
    /**
     * $client
     */
    private $discord;
    
    /**
     * __construct
     */
    public function __construct(HiroInterface $client)
    {
        $this->category = "economy";
        $this->discord = $client;
        $client->registerCommand('slots', function($msg, $args)
        {
        	$items = [
        		"<a:blue_cane:990303193332346970>",
        		"<a:blue_moon:990303158389583873>",
        		":strawberry:",
                ":fireworks:",
                ":gem:",
                ":heart:"
        	];
			$database = new Database();
            if(!$database->isConnected)
            {
                $msg->channel->sendMessage("Couldn't connect to database.");
                return;
            }
            if(!isset($args[0]))
            {
            	$msg->reply("You have to write a pay amount.");
            	return;
            }
            $payamount = $args[0];
            if(!is_numeric($payamount))
            {
            	$msg->reply("Pay amount should be numeric.");
            	return;
            }
            if($payamount < 1)
            {
            	$msg->reply("Invalid pay amount.");
            }
            if(!$database->getUser($database->getUserIdByDiscordId($msg->author->user->id)))
            {
            	if(!$database->addUser([
            		'discord_id' => $msg->author->user->id
            	])) return $msg->reply("An error excepted when adding you to database.");
            }
            if($payamount > $database->getUserMoney($database->getUserIdByDiscordId($msg->author->user->id))) return $msg->reply("You can't pay this money, because u dont have it.");
            if(!$database->setUserMoney($database->getUserIdByDiscordId($msg->author->user->id), $database->getUserMoney($database->getUserIdByDiscordId($msg->author->user->id)) - $payamount)) return $msg->reply("An error excepted when trying to pay.");
            $chance = random_int(1, 3);
            if($chance === 1)
            {
            	if(!$database->setUserMoney($database->getUserIdByDiscordId($msg->author->user->id), $database->getUserMoney($database->getUserIdByDiscordId($msg->author->user->id)) + ($payamount * 3))) return $msg->reply("An error excepted when trying to give your money.");
                $rand_emote = $items[random_int(0, sizeof($items) - 1)];
                $choosed = [
                    $rand_emote,
                    $rand_emote,
                    $rand_emote
                ];
            }else {
                $rand_emotes = [
                    random_int(0, sizeof($items) - 2),
                    random_int(0, sizeof($items) - 2),
                    random_int(0, sizeof($items) - 2)
                ];
                if($rand_emotes[0] === $rand_emotes[1] && $rand_emotes[1] === $rand_emotes[2])
                {
                    if($rand_emotes[0] === 0 || $rand_emotes[0] < sizeof($items) - 1)
                    {
                        $rand_emotes[random_int(0, 2)] += 1;
                    }else 
                    {
                        $rand_emotes[random_int(0, 2)] -= 1;
                    }
                }
                $choosed = [
                    $items[$rand_emotes[0]],
                    $items[$rand_emotes[1]],
                    $items[$rand_emotes[2]]
                ];
            }
			$msg->reply("Slot is spinning... <a:loading:990300992287424553> \n<a:slotmachine:990303077213012008> <a:slotmachine:990303077213012008> <a:slotmachine:990303077213012008>")->then(function($msg) use ($chance, $choosed, $payamount)
			{
				if(!($msg instanceof Message)) return $msg->reply("An error excepted.");
				$this->discord->getLoop()->addTimer(3.0, function() use ($msg, $chance, $choosed, $payamount) {
					if($chance === 1) $text = "**YOU WON!!! $ " . $payamount * 3 . "**";
					else $text = "You lose all of your pay :(";
					$msg->channel->editMessage($msg, "Slot has been spinned. \n{$choosed[0]}{$choosed[1]}{$choosed[2]} \n$text");
				});
			});
        }, [
	        "aliases" => [
	            "slot"
	        ],
	        "description" => "An economy game",
	        "cooldown" => 10 * 1000
    	]);
    }
    
    public function __get(string $name)
    {
        return $this->{$name};
    }
    
}
