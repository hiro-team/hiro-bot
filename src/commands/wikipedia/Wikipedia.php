<?php

/**
 * This file is part of project Hiro 016 Discord Bot.
 *
 * Copyright 2022
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
use hiro\commands\Command;
use hiro\Version;
use GuzzleHttp\Client;

/**
 * Wikipedia
 */
class Wikipedia extends Command
{
    /**
     * configure
     *
     * @return void
     */
    public function configure(): void
    {
        $this->command = "wikipedia";
        $this->description = "Searches for a Wikipedia page.";
        $this->aliases = ["wiki", "w"];
        $this->category = "wikipedia";
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
        // Make sure the user provided a search query
        if (empty($args)) {
            $msg->channel->sendMessage("Please provide a search query.");
            return;
        }

        // Get the search query
        $query = implode(" ", $args);

        // Create a Guzzle client
        $client = new Client([
            "base_uri" => "https://en.wikipedia.org/w/api.php",
            "timeout"  => 5.0,
        ]);

        // Make a request to the Wikipedia API
        $response = $client->request("GET", "", [
            "query" => [
                "action"  => "query",
                "format"  => "json",
                "list"    => "search",
                "srprop"  => "snippet",
                "srsearch" => $query,
            ],
        ]);

        // Decode the JSON response
        $json = json_decode($response->getBody());

        // Check if there are any results
        if (empty($json->query->search)) {
            $msg->channel->sendMessage("No results found.");
            return;
        }

        // Get the first result
        $result = $json->query->search[0];

        // Create an embed with the result information
        $embed = new Embed($this->discord);
        $embed->setTitle($result->title);
        $embed->setDescription($result->snippet);
        $embed->setURL("https://en.wikipedia.org/wiki/" . urlencode($result->title));
        $embed->setThumbnail("https://en.wikipedia.org/static/favicon/wikipedia.ico");
        $embed->setAuthor($msg->member->username, $msg->author->avatar);
        $embed->setTimestamp();

        // Send the embed
        $msg->channel->sendEmbed($embed);
    }
}
