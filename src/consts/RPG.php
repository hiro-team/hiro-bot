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

namespace hiro\consts;

/**
 * RPG - Constants of RPG
 */
class RPG
{
    /**
     * Character Types
     */
    public const WARRIOR_CHAR                      = (1 << 1);
    public const RANGER_CHAR                       = (1 << 2);
    public const MAGE_CHAR                         = (1 << 3);
    public const HEALER_CHAR                       = (1 << 4);

    /**
     * Race Types
     */
    public const HUMAN_RACE                        = (1 << 1);
    public const DRAGON_RACE                       = (1 << 2);
    public const ELF_RACE                          = (1 << 3);
    public const EVILEYETRIBE_RACE                 = (1 << 4);
    public const ONI_RACE                          = (1 << 7);
    public const WOLFHUMAN_RACE                    = (1 << 8);

    /**
     * Gender Types
     */
    public const MALE_GENDER                       = (1 << 1);
    public const FEMALE_GENDER                     = (1 << 2);

    /**
     * Item Types
     */
    public const ITEM_DEFAULT                      = (1 << 1);
    public const ITEM_WEAPON                       = (1 << 2);
    public const ITEM_ARMOR                        = (1 << 3);
    public const ITEM_ARMOR_BOOTS                  = (1 << 4);
    public const ITEM_ARMOR_GLOVES                 = (1 << 5);
    public const ITEM_ARMOR_HELMET                 = (1 << 6);
    public const ITEM_ARMOR_PANTS                  = (1 << 7);
    public const ITEM_ARMOR_PAULDRON               = (1 << 8);

    /**
     * Inventory UI
     */
    public const MAX_ITEM_SLOT                     = 7 * 4;
    public const POS_HELMET                        = [292, 96];
    public const POS_PAULDRON                      = [292, 148];
    public const POS_PANTS                         = [292, 252];
    public const POS_BOOTS                         = [292, 304];
    public const POS_GLOVES                        = [240, 304];
    public const POS_WEAPON                        = [240, 200];

    /**
     * getRacesAsArray
     *
     * @param boolean $swap
     * @return array
     */
    public static function getRacesAsArray(bool $swap = false): array
    {
        $array = [
            "human" => self::HUMAN_RACE,
            "dragon" => self::DRAGON_RACE,
            "elf" => self::ELF_RACE,
            "evileyetribe" => self::EVILEYETRIBE_RACE,
            "oni" => self::ONI_RACE,
            "wolfhuman" => self::WOLFHUMAN_RACE,
        ];

        if ($swap) {
            $array = array_flip($array);
        }

        return $array;
    }
}
