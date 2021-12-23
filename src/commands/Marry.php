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

/**
 * Marry command class
 */
class Marry
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
    public function __construct(DiscordCommandClient $client)
    {
        $this->discord = $client;
        $this->category = "fun";
        $client->registerCommand('marry', function($msg, $args)
        {
            $gifs = [
                "https://bariscodefxy.github.io/cdn/hiro/marry.gif",
                "https://bariscodefxy.github.io/cdn/hiro/marry_1.gif",
                "https://bariscodefxy.github.io/cdn/hiro/marry_2.gif",
                "https://bariscodefxy.github.io/cdn/hiro/marry_3.gif",
                "https://bariscodefxy.github.io/cdn/hiro/marry_4.gif",
                "https://bariscodefxy.github.io/cdn/hiro/marry_5.gif",
            ];
            $random = $gifs[rand(0, sizeof($gifs) - 1)];
            $self = $msg->author->user;
            $user = $msg->mentions->first();
            if(empty($user))
            {
                $embed = new Embed($this->discord);
                $embed->setColor("#ff0000");
                $embed->setDescription("You must mention a user for get marry");
                $embed->setTimestamp();
                $msg->channel->sendEmbed($embed);
                return;
            }else if($user->id == $self->id)
            {
                $embed = new Embed($this->discord);
                $embed->setColor("#ff0000");
                $embed->setDescription("You cant marry with yourself stupid!");
                $embed->setTimestamp();
                $msg->channel->sendEmbed($embed);
                return;
            }
            $embed = new Embed($this->discord);
            $embed->setColor("#ff0000");
            $embed->setDescription("$self get married with you! $user");
            $embed->setImage($random);
            $embed->setTimestamp();
            $msg->channel->sendEmbed($embed);
        }, [
            "aliases" => [],
            "description" => "You can marry with everybody"
        ]);
    }
    
    public function __get(string $name)
    {
        return $this->{$name};
    }
    
}
