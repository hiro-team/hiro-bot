<?php

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
        $channel = $msg->member->getVoiceChannel();

        if (!$channel) {
            $msg->channel->sendMessage("You must be in a voice channel.");
        }

        if ($this->discord->getVoiceClient($msg->channel->guild->id) && $channel->id !== $this->discord->getVoiceClient($msg->channel->guild->id)->getChannel()->id) {
            $msg->channel->sendMessage("You must be in same channel with me.");
        }

        if ($this->discord->getVoiceClient($msg->channel->guild->id)) {
            $this->discord->getVoiceClient($msg->channel->guild->id)->close();
        }
    }
}
