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

use hiro\interfaces\HiroInterface;
use hiro\database\Database;

/**
 * Botban
 */
class Botban extends Command
{
    /**
     * configure
     *
     * @return void
     */
    public function configure(): void
    {
        $this->command = "botban";
        $this->description = "Ban/unban a player from bot. **ONLY FOR AUTHOR**";
        $this->aliases = [];
        $this->category = "author";
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
        if ($msg->author->id != $_ENV['AUTHOR']) {
            $msg->channel->sendMessage("No");
            return;
	}

	$user = $msg->mentions->first();
	if(!$user)
	{
		$msg->channel->sendMessage("You should mention a user to ban.");
		return;
	}

	if($user->id == $msg->author->id)
	{
		$msg->channel->sendMessage("You can't ban yourself.");
		return;
	}

	$db = new Database();
	if(!$db->isConnected)
	{
		$msg->channel->sendMessage("Couldn't connect to database.");
		return;
	}

	if(!$db->isUserBannedFromBot($user->id))
	{
		$db->banUserFromBot($user->id);
		$msg->channel->sendMessage("User has been banned.");
	} else {
		$db->unbanUserFromBot($user->id);
		$msg->channel->sendMessage("User's ban has been removed.");
	}
    }
}
