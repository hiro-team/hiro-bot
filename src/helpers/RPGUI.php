<?php

namespace hiro\helpers;

use GdImage;
use hiro\Hiro;

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
    public static function drawRPGInventoryUI(string $username, string $character, int $money = 0): string
    {
        $username = substr(self::remove_emoji($username), 0, 20);
        $image     = imagecreatefrompng(dirname(__DIR__, 1) . "/images/rpg/ui/InvUI.png");
        $filepath = dirname(__DIR__, 2) . "/cache/" . random_int(100000, 999999) . ".png";

        $image = self::drawMoney($image, $money);
        $image = self::drawCharacter($image, $username, $character);

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
        if(!file_exists(dirname(__DIR__, 1) . "/images/rpg/characters/" . $file . ".png")) return $image;
        $image = self::drawCharacterNick($image, $username);
        $character = imagecreatefrompng(dirname(__DIR__, 1) . "/images/rpg/characters/" . $file . ".png");
        $character = imagescale($character, ceil(imagesx($character)/4.2));
        imagecopy($image, $character, (123 - (imagesx($character) / 2)), (187 - (imagesy($character) / 2)), 0, 0, imagesx($character), imagesy($character));
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
        if(file_exists($background_path)){
            $background = imagecreatefrompng($background_path);
            imagecopy($image, $background, (123 - ( (imagesx($background) / 2) ) ), 80 - (imagesy($background) / 2), 0, 0, imagesx($background), imagesy($background));
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
    protected static function drawItem(GdImage $image, string $file, int $slot): GdImage
    {
        // 28, 346
        if(!file_exists(dirname(__DIR__, 1) . "/images/rpg/items/" . $file . ".png")) return $image;
        $item = imagecreatefrompng(dirname(__DIR__, 1) . "/images/rpg/items/" . $file . ".png");
        $item = imagescale($item, 47, 47);
        imagecopy($image, $item, ((28 + ( 49 * ( ( intval( $slot / 8 ) >= 1 ? intval( $slot / 8 ) : $slot ) - 1 ) ) ) ), ((346 + ( 49 * ( intval( $slot / 8 ) ) ) ) ), 0, 0, imagesx($item), imagesy($item)); // what the fuck
        return $image;
    }

    /**
     * drawItemFrame
     *
     * @param GdImage $image
     * @param integer $slot
     * @return GdImage
     */
    protected static function drawItemFrame(GdImage $image, int $slot): GdImage
    {
        if(!file_exists(dirname(__DIR__, 1) . "/images/rpg/ui/itemFrame.png")) return $image;
        $fr = imagecreatefrompng(dirname(__DIR__, 1) . "/images/rpg/ui/itemFrame.png");
        $fr = imagescale($fr, 47, 47);
        imagecopy($image, $fr, ((28 + ( 49 * ( ( intval( $slot / 8 ) >= 1 ? intval( $slot / 8 ) : $slot ) - 1 ) ) ) ), ((346 + ( 49 * ( intval( $slot / 8 ) ) ) ) ), 0, 0, imagesx($fr), imagesy($fr)); // what the fuck
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
