<?php

/**
 * Copyright 2021 bariscodefx
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

include __DIR__ . '/vendor/autoload.php';

use hiro\Hiro;
use Discord\Parts\User\Activity;
use hiro\CommandLoader;
use Discord\WebSockets\Event;
use Discord\Parts\Channel\Message;
use hiro\ArgumentParser;
use hiro\interfaces\HiroInterface;

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

$ArgumentParser = new ArgumentParser($argv);
$shard_id = $ArgumentParser->getShardId();
$shard_count = $ArgumentParser->getShardCount();
$bot = new Hiro([
    'token' => $_ENV['TOKEN'],
    'shardId' => $shard_id,
    'shardCount' => $shard_count
]);
$prefix = "hiro!";

$bot->on('ready', function($discord) use ($shard_id, $shard_count, $prefix) {
    echo "Bot is ready!", PHP_EOL;
    
    $discord->on(Event::MESSAGE_CREATE, function(Message $message, HiroInterface $discord) use ($prefix) {
        if($discord->id == $message->author->id)
            return;

        if(!preg_match("/$prefix([A-Za-z0-9-_]+)/", strtolower($message->content)))
            return;

        $command = @explode($prefix, strtolower($message->content))[1];
        if(!$command)
            return;
        $message->reply($command);
    } );

    $act = $discord->factory(Activity::class, [
        "name" => "Shard $shard_id of $shard_count | Hiro Developing...",
        "type" => Activity::TYPE_WATCHING
    ]);
    $discord->updatePresence($act, false, 'idle');
});

$bot->run();
