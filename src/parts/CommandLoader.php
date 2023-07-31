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

namespace hiro\parts;

use hiro\database\Database;
use hiro\interfaces\HiroInterface;
use hiro\interfaces\SecurityCommandInterface;
use Wujunze\Colors;

/**
 * CommandLoader
 */
class CommandLoader
{
    /**
     * client
     *
     * @var HiroInterface
     */
    protected HiroInterface $client;

    /**
     * CLI Colors
     *
     * @var Colors
     */
    protected Colors $colors;

    /**
     * Command categories
     */
    protected $categories = [];

    /**
     * CommandLoader $version
     */
    protected $version = "1.2";

    /**
     * Commands Directory
     */
    protected $dir = "";

    /**
     * CommandLoader Constructor
     */
    public function __construct(HiroInterface $client)
    {
        $this->client = $client;
        $this->colors = new Colors();
        $this->dir = dirname(__DIR__, 1) . "/commands";
        $this->loadAllCommands();
    }

    /**
     * loadAllCommands
     *
     * @return void
     */
    public function loadAllCommands()
    {
        $this->loaderInfo();

        $this->print_color("Loading commands...", "yellow");
        $this->loadDir($this->dir);
        $this->print_color("All commands has been loaded.", "green");

        $this->print_color("Bot is ready for use!", "green");
    }

    /**
     * getCommandsCount
     *
     * @return void
     */
    public function getCommandsCount()
    {
        $num = 0;
        foreach ($this->categories as $category) {
            foreach ($category as $command) {
                $num += 1;
            }
        }
        return $num;
    }

    /**
     * loadDir
     *
     * @param  string $dir
     * @return void
     */
    protected function loadDir(string $dir): void
    {
        foreach (scandir($dir) as $file) {
            $extension = substr($file, -4);

            if ($file != '.' && $file != '..' && $extension == '.php') {
                $class = substr($file, 0, -4);

                if ($class === "Command") { // default class
                    continue;
                }

                try {
                    include $dir . "/" . $file;
                } catch (\Throwable $e) {
                    echo $e->getMessage() . " " . $e->getFile() . ":" . $e->getLine() . \PHP_EOL;
                }

                $classnamespace = 'hiro\\commands\\' . $class;
                $cmd = new $classnamespace($this->client, $this);

                $this->print_color("Loading {$class}...", "yellow");
                $this->loadCommand($cmd);

                if (!isset($this->categories[$cmd->category])) {
                    $this->categories[$cmd->category] = [];
                }

                $kategori = $this->categories[$cmd->category];
                array_push($kategori, $cmd);
                $this->categories[$cmd->category] = $kategori;

                $this->print_color("{$class} loaded.", "green");
            } elseif ($file != '.' && $file != '..' && is_dir($dir . "/" . $file)) {
                $this->loadDir($dir . "/" . $file);
            }
        }
    }

    /**
     * getCmd
     */
    public function getCmd($cmd_name)
    {
        foreach ($this->categories as $category) {
            foreach ($category as $cmd) {
                if ($cmd_name === $cmd->command) return $cmd;
            }
        }
        return null;
    }

    /**
     * loadCommand
     */
    public function loadCommand($cmd)
    {
        $command = $cmd->command;

        $closure = function ($msg, $args) use ($cmd) {
            try {
                if ($cmd->category == "rpg") {
                    $database = new Database();

                    if ($database->isConnected) {
                        $rpgenabled = $database->getRPGEnabledForServer($database->getServerIdByDiscordId($msg->guild->id));
                        $rpgchannel = $database->getRPGChannelForServer($database->getServerIdByDiscordId($msg->guild->id));

                        if ($cmd->command != "setrpgchannel" && $cmd->command != "setrpgenabled") {
                            if (!$rpgenabled) {
                                $msg->reply('RPG commands is not enabled in this server.');
                                return;
                            } elseif (!$rpgchannel) {
                                $msg->reply('RPG commands channel is not available for this server.');
                                return;
                            } elseif ($rpgchannel != $msg->channel->id) {
                                $msg->reply('You should use this command in <#' . $rpgchannel . '>'); // may be problems if channel was deleted.
                                return;
                            }


                            if ($cmd->command != "createchar") {
                                $charType = $database->getRPGCharType($database->getUserIdByDiscordId($msg->author->id));
                                $charNation = $database->getRPGCharRace($database->getUserIdByDiscordId($msg->author->id));
                                $charGender = $database->getRPGCharGender($database->getUserIdByDiscordId($msg->author->id));

                                if (!$charType || !$charNation || !$charGender) {
                                    $msg->reply('You must create your character first!');
                                    return;
                                }
                            }
                        }
                    }
                }

                $database = new Database();

                if (!$database->isUserBannedFromBot($msg->author->id)) {
                    if( $cmd instanceof SecurityCommandInterface )
                    {
                        if( !$cmd->securityChecks(['msg' => $msg, 'client' => $this->client]) )
                        {
                            return;
                        }
                    }

                    global $language;
                    $language = new Language($database->getUserLocale($database->getUserIdByDiscordId($msg->author->id)) ?? "en_EN");

                    $cmd->handle($msg, $args);
                }
            } catch (\Throwable $e) {
                if (\hiro\Version::TYPE == 'development') {
                    echo $e;
                }
                $msg->reply("ERROR: `" . $e->getMessage() . "`");
            }
        };

        $options = [
            'aliases' => $cmd->aliases,
            'description' => $cmd->description,
            'cooldown' => $cmd->cooldown ?? 0
        ];

        $this->client->registerCommand(
            $command,
            $closure,
            $options
        );

        // $command_for_slash = Discord\Parts\Interactions\Command\Command($this->client, $options);
        // $this->client->application->commands->save(
        //     $this->client->application->commands->create(
        //         CommandBuilder::new()
        //             ->setName($command)
        //             ->setDescription($cmd->description)
        //             ->toArray()
        //     )
        // );
        // $this->client->listenCommand($command, function(Interaction $interaction) use ($closure, $command) {
        //     {$closure}($interaction->message, substr($interaction->message->content, strlen($this->client->prefix . $command . " ")));
        // });
    }

    /**
     * loaderInfo
     *
     * @return void
     */
    private function loaderInfo()
    {
        $this->print_color("CommandLoader v{$this->version}", "yellow");
    }

    /**
     * prints colored text
     *
     * @param string $text
     * @param string $color1
     * @param string $color2
     * @return void
     */
    public function print_color(string $text, ?string $color1 = null, ?string $color2 = null): void
    {
        print $this->colors->getColoredString("[ HIRO BOT ] ", "blue") . $this->colors->getColoredString($text, $color1, $color2) . PHP_EOL;
    }

    /**
     * __get
     *
     * @param  string $var
     * @return void
     */
    public function __get(string $var)
    {
        return $this->{$var};
    }
}
