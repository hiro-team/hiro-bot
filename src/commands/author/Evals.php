<?php

/**
 * Copyright 2022 bariscodefx
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

/**
 * Evals
 */
class Evals extends Command
{
    /**
     * configure
     *
     * @return void
     */
    public function configure(): void
    {
        $this->command = "evals";
        $this->description = "Runs a code **ONLY FOR AUTHOR**";
        $this->aliases = ["run", "code", "eval"];
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
        $content = explode(' ', $msg->content, 2);
        if (!isset($content[1])) {
            $msg->reply("No args.");
            return;
        }
        $code = $content[1];
        if (str_starts_with($code, "```php")) {
            $code = substr($code, 6);
        }
        if (str_starts_with($code, "```")) {
            $code = substr($code, 3);
        }
        if (str_ends_with($code, "```")) {
            $code = substr($code, 0, -3);
        }
        try {
            $output = eval($code);
            $msg->reply($output);
        } catch (\Throwable $e) {
            $msg->reply("Error: \n```\n{$e->getMessage()}```");
        }
    }
}
