<?php

namespace App\Helpers;

class ContentParser
{
    /**
     * Parse content to handle embeds and other formatting.
     *
     * @param string $content
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

    private static function generateEmbed($url)
    {
        // Youtube
        if (preg_match('/(?:youtube\.com\/(?:[^\/]+\/.+\/|(?:v|e(?:mbed)?)\/|.*[?&]v=)|youtu\.be\/)([^"&?\/\s]{11})/i', $url, $matches)) {
            $videoId = $matches[1];
            return '<div class="w-full aspect-video my-4"><iframe src="https://www.youtube.com/embed/' . $videoId . '" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen class="w-full h-full rounded-lg"></iframe></div>';
        }

        // Return link if not supported
        return '<a href="' . $url . '" target="_blank" class="text-indigo-600 hover:underline break-words">' . $url . '</a>';
    }
}
