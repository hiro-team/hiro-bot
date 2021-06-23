<?php

include __DIR__ . '/vendor/autoload.php';

use Discord\DiscordCommandClient;
use Discord\Parts\User\Activity;
use hiro\CommandLoader;

// If you arent using Heroku you should delete // symbols
//$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
//$dotenv->load();
$bot = new DiscordCommandClient([
    'token' => $_ENV['TOKEN'],
    'prefix' => 'hiro!',
]);

$bot->on('ready', function($discord) {
    echo "Bot is ready!", PHP_EOL;
    $commandLoader = new CommandLoader($discord);
    
    $act = $discord->factory(Activity::class, [
        "name" => php_uname(),
        "type" => Activity::TYPE_WATCHING
    ]);
    $discord->updatePresence($act, false, 'online');
});

$bot->run();
