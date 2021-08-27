<?php
/**
 * CSS Minifier
 *
 * Please report bugs on https://github.com/matthiasmullie/minify/issues
 *
 * @author Matthias Mullie <minify@mullie.eu>
 * @copyright Copyright (c) 2012, Matthias Mullie. All rights reserved
 * @license MIT License
 */

namespace Citfact\Minify;

class MinifyJs
{
    const MODULE_ID = 'citfact.minify';

    /**
     * @throws \Bitrix\Main\ArgumentNullException
     */
    public static function OnAfterEpilog() {
        return;

        self::includeMinify();

        $arPathJs = [];
        $assets = \Bitrix\Main\Page\Asset::getInstance();
        $allJs = $assets->getJs();
        $arAllJs = explode('</script>', $allJs);
        foreach ($arAllJs as $k => $str) {
            $pos = strpos($str, 'src="/bitrix/cache/js/');
            $posReal = strpos($str, '/bitrix/cache/js/');
            if ($pos !== false) {
                $rest = substr($str, $posReal);

                $pos2 = strpos($rest, '?');
                $arPathJs[] = substr($rest, 0, $pos2);
            }
        }


        foreach ($arPathJs as $k => $sourcePath) {
            if (file_exists($_SERVER['DOCUMENT_ROOT'].$sourcePath)) {
                $minifier = new \MatthiasMullie\Minify\JS($_SERVER['DOCUMENT_ROOT'].$sourcePath);
                $jsMinify = $minifier->minify();
                if (!empty($jsMinify)) {
                    $assets->write($_SERVER['DOCUMENT_ROOT'].$sourcePath, $jsMinify, true);
                }
            }
        }
    }



    public static function includeMinify() {
        include_once __DIR__ . '/path-converter/src/ConverterInterface.php';
        include_once __DIR__ . '/path-converter/src/Converter.php';
        include_once __DIR__ . '/minify/src/Minify.php';
        include_once __DIR__ . '/minify/src/Exception.php';
        include_once __DIR__ . '/minify/src/JS.php';
    }
}
