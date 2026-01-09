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
