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
use React\ChildProcess\Process;
use Discord\Builders\MessageBuilder;

class Play extends Command
{
    /**
     * configure
     *
     * @return void
     */
    public function configure(): void
    {
        $this->command = "play";
        $this->description = "Plays music from youtube.";
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
        global $voiceClients;
        $channel = $msg->member->getVoiceChannel();

        if (!$channel) {
            $msg->channel->sendMessage("You must be in a voice channel.");
            return;
        }

        $url = substr($msg->content, strlen($_ENV['PREFIX'] . "play "));

        if(!$url)
        {
            $msg->reply("You should write a URL!");
            return;
        }

        $voiceClient = @$voiceClients[$msg->channel->guild_id];

        if(!$voiceClient)
        {
            $msg->reply("Use join command first.\n");
            return;
        }

        if ($voiceClient && $channel->id !== $voiceClient->getChannel()->id)
        {
            $msg->channel->sendMessage("You must be in same channel with me.");
            return;
        }

        @unlink($msg->author->id . ".m4a");
        @unlink($msg->author->id . ".info.json");

        $process = new Process("./yt-dlp -f bestaudio[ext=m4a] --ignore-config --ignore-errors --write-info-json --output=./{$msg->author->id}.m4a --audio-quality=0 {$url}");
	$process->start();

	$editmsg = $msg->reply("Downloading audio please wait...");

        $process->on('exit', function($code, $term) use ($msg, $voiceClient, $editmsg)
        {
            if(is_file($msg->author->id . ".m4a"))
	    {
		$voiceClient->playFile($msg->author->id . ".m4a");
	    }
            $editmsg->then(function($m) use ($msg) {
		if(!is_file($msg->author->id . ".m4a"))
		{
			$m->edit(MessageBuilder::new()->setContent("Couldn't download the audio."));
		} else {
			$jsondata = json_decode(file_get_contents($msg->author->id . ".info.json"));

			$m->edit(MessageBuilder::new()->setContent("Playing **{$jsondata->title}**. :musical_note: :tada:"));
		}
            });
            $this->discord->getLoop()->addTimer( 0.5, function() use ($msg)
            {
                @unlink($msg->author->id . ".m4a");
                @unlink($msg->author->id . ".info.json");
            });
        });
    }
}
