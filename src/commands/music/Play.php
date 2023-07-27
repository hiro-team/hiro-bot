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
use Madcoda\Youtube\Youtube;
use Symfony\Component\Process\Process;
use YoutubeDl\Process\ProcessBuilderInterface;
use YoutubeDl\YoutubeDl;
use YoutubeDl\Options;

class ProcessBuilder implements ProcessBuilderInterface
{
    public function build(?string $binPath, ?string $pythonPath, array $arguments = []): Process
    {
        array_unshift($arguments, '-f bestaudio[ext=m4a]');
        print_r([$binPath, $pythonPath, ...$arguments]);
        $process = new Process([$binPath, $pythonPath, ...$arguments]);
        // Set custom timeout or customize other things..
        $process->setTimeout(10);

        return $process;
    }
}

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

        $voiceClient = $voiceClients[$msg->channel->guild_id];
        
        if(!$voiceClient)
        {
            $msg->reply("Use join command first.\n" . sizeof($voiceClients));
            return;
        }

        if ($voiceClient && $channel->id !== $voiceClient->getChannel()->id)
        {
            $msg->channel->sendMessage("You must be in same channel with me.");
            return;
        }
        
        @unlink("./" . $msg->author->id . ".m4a");
        @unlink("./" . $msg->author->id . ".m4a.json");
        
        $processBuilder = new ProcessBuilder();
        $yt = new YoutubeDl($processBuilder);
        $yt->setBinPath('./yt-dlp');

        $collection = $yt->download(
            Options::create()
                ->downloadPath('.')
                ->audioQuality('0') // best
                ->output($msg->author->id . '.%(ext)s')
                ->url($url)
        );
        
        $this->discord->getLoop()->addTimer( 0.5, function() use ($msg, $voiceClient)
        {
            $voiceClient->playFile($msg->author->id . ".m4a");
            $this->discord->getLoop()->addTimer( 0.5, function() use ($msg)
            {
                @unlink("./" . $msg->author->id . ".m4a");
                @unlink("./" . $msg->author->id . ".m4a.json");
            });
        });
    }
}
