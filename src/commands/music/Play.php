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
use Yt\Dlp\YtDlp;

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
        $youtube = new Youtube(array('key' => $_ENV['YT_API_KEY']));
        $channel = $msg->member->getVoiceChannel();

        if (!$channel) {
            $msg->channel->sendMessage("You must be in a voice channel.");
            return;
        }
        
        $keyword = implode(' ', $args);
        
        if(!$keyword)
        {
            $msg->reply("You should write a keyword!");
            return;
        }
        
        $videos = $youtube->searchVideos($keyword, 1);
        if(!$videos)
        {
            $msg->reply("Couldn't found a video on youtube.");
            return;
        }
        
        $video = $videos[0]; // best match

        $voiceClient = $this->discord->getVoiceClient($msg->channel->guild_id);
        
        if(!$voiceClient)
        {
            if($channel = $msg->member->getVoiceChannel())
            {
                $this->discord->joinVoiceChannel($channel, false, true, null, true)->done(function (VoiceClient $vc) use ($msg, $keyword) {
                    
                }, function ($e) use ($msg) {
                    $msg->channel->sendMessage("There was an error joining the voice channel: {$e->getMessage()}"); 
                });
            }
        }

        if ($voiceClient && $channel->id !== $voiceClient->getChannel()->id)
        {
            $msg->channel->sendMessage("You must be in same channel with me.");
            return;
        }
        
        $yt = new YtDlp();

        $yt->getInfo("https://youtube.com/watch?v=" . $video->id->videoId)->then(static function (stdClass $video) use ($voiceClient) {
          foreach ($video->formats as $format) {
            if ($bestFormat === null) {
              $bestFormat = $format;

              continue;
            }

            if ($format->abr > $bestFormat->abr) {
              $bestFormat = $format;
            }

            $stream = $voiceClient->ffmpegEncode($bestFormat->url);
            $stream->start();
            $voiceClient->playOggStream($stream);
          }
        });
    }
}
