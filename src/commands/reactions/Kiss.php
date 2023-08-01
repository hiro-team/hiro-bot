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

use Discord\Parts\Embed\Embed;

/**
 * Kiss
 */
class Kiss extends Command
{
    /**
     * configure
     *
     * @return void
     */
    public function configure(): void
    {
        $this->command = "kiss";
        $this->description = "You can kiss everybody.";
        $this->aliases = ["öp"];
        $this->category = "reactions";
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
        global $language;
        $gifs = [
            "https://bariscodefxy.github.io/cdn/hiro/kiss.gif",
            "https://bariscodefxy.github.io/cdn/hiro/kiss_1.gif",
            "https://bariscodefxy.github.io/cdn/hiro/kiss_2.gif",
            "https://bariscodefxy.github.io/cdn/hiro/kiss_3.gif",
            "https://bariscodefxy.github.io/cdn/hiro/kiss_4.gif",
            "https://bariscodefxy.github.io/cdn/hiro/kiss_5.gif",
            "https://bariscodefxy.github.io/cdn/hiro/kiss_6.gif",
        ];
        $random = $gifs[rand(0, sizeof($gifs) - 1)];
        $self = $msg->author;
        $user = $msg->mentions->first();
        if (empty($user)) {
            $embed = new Embed($this->discord);
            $embed->setColor("#ff0000");
            $embed->setDescription($language->getTranslator()->trans('commands.kiss.no_user'));
            $embed->setTimestamp();
            $msg->channel->sendEmbed($embed);
            return;
        } else if ($user->id == $self->id) {
            $embed = new Embed($this->discord);
            $embed->setColor("#ff0000");
            $embed->setDescription($language->getTranslator()->trans('commands.kiss.selfkiss'));
            $embed->setTimestamp();
            $msg->channel->sendEmbed($embed);
            return;
        }
        $embed = new Embed($this->discord);
        $embed->setColor("#ff0000");
        $embed->setDescription(sprintf($language->getTranslator()->trans('commands.kiss.success'), $self, $user));
        $embed->setImage($random);
        $embed->setTimestamp();
        $msg->channel->sendEmbed($embed);
    }
}
