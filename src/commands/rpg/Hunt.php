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

use hiro\consts\RPG;
use hiro\database\Database;
use hiro\parts\generators\{GithubImageGenerator, MonsterGenerator};
use hiro\parts\rpg\{AttackSystem, LevelSystem};
use hiro\interfaces\GeneratorReturn;
use Discord\Parts\Channel\Message;
use Discord\Parts\Embed\Embed;
use Discord\Parts\User\User;
use Discord\Builders\MessageBuilder;
use Discord\Builders\Components\{Button, ActionRow};
use Discord\Parts\Interactions\Interaction;

class Hunt extends Command
{
    /**
     * configure
     *
     * @return void
     */
    public function configure(): void
    {
        $this->command = "hunt";
        $this->description = "Hunting.";
        $this->aliases = ["hunting"];
        $this->category = "rpg";
    }

    /**
     * handle
     *
     * @param  [type] $msg
     * @param  [type] $args
     * @return void
     */
    public function handle($msg, $args): void
    {
        $database = new Database();
        if (!$database->isConnected) {
            $msg->channel->sendMessage("Couldn't connect to database.");
            return;
        }

        $msg->channel->sendMessage(
            $this->getStartMessage($msg->author)
        );
    }

    /**
     * attackHandle
     * 
     * @var Interaction $interaction
     * @var GeneratorReturn $monster
     * @var bool $attack
     */
    public function attackHandle(Interaction $interaction = null, User $user, GeneratorReturn $monster, bool $attack = true)
    {
        $embed = new Embed($this->discord);
        $embed
            ->setTitle($monster->getName())
            ->setDescription(<<<EOF
Monster HP {$monster->getHealth()}
EOF)
            ->setImage(GithubImageGenerator::generate($monster->getName()))
            ->setTimestamp();
        
        $database = new \hiro\database\Database();
        
        $uId = $database->getUserIdByDiscordId(
            $user->id
        );
        $uLvl = $database->getUserLevel(
            $uId
        );
        $uExp = $database->getUserExperience(
            $uId
        );

        // attack event
        if($interaction->message && $attack)
        {
            $monster->setHealth(
                $monster->getHealth() - AttackSystem::getAttackDamage($uLvl)
            );
            
            if($monster->getHealth() <= 0)
            {
                $exp = $monster->getXp();

                $database->setUserExperience(
                    $uId,
                    $uExp + $exp
                );

                if( $uExp + $exp >= LevelSystem::getRequiredExperiences($uLvl) )
                {
                    $database->setUserExperience($uId, abs($uExp - LevelSystem::getRequiredExperiences($uLvl)));
                    $database->setUserLevel($uId, $uLvl + 1);

                    $interaction->channel->sendMessage("Level up !");
                }

                $interaction->updateMessage(
                    MessageBuilder::new()
                    ->setContent(sprintf("Monster died! Gained %d experiences.", $exp))
                    ->setEmbeds([])
                    ->setComponents([])
                );
                
                $this->discord->getLoop()->addTimer(2.0, function() use ($interaction) {
                    $interaction->channel->sendMessage($this->getStartMessage($interaction->user));
                });

                return;
            }
        }

        $buildedMsg = MessageBuilder::new()
        ->addComponent(
            ActionRow::new()->addComponent(
                Button::new(Button::STYLE_DANGER)->setLabel("Attack")
                ->setCustomId(sprintf("for-%s", $interaction->user->id))
                ->setListener(
                    function(Interaction $i) use ($user, $monster)
                    {
                        if (!str_starts_with($i->data->custom_id, "for-{$i->user->id}")) {
                            return;
                        }
                        $this->attackHandle($i, $user, $monster);
                    },
                    $this->discord,
                    true
                )
            )
        )
        ->addEmbed($embed);

        if($interaction->message) {
            $interaction->message->edit($buildedMsg);
        } else {
            $interaction->respondWithMessage($buildedMsg);
        }
    }

    /**
     * getStartMessage
     * 
     * @return MessageBuilder
     */
    public function getStartMessage(User $user): MessageBuilder
    {
        $embed = new Embed($this->discord);
        $embed->setTitle("Hunting");
        $embed->setDescription("Click to the button for starting hunting");
        $embed->setTimestamp();

        return MessageBuilder::new()
                ->addEmbed($embed)
                ->addComponent(
                    ActionRow::new()->addComponent(
                        Button::new(Button::STYLE_DANGER)
                            ->setLabel("Start Hunting")
                            ->setCustomId(sprintf("for-%s", $user->id))
                            ->setListener(
                            function (Interaction $interaction) use ($user) {
                                if (!str_starts_with($interaction->data->custom_id, "for-{$user->id}")) {
                                    return;
                                }
                                $generator = new MonsterGenerator();
                                $monster = $generator->generateRandom();
                                $this->attackHandle(null, $user, $monster, true);
                            },
                            $this->discord,
                            true
                        )
                    )
        );
    }
}
