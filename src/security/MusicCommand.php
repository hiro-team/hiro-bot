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

namespace hiro\security;

use hiro\interfaces\SecurityCommandInterface;
use hiro\commands\Command;

/**
 * MusicCommand
 */
class MusicCommand extends Command implements SecurityCommandInterface
{

    /**
     * securityChecks
     *
     * @param array $args
     * @return boolean
     */
    public function securityChecks(array $args): bool
    {
        global $voiceSettings;
        if(!isset($args['msg']))
        {
            return false;
        }

        if (!$args['msg']->member->getVoiceChannel()) {
            $args['msg']->channel->sendMessage("You must be in a voice channel.");
            return false;
        }

        if (!$args['client']->getVoiceClient($args['msg']->guild_id)) {
            $args['msg']->channel->sendMessage("You should use join command first.");
            return false;
        }

        if ($args['client']->getVoiceClient($args['msg']->guild_id) && $args['msg']->member->getVoiceChannel()->id !== $args['client']->getVoiceClient($args['msg']->guild_id)->getChannel()->id) {
            $args['msg']->channel->sendMessage("You must be in same channel with me.");
            return false;
        }

        if (!isset($voiceSettings[$args['msg']->guild_id]))
	    {
		    $args['msg']->reply("Voice options couldn't found.");
		    return false;
	    }

        return true;
    }

}