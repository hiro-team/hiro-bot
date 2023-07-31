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
use hiro\security\MusicCommand;

/**
 * Queue
 */
class Queue extends MusicCommand
{

    /**
     * configure
     *
     * @return void
     */
    public function configure(): void
    {
        $this->command = "queue";
        $this->description = "Shows music queue!";
        $this->aliases = [];
        $this->category = "music";
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
        global $voiceSettings;
        $queue = $voiceSettings[$msg->guild_id]->getQueue();
        $embed = new Embed($this->discord);
        $embed->setTitle('Queue');
        $embed->setDescription("Current queue (" . sizeof($queue) . "):");
        foreach($queue as $song)
        {
            $embed->addFieldValues($song->title, <<<EOF
            Requested by: <@{$song->author_id}>
            URL: {$song->url}
            EOF);
        }
        $embed->setTimestamp();
        $msg->channel->sendEmbed($embed);
    }
}