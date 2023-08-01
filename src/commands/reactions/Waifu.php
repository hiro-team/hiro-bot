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
 * Waifu
 */
class Waifu extends Command
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
        $this->command = "waifu";
        $this->description = "Find your own waifu!";
        $this->aliases = ["wfu"];
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
        global $language;
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
        if (!isset($args[0]))
        {
            $type = "waifu";
        }

        if (!in_array($args[0], $type_array)) {
            $msg->reply(sprintf($language->getTranslator()->trans('commands.waifu.not_available_category'), $args[0]) . " \n" . sprintf($language->getTranslator()->trans('commands.waifu.available_categories'), implode(", ", $type_array)));
            return;
        }
        $type = $args[0];

        $this->browser->get("https://api.waifu.pics/sfw/$type")->then(
            function (ResponseInterface $response) use ($msg, $language) {
                $result = (string)$response->getBody();
                $api = json_decode($result);
                $embed = new Embed($this->discord);
                $embed->setColor("#EB00EA");
                $embed->setTitle($language->getTranslator()->trans('commands.waifu.title'));
                $embed->setDescription(sprintf($language->getTranslator()->trans('commands.waifu.success'), $msg->author->username));
                $embed->setImage($api->url);
                $embed->setTimestamp();
                $msg->channel->sendEmbed($embed);
            },
            function (\Exception $e) use ($msg, $language) {
                $msg->reply($language->getTranslator()->trans('commands.waifu.api_error'));
            }
        );
    }
}
