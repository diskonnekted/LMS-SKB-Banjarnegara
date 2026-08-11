<?php

namespace App\Helpers;

class ContentParser
{
    /**
     * Parse content to handle embeds and other formatting.
     *
     * @param  string  $content
     * @return string
     */
    public static function parse($content)
    {
        if (empty($content)) {
            return '';
        }

        // Convert [embed]url[/embed] to iframe
        $content = preg_replace_callback('/\[embed\](.*?)\[\/embed\]/i', function ($matches) {
            $url = trim($matches[1]);

            return self::generateEmbed($url);
        }, $content);

        return $content;
    }

    public static function parseLinks($text)
    {
        if (empty($text)) {
            return '';
        }

        // Match URLs starting with http:// or https://
        $pattern = '/(https?:\/\/[^\s\<>\"]+)/';

        return preg_replace($pattern, '<a href="$1" target="_blank" class="text-blue-600 hover:underline font-semibold">$1</a>', $text);
    }

    public static function parseFormatting($text)
    {
        if (empty($text)) {
            return '';
        }

        // Run standard HTML escaping first to ensure XSS safety
        $text = e($text);

        // Parse links
        $text = self::parseLinks($text);

        // Parse BBCode formatting tags
        $bbcode = [
            '/\[b\](.*?)\[\/b\]/is' => '<strong>$1</strong>',
            '/\[i\](.*?)\[\/i\]/is' => '<em>$1</em>',
            '/\[u\](.*?)\[\/u\]/is' => '<u>$1</u>',
            '/\[left\](.*?)\[\/left\]/is' => '<div style="text-align: left;">$1</div>',
            '/\[center\](.*?)\[\/center\]/is' => '<div style="text-align: center;">$1</div>',
            '/\[right\](.*?)\[\/right\]/is' => '<div style="text-align: right;">$1</div>',
        ];

        foreach ($bbcode as $pattern => $replacement) {
            $text = preg_replace($pattern, $replacement, $text);
        }

        return $text;
    }

    public static function excerpt($content, $limit = 150)
    {
        $text = self::toPlainText($content);

        return \Illuminate\Support\Str::limit($text, $limit);
    }

    public static function toPlainText($content)
    {
        if (empty($content)) {
            return '';
        }

        $content = self::parse($content);

        $text = strip_tags((string) $content);
        $text = html_entity_decode($text, ENT_QUOTES | ENT_HTML5, 'UTF-8');
        $text = html_entity_decode($text, ENT_QUOTES | ENT_HTML5, 'UTF-8');
        $text = str_replace(["\xC2\xA0", "\u{00A0}"], ' ', $text);
        $text = preg_replace('/\x{FFFD}/u', '', $text);
        $text = preg_replace('/[\x00-\x1F\x7F]/u', ' ', $text);
        $text = preg_replace('/\s+/u', ' ', $text);

        return trim($text);
    }

    private static function generateEmbed($url)
    {
        // Youtube
        if (preg_match('/(?:youtube\.com\/(?:[^\/]+\/.+\/|(?:v|e(?:mbed)?)\/|.*[?&]v=)|youtu\.be\/)([^"&?\/\s]{11})/i', $url, $matches)) {
            $videoId = $matches[1];

            return '<div class="w-full aspect-video my-4"><iframe src="https://www.youtube.com/embed/'.$videoId.'" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen class="w-full h-full rounded-lg"></iframe></div>';
        }

        // Return link if not supported
        return '<a href="'.$url.'" target="_blank" class="text-indigo-600 hover:underline break-words">'.$url.'</a>';
    }
}
