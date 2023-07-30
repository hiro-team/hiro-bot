<?php

/**
 * Copyright 2023 bariscodefx
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
use Psr\Http\Message\ResponseInterface;
use React\Http\Browser;

/**
 * WaifuNSFW
 */
class WaifuNSFW extends Command
{
    /**
     * Browser
     *
     * @var Browser
     */
    public Browser $browser;

    /**
     * configure
     *
     * @return void
     */
    public function configure(): void
    {
        $this->command = "waifunsfw";
        $this->description = "Find your own *horny* waifu!";
        $this->aliases = ["wnsfw", "wn"];
        $this->category = "reactions";
        $this->browser = new Browser(null, $this->discord->getLoop());
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
        if (!$msg->channel->nsfw) {
            $msg->reply('You have to use this command in nsfw channel!');
            return;
        }
        $type_array = [
            "waifu",
            "neko",
            "trap",
            "blowjob"
        ];
        if (!isset($args[0])) $type = "waifu";
        if (isset($args[0])) {
            if (!in_array($args[0], $type_array)) {
                $msg->reply("{$args[0]} is not available. \nAvailable categories: `" . implode(", ", $type_array) . "`");
                return;
            }
            $type = $args[0];
        }
        $this->browser->get("https://api.waifu.pics/nsfw/$type")->then(
            function (ResponseInterface $response) use ($msg) {
                $result = (string)$response->getBody();
                $api = json_decode($result);
                $embed = new Embed($this->discord);
                $embed->setColor("#EB00EA");
                $embed->setTitle('Horny Waifu Generator');
                $embed->setDescription("{$msg->author->username} Your random horny waifu :)");
                $embed->setImage($api->url);
                $embed->setTimestamp();
                $msg->channel->sendEmbed($embed);
            },
            function (Exception $e) use ($msg) {
                $msg->reply('Unable to acesss the waifu.pics API :(');
            }
        );
    }
}
