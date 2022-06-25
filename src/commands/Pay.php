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
use Discord\Parts\Embed\Embed;
use Discord\Parts\Embed\Field;
use hiro\database\Database;
use hiro\interfaces\HiroInterface;
use hiro\interfaces\CommandInterface;

class Pay implements CommandInterface
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
        $client->registerCommand('pay', function($msg, $args)
        {
            $database = new Database();
            if(!$database->isConnected)
            {
                $msg->channel->sendMessage("Couldn't connect to database.");
                return;
            }
            $embed = new Embed($this->discord);
			$user = $msg->mentions->first();
            if(!$user) {
                $embed->setDescription("You should select a user for send money.");
                $embed->setColor('#ff0000');
                $msg->channel->sendEmbed($embed);
                return;
            }
            if($user->id === $msg->author->user->id)
            {
                $embed->setDescription("You can't send your money to yourself.");
                $embed->setColor('#ff0000');
                $msg->channel->sendEmbed($embed);
                return;
            }
            if(!isset($args[1]) && !is_numeric($args[1]))
            {
                $embed->setDescription('You should give a numeric money.');
                $embed->setColor('#ff0000');
                $msg->channel->sendEmbed($embed);
                return;
            }
            if(!$database->getUser($database->getUserIdByDiscordId($msg->author->user->id)))
            {
                if(!$database->addUser(["discord_id" => $user->id]))
                {
                    $embed->setDescription('An error excepted when registering you to database :(');
                    $embed->setColor('#ff0000');
                    $msg->channel->sendEmbed($embed);
                    return;
                }
            }
            if(!$database->getUser($database->getUserIdByDiscordId($user->id)))
            {
                if(!$database->addUser(["discord_id" => $user->id]))
                {
                    $embed->setDescription('An error excepted when registering user to database :(');
                    $embed->setColor('#ff0000');
                    $msg->channel->sendEmbed($embed);
                    return;
                }
            }
            if(!$database->pay($database->getUserIdByDiscordId($msg->author->user->id), $database->getUserIdByDiscordId($user->id), $args[1]))
            {
                $embed->setDescription('An error excepted when sending money :(');
                $embed->setColor('#ff0000');
                $msg->channel->sendEmbed($embed);
                return;
            }
            setlocale(LC_MONETARY, 'en_US');
            $embed->setTitle("Money Sent!");
            $embed->setDescription($msg->user . " - $ " . number_format($args[1], 2,',', '.') . "  -->  " . $user . " + $ " . number_format($args[1], 2,',', '.'));
            $embed->setColor('#7CFC00');
            $msg->channel->sendEmbed($embed);
            return;
        }, [
            "description" => "Send your money to anybody.",
            "cooldown" => 10 * 1000
        ]);
    }
    
    public function __get(string $name)
    {
        return $this->{$name};
    }
    
}
