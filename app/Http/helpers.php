<?php

if (! function_exists('youTubeLinkToEmbed')) {

    /**
     * Re-format YouTube string for embedding
     *
     * @param string $link [the link string to parse]
     *
     * @return string
     */
    function youTubeLinkToEmbed($link)
    {
        // Ensure we take first substring of ampersand delimited url string
        $link = explode("&", $link)[0];

        if (strpos($link, 'watch')) {
            return str_replace("watch?v=", "embed/", $link);
        } elseif (strpos($link, 'youtu.be')) {
            return str_replace("youtu.be", "youtube.com/embed", $link);
        }
            
        return null;
    }
}
