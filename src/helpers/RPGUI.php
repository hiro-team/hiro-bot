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

namespace hiro\helpers;

use GdImage;
use hiro\consts\RPG;

/**
 * RPGUI
 */
class RPGUI
{
    /**
     * Font Value
     */
    public const FONT = 2;

    /**
     * drawRPGInventoryUI
     *
     * @param string $username
     * @return string
     */
    public static function drawRPGInventoryUI(string $username, string $character, array $items, int $money = 0): string
    {
        $username = substr(self::remove_emoji($username), 0, 20);
        $image     = imagecreatefrompng(dirname(__DIR__, 1) . "/images/rpg/ui/InvUI.png");
        $filepath = dirname(__DIR__, 2) . "/cache/" . random_int(100000, 999999) . ".png";

        $image = self::drawMoney($image, $money);
        $image = self::drawCharacter($image, $username, $character);
        $image = self::drawItems($image, $items);

        imagepng($image, $filepath);
        imagedestroy($image);
        return $filepath;
    }

    /**
     * drawMoney
     *
     * @param GdImage $image
     * @param integer $value
     * @return GdImage
     */
    protected static function drawMoney(GdImage $image, int $value = 0): GdImage
    {
        $string = (string)number_format($value);
        $orange = imagecolorallocate($image, 220, 210, 60);
        imagestring($image, self::FONT, (159 - (strlen($string) * imagefontwidth(self::FONT))), (314 - (imagefontheight(self::FONT) / 2)), $string, $orange);
        return $image;
    }

    /**
     * drawCharacter
     *
     * @param GdImage $image
     * @param string $username
     * @param string $file
     * @return GdImage
     */
    protected static function drawCharacter(GdImage $image, string $username, string $file): GdImage
    {
        if (!file_exists(dirname(__DIR__, 1) . "/images/rpg/characters/" . $file . ".png")) {
            return $image;
        }
        $character = imagecreatefrompng(dirname(__DIR__, 1) . "/images/rpg/characters/" . $file . ".png");
        $character = imagescale($character, ceil(imagesx($character)/4.2));
        imagecopy($image, $character, (123 - (imagesx($character) / 2)), (187 - (imagesy($character) / 2)), 0, 0, imagesx($character), imagesy($character));
        $image = self::drawCharacterNick($image, $username);
        return $image;
    }

    /**
     * drawCharacterNick
     *
     * @param GdImage $image
     * @param string $username
     * @return GdImage
     */
    protected static function drawCharacterNick(GdImage $image, string $username): GdImage
    {
        $background_path = dirname(__DIR__, 1) . "/images/rpg/ui/nick_background.png";
        if (file_exists($background_path)) {
            $background = imagecreatefrompng($background_path);
            imagecopy($image, $background, (123 - ((imagesx($background) / 2))), 80 - (imagesy($background) / 2), 0, 0, imagesx($background), imagesy($background));
        }
        $orange = imagecolorallocate($image, 220, 210, 60);
        imagestring($image, self::FONT, (123 - (strlen($username) * imagefontwidth(self::FONT)) / 2), 75, $username, $orange);
        return $image;
    }

    /**
     * drawItem
     *
     * @param GdImage $image
     * @param string $file
     * @param integer $slot
     * @return GdImage
     */
    protected static function drawItem(GdImage $image, string $file, int $slot, $x = null, $y = null): GdImage
    {
        if (!file_exists(dirname(__DIR__, 1) . "/images/rpg/items/" . $file . ".png")) {
            return $image;
        }
        $item = imagecreatefrompng(dirname(__DIR__, 1) . "/images/rpg/items/" . $file . ".png");
        $item = imagescale($item, 46, 46);
        if ($x && $y) {
            imagecopy($image, $item, $x - imagesx($item)/2, $y - imagesy($item)/2, 0, 0, imagesx($item), imagesy($item));
            return $image;
        }
        $y = intval($slot / 7);
        $x = $slot % 7;
        imagecopy($image, $item, 28 + (49 * $x), 346 + (49 * $y), 0, 0, imagesx($item), imagesy($item)); // what the fuck
        return $image;
    }

    /**
     * drawItemFrame
     *
     * @param GdImage $image
     * @param integer $slot
     * @return GdImage
     */
    protected static function drawItemFrame(GdImage $image, int $slot, $x = null, $y = null): GdImage
    {
        if (!file_exists(dirname(__DIR__, 1) . "/images/rpg/ui/itemFrame.png")) {
            return $image;
        }
        $fr = imagecreatefrompng(dirname(__DIR__, 1) . "/images/rpg/ui/itemFrame.png");
        $fr = imagescale($fr, 46, 46);
        if ($x && $y) {
            imagecopy($image, $fr, $x - imagesx($fr)/2, $y - imagesy($fr)/2, 0, 0, imagesx($fr), imagesy($fr));
            return $image;
        }
        $y = intval($slot / 7);
        $x = $slot % 7;
        imagecopy($image, $fr, 28 + (49 * $x), 346 + (49 * $y), 0, 0, imagesx($fr), imagesy($fr)); // what the fuck
        return $image;
    }

    /**
     * drawItems
     *
     * @param GdImage $image
     * @param array $items
     * @return GdImage
     */
    protected static function drawItems(GdImage $image, array $items): GdImage
    {
        foreach ($items as $i) {
            if ($i['item_using']) {
                if ($i['item_type'] & RPG::ITEM_ARMOR) {
                    if ($i['item_type'] & RPG::ITEM_ARMOR_BOOTS) {
                        $x = RPG::POS_BOOTS[0];
                        $y = RPG::POS_BOOTS[1];
                    } elseif ($i['item_type'] & RPG::ITEM_ARMOR_GLOVES) {
                        $x = RPG::POS_GLOVES[0];
                        $y = RPG::POS_GLOVES[1];
                    } elseif ($i['item_type'] & RPG::ITEM_ARMOR_HELMET) {
                        $x = RPG::POS_HELMET[0];
                        $y = RPG::POS_HELMET[1];
                    } elseif ($i['item_type'] & RPG::ITEM_ARMOR_PANTS) {
                        $x = RPG::POS_PANTS[0];
                        $y = RPG::POS_PANTS[1];
                    } elseif ($i['item_type'] & RPG::ITEM_ARMOR_PAULDRON) {
                        $x = RPG::POS_PAULDRON[0];
                        $y = RPG::POS_PAULDRON[1];
                    } elseif ($i['item_type'] & RPG::ITEM_ARMOR_EARRING) {
                        if ($i['item_type'] & RPG::ITEM_USED_LEFT) {
                            $x = RPG::POS_EARRING_LEFT[0];
                            $y = RPG::POS_EARRING_LEFT[1];
                        } elseif ($i['item_type'] & RPG::ITEM_USED_RIGHT) {
                            $x = RPG::POS_EARRING_RIGHT[0];
                            $y = RPG::POS_EARRING_RIGHT[1];
                        }
                    } elseif ($i['item_type'] & RPG::ITEM_ARMOR_RING) {
                        if ($i['item_type'] & RPG::ITEM_USED_LEFT) {
                            $x = RPG::POS_RING_LEFT[0];
                            $y = RPG::POS_RING_LEFT[1];
                        } elseif ($i['item_type'] & RPG::ITEM_USED_RIGHT) {
                            $x = RPG::POS_RING_RIGHT[0];
                            $y = RPG::POS_RING_RIGHT[1];
                        }
                    } elseif ($i['item_type'] & RPG::ITEM_ARMOR_SHIELD) {
                        $x = RPG::POS_SHIELD[0];
                        $y = RPG::POS_SHIELD[1];
                    } elseif ($i['item_type'] & RPG::ITEM_ARMOR_PENDANT) {
                        $x = RPG::POS_PENDANT[0];
                        $y = RPG::POS_PENDANT[1];
                    }
                } elseif ($i['item_type'] & RPG::ITEM_WEAPON) {
                    if ($i['item_type'] & RPG::ITEM_USED_LEFT) {
                        $x = RPG::POS_WEAPON[0];
                        $y = RPG::POS_WEAPON[1];
                    } elseif ($i['item_type'] & RPG::ITEM_USED_RIGHT) {
                        $x = RPG::POS_SHIELD[0];
                        $y = RPG::POS_SHIELD[1];
                    }
                }
                $image = self::drawItemFrame($image, $i['item_slot'] ?? 0, $x, $y);
                $image = self::drawItem($image, $i['item_image'], $i['item_slot'] ?? 0, $x, $y);
                continue;
            }
            $image = self::drawItemFrame($image, $i['item_slot']);
            $image = self::drawItem($image, $i['item_image'], $i['item_slot']);
        }
        return $image;
    }

    /**
     * remove_emoji
     *
     * @param string $string
     * @return string
     */
    protected static function remove_emoji(string $string): string
    {
        // Match Enclosed Alphanumeric Supplement
        $regex_alphanumeric = '/[\x{1F100}-\x{1F1FF}]/u';
        $clear_string = preg_replace($regex_alphanumeric, '', $string);

        // Match Miscellaneous Symbols and Pictographs
        $regex_symbols = '/[\x{1F300}-\x{1F5FF}]/u';
        $clear_string = preg_replace($regex_symbols, '', $clear_string);

        // Match Emoticons
        $regex_emoticons = '/[\x{1F600}-\x{1F64F}]/u';
        $clear_string = preg_replace($regex_emoticons, '', $clear_string);

        // Match Transport And Map Symbols
        $regex_transport = '/[\x{1F680}-\x{1F6FF}]/u';
        $clear_string = preg_replace($regex_transport, '', $clear_string);

        // Match Supplemental Symbols and Pictographs
        $regex_supplemental = '/[\x{1F900}-\x{1F9FF}]/u';
        $clear_string = preg_replace($regex_supplemental, '', $clear_string);

        // Match Miscellaneous Symbols
        $regex_misc = '/[\x{2600}-\x{26FF}]/u';
        $clear_string = preg_replace($regex_misc, '', $clear_string);

        // Match Dingbats
        $regex_dingbats = '/[\x{2700}-\x{27BF}]/u';
        $clear_string = preg_replace($regex_dingbats, '', $clear_string);

        return $clear_string;
    }
}
