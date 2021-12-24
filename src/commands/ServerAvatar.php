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
 * ServerAvatar command class
 */
class ServerAvatar implements CommandInterface
{
    
    /**
     * command category
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
        $this->category = "bot";
        $client->registerCommand('serveravatar', function($msg, $args)
        {
            if(!@$msg->channel->guild) 
            {
                $msg->reply("You can only use in a guild!");
                return;
            }
            $embed = new Embed($this->discord);
            $embed->setColor("#ff0000");
            $embed->setDescription($msg->channel->guild->getUpdatableAttributes()['name'] . "'s Avatar");
            $embed->setImage($msg->channel->guild->getIconAttribute("png", 1024));
            $embed->setTimestamp();
            $msg->channel->sendEmbed($embed);
        }, [
            "aliases" => [
                "server_avatar",
                "server-avatar",
                "svavatar",
                "savatar",
                "getserveravatar"
            ],
            "description" => "Returns server pfp"
        ]);
    }
    
    public function __get(string $name)
    {
        return $this->{$name};
    }
    
}
