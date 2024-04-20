<?php

/**
 * Copyright 2021-2024 bariscodefx
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

use hiro\database\Database;
use hiro\parts\generators\{GithubImageGenerator, MonsterGenerator};
use hiro\parts\rpg\{AttackSystem, LevelSystem};
use hiro\interfaces\GeneratorReturn;
use hiro\parts\Language;
use Discord\Parts\Embed\Embed;
use Discord\Parts\User\User;
use Discord\Builders\MessageBuilder;
use Discord\Builders\Components\{Button, ActionRow};
use Discord\Parts\Channel\Message;
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
        global $language;
        $database = new Database();
        if (!$database->isConnected) {
            $msg->channel->sendMessage($language->getTranslator()->trans('database.notconnect'));
            return;
        }

        $startMessage = $this->getStartMessage($msg->author, $language);
        $custom_id = $startMessage[1];
        $btn = $startMessage[2];

        $msg->channel->sendMessage($startMessage[0])->then(function ($msg) use ($custom_id, $btn) {
            $this->discord->getLoop()->addTimer(5.0, function () use ($msg, $custom_id, $btn) {
                foreach($msg->components as $collection)
                {
                    foreach($collection->components as $component)
                    {
                        if($component['custom_id'] == $custom_id)
                        {
                            $btn->setListener(null, $this->discord); // fill null listener if user didnt contact with button
                        }
                    }
                }
            });
        });
    }

    /**
     * attackHandle
     * 
     * @var ?Interaction $interaction
     * @var GeneratorReturn $monster
     * @var bool $attack
     * @var Language $language
     */
    public function attackHandle(Interaction $interaction = null, User $user, GeneratorReturn $monster, bool $attack, Language $language): ?MessageBuilder
    {
        $embed = new Embed($this->discord);
        $embed
            ->setTitle($monster->getName())
            ->setDescription(<<<EOF
{$language->getTranslator()->trans('commands.hunt.monster_hp')}: {$monster->getHealth()}
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
        if($interaction && $attack)
        {
            $monster->setHealth(
                $monster->getHealth() - AttackSystem::getAttackDamage($uLvl)
            );

            if ($monster->getHealth() <= 0) {
                $exp = $monster->getXp();

                $database->setUserExperience(
                    $uId,
                    $uExp + $exp
                );

                if ($uExp + $exp >= LevelSystem::getRequiredExperiences($uLvl)) {
                    $database->setUserExperience($uId, abs($uExp - LevelSystem::getRequiredExperiences($uLvl)));
                    $database->setUserLevel($uId, $uLvl + 1);

                    $interaction->channel->sendMessage($language->getTranslator()->trans('commands.hunt.level_up'));
                }

                $interaction->updateMessage(
                    MessageBuilder::new()
                        ->setContent(sprintf($language->getTranslator()->trans('commands.hunt.gain_exp'), $exp))
                        ->setEmbeds([])
                        ->setComponents([])
                );

                $this->discord->getLoop()->addTimer(2.0, function () use ($interaction, $language) {
                    $startMessage = $this->getStartMessage($interaction->user, $language);
                    $custom_id = $startMessage[1];
                    $btn = $startMessage[2];

                    $interaction->channel->sendMessage($startMessage[0])->then(function ($msg) use ($custom_id, $btn) {
                        $this->discord->getLoop()->addTimer(5.0, function () use ($msg, $custom_id, $btn) {
                            foreach($msg->components as $collection)
                            {
                                foreach($collection->components as $component)
                                {
                                    if($component['custom_id'] == $custom_id)
                                    {
                                        $btn->setListener(null, $this->discord); // fill null listener if user didnt contact with button
                                    }
                                }
                            }
                        });
                    });
                });

                return null;
            }
        }

        $buildedMsg = MessageBuilder::new()
            ->addComponent(
                ActionRow::new()->addComponent(
                    Button::new(Button::STYLE_DANGER)->setLabel($language->getTranslator()->trans('commands.hunt.attack_button'))
                        ->setCustomId(sprintf("for-%s", $user->id))
                        ->setListener(
                            function (Interaction $i) use ($user, $monster, $language) {
                                if (!str_starts_with($i->data->custom_id, "for-{$i->user->id}")) {
                                    return;
                                }
                                $this->attackHandle($i, $user, $monster, true, $language);
                            },
                            $this->discord,
                            true
                        )
                )
            )
            ->addEmbed($embed);

        if ($interaction) {
            $interaction->updateMessage($buildedMsg);
        }

        return $buildedMsg;
    }

    /**
     * getStartMessage
     * 
     * @var User $user
     * @var Language $language
     * @return MessageBuilder
     */
    public function getStartMessage(User $user, Language $language): array
    {
        $embed = new Embed($this->discord);
        $embed->setTitle($language->getTranslator()->trans('commands.hunt.start_msg.title'));
        $embed->setDescription($language->getTranslator()->trans('commands.hunt.start_msg.description'));
        $embed->setTimestamp();
        $random_hex = bin2hex(random_bytes(6));
        $custom_id = "hunting-{$random_hex}-{$user->id}";

        $buildedMsg = MessageBuilder::new()
            ->addEmbed($embed)
            ->addComponent(
                ActionRow::new()->addComponent(
                    $btn = Button::new(Button::STYLE_DANGER)
                        ->setLabel($language->getTranslator()->trans('commands.hunt.start_button'))
                        ->setCustomId($custom_id)
                        ->setListener(
                            function (Interaction $interaction) use ($custom_id, $user, $language) {
                                if (!str_starts_with($interaction->data->custom_id, $custom_id)) {
                                    return;
                                }
                                $generator = new MonsterGenerator();
                                $monster = $generator->generateRandom();
                                $interaction->message->edit($this->attackHandle(null, $user, $monster, true, $language));
                            },
                            $this->discord,
                            true
                        )
                )
            );

        return [$buildedMsg, $custom_id, $btn];
    }
}
