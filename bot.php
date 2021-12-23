<?php

include __DIR__ . '/vendor/autoload.php';

use Discord\DiscordCommandClient;
use Discord\Parts\User\Activity;
use hiro\CommandLoader;

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();
$bot = new DiscordCommandClient([
    'token' => $_ENV['TOKEN'],
    'prefix' => 'hiro!',
    'shardId' => 0,
    'shardCount' => 5,
]);

$bot->on('ready', function($discord) {
    echo "Bot is ready!", PHP_EOL;
    $commandLoader = new CommandLoader($discord);
    
    $act = $discord->factory(Activity::class, [
        "name" => "Hiro Abandoned | hiro!help👾 hiro!money🤑 hiro!hug🥵 hiro!ban😈 hiro!slap🤬 v1.0.0",
        "type" => Activity::TYPE_WATCHING
    ]);
    $discord->updatePresence($act, false, 'idle');
});

$bot->run();
