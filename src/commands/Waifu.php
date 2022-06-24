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
use Psr\Http\Message\ResponseInterface;
use React\Http\Browser;

/**
 * Waifu command class
 */
class Waifu implements CommandInterface
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
        $this->category = "fun";
        $this->browser = new Browser(null, $this->discord->getLoop());
        $client->registerCommand('waifu', function($msg, $args)
        {
            $type_array = [
                "waifu",
                "neko",
                "shinobu",
                "megumin",
                "bully",
                "cuddle",
                "cry",
                "hug",
                "awoo",
                "kiss",
                "lick",
                "pat",
                "smug",
                "bonk",
                "yeet",
                "blush",
                "smile",
                "wave",
                "highfive",
                "handhold",
                "nom",
                "bite",
                "glomp",
                "slap",
                "kill",
                "kick",
                "happy",
                "wink",
                "poke",
                "dance",
                "cringe"
            ];
            if(!isset($args[0])) $type = "waifu";
            if(isset($args[0])){
                if(!in_array($args[0], $type_array))
                {
                    $msg->reply("{$args[0]} is not available. \nAvailable categories: `". implode(", ", $type_array) . "`");
                    return;
                }
                $type = $args[0];
            }
            $this->browser->get("https://api.waifu.pics/sfw/$type")->then(
                function (ResponseInterface $response) use ($msg) {
                    $result = (string) $response->getBody();
                    $api = json_decode($result);
                    $embed = new Embed($this->discord);
                    $embed->setColor("#EB00EA");
                    $embed->setTitle('Waifu Generator');
                    $embed->setDescription("{$msg->user->username} Your random waifu!");
                    $embed->setImage($api->url);
                    $embed->setTimestamp();
                    $msg->channel->sendEmbed($embed);
                },
                function (Exception $e) use ($msg) {
                    $msg->reply('Unable to acesss the waifu.pics API :(');
                }
            );
        }, [
            "aliases" => [
                "wfu"
            ],
            "description" => "How much u are waifu"
        ]);
    }
    
    public function __get(string $name)
    {
        return $this->{$name};
    }
    
}
