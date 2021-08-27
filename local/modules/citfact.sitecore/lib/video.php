<?php

namespace Citfact\Sitecore;


class Video
{
    /**
     * @param $code
     * @return false|string
     */
    public function getLengthByCode($code)
    {
        if (!$code) {
            return '';
        }
        $content = file_get_contents('http://youtube.com/get_video_info?video_id=' . $code);
        parse_str($content, $response);
        if ($response['length_seconds']) {
            return gmdate("H:i:s", $response['length_seconds']);
        }
        return '';
    }

    /**
     * @param $url string
     * @param $width number
     * @param $height number
     * @return string
     */

    public function convertUrlToIframe($url, $width = 560, $height = 315)
    {
        $convertUrl = self::getYoutubeId($url);
        $iframeUrl = "<iframe width='".$width."' height='".$height."' src='https://www.youtube.com/embed/". $convertUrl. "' title='YouTube video player' frameborder='0' allow='accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture' allowfullscreen></iframe>";
        return $iframeUrl;
    }

    public function getYoutubeId($url)
    {
        $shortUrlRegex = '/youtu.be\/([a-zA-Z0-9_-]+)\??/i';
        $longUrlRegex = '/youtube.com\/((?:embed)|(?:watch))((?:\?v\=)|(?:\/))([a-zA-Z0-9_-]+)/i';

        if (preg_match($longUrlRegex, $url, $matches)) {
            $youtube_id = $matches[count($matches) - 1];
        }

        if (preg_match($shortUrlRegex, $url, $matches)) {
            $youtube_id = $matches[count($matches) - 1];
        }
        return  $youtube_id ;
    }

    /**
     * @param $url
     * @return mixed|string
     */
    public function getCodeByUrl($url)
    {
        if (!$url) {
            return '';
        }
        $arQuery = array();
        parse_str(parse_url($url, PHP_URL_QUERY), $arQuery);
        return $arQuery['v'];
    }

    /**
     * @param $url
     * @return mixed|string
     */
    public static function getEmbedUrlByShareUrl($url)
    {
        if (!$url) {
            return '';
        }

        $code = self::getCode($url);
        return 'https://www.youtube.com/embed/' . $code;
    }

    /**
     * @param $url
     * @return mixed|string
     */
    public static function getFrameUrlByShareUrl($url)
    {
        if (!$url) {
            return '';
        }

        $code = self::getCode($url);
        return 'http://www.youtube.com/watch?v=' . $code;
    }

    /**
     * @param $url
     * @return mixed|string
     */
    public static function getCode($url)
    {
        if (!$url) {
            return '';
        }

        $ar =  explode('/', $url);
        $code = (string)end($ar);

        $arCode = explode('v=', $code);
        if (count($arCode) > 1) {
            $code = $arCode[1];
        }


        return $code;
    }

    /**
     * @param $url
     * @return mixed|string
     */
    public static function getImgYoutube($url)
    {
        if (!$url) {
            return '';
        }

        $code = self::getCode($url);
        return 'http://i3.ytimg.com/vi/'.$code.'/hqdefault.jpg';
    }
}