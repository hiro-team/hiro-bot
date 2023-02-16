<?php

namespace hiro\commands;

use Discord\Voice\VoiceClient;

class Join extends Command
{
    /**
     * configure
     *
     * @return void
     */
    public function configure(): void
    {
        $this->command = "join";
        $this->description = "Bot joins to voice channel that you in";
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
        $channel = $msg->member->getVoiceChannel();

        if (!$channel) {
            $msg->channel->sendMessage("You must be in a voice channel.");
        }

        $this->discord->joinVoiceChannel($channel)->done(function (VoiceClient $vc) {
        }, function ($e) use ($msg) {
            $msg->channel->sendMessage("There was an error joining the voice channel: {$e->getMessage()}"); 
        });
    }
}
