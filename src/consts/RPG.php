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
class RPG {

    /**
     * Character Types
     */
    const WARRIOR_CHAR                      = (1 << 1);
    const RANGER_CHAR                       = (1 << 2);
    const MAGE_CHAR                         = (1 << 3);
    const HEALER_CHAR                       = (1 << 4);

    /**
     * Race Types
     */
    const HUMAN_RACE                        = (1 << 1);
    const BEARHUMAN_RACE                    = (1 << 2);
    const BEASTHUMAN_RACE                   = (1 << 3);
    const DEERHUMAN_RACE                    = (1 << 4);
    const DEMONBEAST_RACE                   = (1 << 5);
    const DRAGON_RACE                       = (1 << 6);
    const DRAGONKIN_RACE                    = (1 << 7);
    const DWARF_RACE                        = (1 << 8);
    const EARTHDRAGON_RACE                  = (1 << 9);
    const ELF_RACE                          = (1 << 10);
    const EVILEYETRIBE_RACE                 = (1 << 11);
    const FOXHUMAN_RACE                     = (1 << 12);
    const GIANT_RACE                        = (1 << 13);
    const HALFONI_RACE                      = (1 << 14);
    const HALFELF_RACE                      = (1 << 15);
    const HYENAHUMAN_RACE                   = (1 << 16);
    const LIZARDHUMAN_RACE                  = (1 << 17);
    const ONI_RACE                          = (1 << 18);
    const PHANTOM_RACE                      = (1 << 19);
    const RABBITHUMAN_RACE                  = (1 << 20);
    const SHUDRAK_RACE                      = (1 << 21);
    const SNAKEHUMAN_RACE                   = (1 << 22);
    const SPIRIT_RACE                       = (1 << 23);
    const TANUKIHUMAN_RACE                  = (1 << 24);
    const WOLFHUMAN_RACE                    = (1 << 25);

    /**
     * Gender Types
     */
    const MALE_GENDER                       = (1 << 1);
    const FEMALE_GENDER                     = (1 << 2);

    /**
     * Weapons
     */
    const SWORD_WEAPON                      = 1;
    const BOW_WEAPON                        = 2;
    const STAFF_WEAPON                      = 3;
    const MORNINGSTAR_WEAPON                = 4;

    /**
     * Weapon Types
     */
    const LONG_WEAPON                       = (1 << 1);
    const SHORT_WEAPON                      = (1 << 2);

}