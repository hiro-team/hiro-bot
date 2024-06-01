<?php

/**
 * Copyright 2021-2024 bariscodefx
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
use Discord\Parts\Interactions\Interaction;
use Discord\Parts\Channel\Message;
use Discord\Builders\MessageBuilder;

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
        if(!isset($args['respondable']))
        {
            return false;
        }

        if (!$args['respondable']->member->getVoiceChannel()) {
            $args['respondable']->channel->sendMessage("You're must be in a voice channel.");
            return false;
        }

        if (!$args['client']->getVoiceClient($args['respondable']->guild_id)) {
            $args['respondable']->reply("You should use join command first.");
            return false;
        }

        if ($args['client']->getVoiceClient($args['respondable']->guild_id) && $args['respondable']->member->getVoiceChannel()->id !== $args['client']->getVoiceClient($args['respondable']->guild_id)->getChannel()->id) {
            $args['respondable']->reply("You must be in same channel with me.");
            return false;
        }

        if (!isset($voiceSettings[$args['respondable']->guild_id]))
	    {
            $args['respondable']->reply("Voice options couldn't found.");
		    return false;
	    }

        return true;
    }

}