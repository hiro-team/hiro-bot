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

use Discord\Voice\VoiceClient;

class Leave extends Command
{
    /**
     * configure
     *
     * @return void
     */
    public function configure(): void
    {
        $this->command = "leave";
        $this->description = "Bot leaves from voice channel that you in";
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
        $channel = $msg->member->getVoiceChannel();

	$voiceClient = $this->discord->getVoiceClient($msg->guild_id);

        if ($voiceClient && $channel->id !== $voiceClient->getChannel()->id) {
            $msg->channel->sendMessage("You must be in same channel with me.");
            return;
	}

        if ($voiceClient) {
            $voiceClient->close();
            if(isset($voiceSettings[$msg->guild_id]))
            {
                unset($voiceSettings[$msg->guild_id]);
            }
        } else {
            $msg->channel->sendMessage("I'm not in a voice channel.");
        }
    }
}
