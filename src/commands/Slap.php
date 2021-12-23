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
 * Slap command class
 */
class Slap
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
        $client->registerCommand('slap', function($msg, $args)
        {
            $gifs = [
                "https://bariscodefxy.github.io/cdn/hiro/slap.gif",
                "https://bariscodefxy.github.io/cdn/hiro/slap_1.gif",
                "https://bariscodefxy.github.io/cdn/hiro/slap_2.gif",
                "https://bariscodefxy.github.io/cdn/hiro/slap_3.gif",
                "https://bariscodefxy.github.io/cdn/hiro/slap_4.gif",
                "https://bariscodefxy.github.io/cdn/hiro/slap_5.gif",
            ];
            $random = $gifs[rand(0, sizeof($gifs) - 1)];
            $self = $msg->author->user;
            $user = $msg->mentions->first();
            if(empty($user))
            {
                $embed = new Embed($this->discord);
                $embed->setColor("#ff0000");
                $embed->setDescription("You must mention a user for slap");
                $embed->setTimestamp();
                $msg->channel->sendEmbed($embed);
                return;
            }else if($user->id == $self->id)
            {
                $embed = new Embed($this->discord);
                $embed->setColor("#ff0000");
                $embed->setDescription("You cant slap yourself stupid!");
                $embed->setTimestamp();
                $msg->channel->sendEmbed($embed);
                return;
            }
            $embed = new Embed($this->discord);
            $embed->setColor("#ff0000");
            $embed->setDescription("$self slapped you! $user");
            $embed->setImage($random);
            $embed->setTimestamp();
            $msg->channel->sendEmbed($embed);
        }, [
            "aliases" => [
                "tokat"
            ],
            "description" => "You can slap everybody"
        ]);
    }
    
    public function __get(string $name)
    {
        return $this->{$name};
    }
    
}
