<?php

/**
 * Copyright 2023 bariscodefx
 * 
 * This file is part of project Hiro 016 Discord Bot.
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

use hiro\security\MusicCommand;
class Loop extends MusicCommand
{
    public function configure(): void
    {
        $this->command = "loop";
        $this->description = "Enables or disables looping in music.";
        $this->aliases = [];
        $this->category = "music";
    }

    public function handle($msg, $args): void
    {
        global $voiceSettings;

        $settings = @$voiceSettings[$msg->channel->guild_id];

        if ($settings->getLoopEnabled())
        {
            $settings->setLoopEnabled(false);
            $msg->reply("Loop is **disabled** now.");
        } else {
             $settings->setLoopEnabled(true);
            $msg->reply("Loop is **enabled** now.");
        }
    }
}
