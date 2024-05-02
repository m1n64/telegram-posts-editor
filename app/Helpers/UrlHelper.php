<?php
declare(strict_types=1);

namespace App\Helpers;

class UrlHelper
{
    /**
     * @param $string
     * @return string
     */
    public function encodeURIComponent($string): string
    {
        $specialChars = '#-+=(){}.!';

        $encodedString = '';
        for ($i = 0; $i < strlen($string); $i++) {
            $char = $string[$i];
            if (in_array($char, str_split($specialChars))) {
                $encodedString .= '\\' . $char;
            } else {
                $encodedString .= $char;
            }
        }
        return $encodedString;
    }

    /**
     * @param $text
     * @return array|string
     */
    public function escapeMarkdownV2($text): array|string
    {
        $markdownSpecialChars = ['_', '*', '[', ']', '(', ')', '~', '|', '\\', '`'];
        $escapedChars = ['\\_', '\\*', '\\[', '\\]', '\\(', '\\)', '\\~', '\\|', '\\\\', '\\`'];

        return str_replace($markdownSpecialChars, $escapedChars, $text);
    }
}
