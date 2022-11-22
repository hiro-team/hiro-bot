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
use hiro\interfaces\HiroInterface;
use hiro\interfaces\CommandInterface;

/**
 * Evals command class
 */
class Evals implements CommandInterface
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
        $this->category = "author";
        $client->registerCommand('eval', function($msg, $args)
        {
            if($msg->author->user->id != 793431383506681866)
            {
                $msg->channel->sendMessage("No");
                return;
            }
            $content = explode(' ', $msg->content, 2);
            if(!isset($content[1])) return $msg->reply("No args.");
            $code = $content[1];
            if(str_starts_with($code, "```php")) $code = substr($code, 6);
            if(str_starts_with($code, "```")) $code = substr($code, 3);
            if(str_ends_with($code, "```")) $code = substr($code, 0, -3);
            try {
                eval($code);
            }catch(\Throwable $e){
                $msg->reply("Error: \n```\n{$e->getMessage()}```");
            }
        }, [
            "aliases" => [
                "execute", "code"
            ],
            "description" => "Runs a code **ONLY FOR AUTHOR**"
        ]);
    }
    
    public function __get(string $name)
    {
        return $this->{$name};
    }
    
}
