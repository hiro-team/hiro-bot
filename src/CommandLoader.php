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

namespace hiro;

use hiro\commands\Command;
use hiro\interfaces\HiroInterface;

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
        $this->dir = __DIR__ . "/commands";
        $this->loadAllCommands();
    }

    /**
     * loadAllCommands
     *
     * @return void
     */
    public function loadAllCommands()
    {
        $this->clearConsole();
        $this->loaderInfo();

        print "Loading Commands" . PHP_EOL;
        $this->loadDir($this->dir);
        print "All Commands Are Loaded." . PHP_EOL;
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
     * @param string $dir
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
                    require $dir . "/" . $file;
                } catch (\Throwable $e) {
                }

                $classnamespace = 'hiro\\commands\\' . $class;
                $cmd = new $classnamespace($this->client, $this);

                $this->client->registerCommand(
                    $cmd->command,
                    function ($msg, $args) use ($cmd) {
                        $cmd->handle($msg, $args);
                    },
                    [
                        'aliases' => $cmd->aliases,
                        'description' => $cmd->description,
                        'cooldown' => $cmd->cooldown ?? 0
                    ]
                );

                if (!isset($this->categories[$cmd->category])) {
                    $this->categories[$cmd->category] = [];
                }

                $kategori = $this->categories[$cmd->category];
                array_push($kategori, $cmd);
                $this->categories[$cmd->category] = $kategori;

                print "====================" . PHP_EOL;
                print "Loaded : $class" . PHP_EOL;
                print "====================" . PHP_EOL;
            } elseif ($file != '.' && $file != '..' && is_dir($dir . "/" . $file)) {
                $this->loadDir($dir . "/" . $file);
            }
        }
    }

    /**
     * clearConsole
     *
     * This function clears the console/terminal.
     *
     * @return void
     */
    private function clearConsole()
    {
        for ($i = 0; $i < 100; $i++) {
            print PHP_EOL;
        }
    }

    /**
     * loaderInfo
     *
     * @return void
     */
    private function loaderInfo()
    {
        exec("figlet \"Command\nLoader\nv$this->version\"", $figlet);
        foreach ($figlet as $line) {
            print $line . PHP_EOL;
        }
        print PHP_EOL . PHP_EOL;
    }

    /**
     * __get
     *
     * @param string $var
     * @return void
     */
    public function __get(string $var)
    {
        return $this->{$var};
    }
}
