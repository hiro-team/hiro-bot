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

use React\Http\Browser;
use Psr\Http\Message\ResponseInterface;
use Discord\Parts\Embed\Embed;

/**
 * Apod
 */
class Apod extends Command
{

    /**
     * configure
     *
     * @return void
     */
    public function configure(): void
    {
        $this->command = "apod";
        $this->description = "Shows some pictures from today by Nasa.";
        $this->aliases = [];
        $this->category = "utility";
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
        $this->browser->get("https://api.nasa.gov/planetary/apod?thumbs=true&api_key=" . $_ENV['NASA_KEY'])->then(function (ResponseInterface $response) use ($msg) {
            $embed = new Embed($this->discord);

            $result = json_decode((string)$response->getBody());

            $embed->setAuthor($result->copyright, $msg->author->avatar);
            $embed->setTitle($result->title);
            $embed->setImage($result->hdurl);
            $embed->setDescription($result->explanation);
            $embed->addField($this->discord->makeField("Date", $result->date));
            $embed->addField($this->discord->makeField("Media Type", $result->media_type));
            $embed->addField($this->discord->makeField("Service Version", $result->service_version));
            $embed->setTimestamp();

            $msg->channel->sendEmbed($embed);
        }, function (Exception $e) use ($msg) {
            $msg->reply('Unable to acesss the Nasa API :(');
        });
    }
}
