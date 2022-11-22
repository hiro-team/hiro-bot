<?php

/**
 * Copyright 2021 bariscodefx
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
use hiro\interfaces\HiroInterface;
use hiro\interfaces\CommandInterface;

/**
 * Clear command class
 */
class Clear implements CommandInterface
{
    
    /**
     * Command category
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
        $this->discord = $client;
        $this->category = "mod";
        $client->registerCommand('clear', function($msg, $args)
        {
            if(!$msg->author->getPermissions()["manage_messages"])
            {
                $embed = new Embed($this->discord);
                $embed->setTitle("Error!");
                $embed->setDescription("You must have manage messages permission for use this");
                $embed->setColor("#ff000");
                $embed->setTimestamp();
                $msg->channel->sendEmbed($embed);
                return;
            }
            $limit = $args[0];
            if(!isset($limit))
            {
                $embed = new Embed($this->discord);
                $embed->setTitle("Error!");
                $embed->setDescription("You must give an amount");
                $embed->setColor("#ff000");
                $embed->setTimestamp();
                $msg->channel->sendEmbed($embed);
                return;
            }else if(!is_numeric($limit))
            {
                $embed = new Embed($this->discord);
                $embed->setTitle("Error!");
                $embed->setDescription("You must give an numeric parameter");
                $embed->setColor("#ff000");
                $embed->setTimestamp();
                $msg->channel->sendEmbed($embed);
                return;
            }else if($limit < 1 || $limit > 100)
            {
                $embed = new Embed($this->discord);
                $embed->setTitle("Error!");
                $embed->setDescription("Amount must be around of 1-100");
                $embed->setColor("#ff000");
                $embed->setTimestamp();
                $msg->channel->sendEmbed($embed);
                return;
            }
            $msg->channel->limitDelete($limit);
            $embed = new Embed($this->discord);
            $embed->setTitle("Clear Command");
            $embed->setDescription($limit . " messages was deleted.");
            $embed->setColor("#5558E0");
            $embed->setTimestamp();
            $msg->channel->sendEmbed($embed);
        }, [
            "aliases" => [
                "purge"
            ],
            "description" => "Clears messages",
            "cooldown" => 10 * 1000
        ]);
    }
    
    public function __get(string $name)
    {
        return $this->{$name};
    }
    
}
