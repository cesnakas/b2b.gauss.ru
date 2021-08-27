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

class MinifyCss
{
    const MODULE_ID = 'citfact.minify';

    /**
     * @throws \Bitrix\Main\ArgumentNullException
     */
    public static function OnAfterEpilog() {
        self::includeMinify();

        $assets = \Bitrix\Main\Page\Asset::getInstance();

        $allCss = $assets->getCss();
        $arAllCss = explode('/>', $allCss);
        $arPathCss = [];
        foreach ($arAllCss as $str) {
            $pos = strpos($str, '/bitrix/cache/css/');
            if ($pos !== false) {
                $rest = substr($str, $pos);

                $pos2 = strpos($rest, '?');
                $arPathCss[] = substr($rest, 0, $pos2);
            }
        }

        foreach ($arPathCss as $sourcePath) {
            if (file_exists($_SERVER['DOCUMENT_ROOT'].$sourcePath)) {
                $mktime = 0;
                $mktimeReal = filemtime($_SERVER['DOCUMENT_ROOT'].$sourcePath);
                $mkTimeMinifyFile = $_SERVER['DOCUMENT_ROOT'].$sourcePath . '.mktime.txt';
                if (file_exists($mkTimeMinifyFile)) {
                    $mktime = (int)file_get_contents($mkTimeMinifyFile);
                }

                if ($mktime != $mktimeReal) {
                    $minifier = new \MatthiasMullie\Minify\CSS($_SERVER['DOCUMENT_ROOT'].$sourcePath);
                    $cssMinify = $minifier->minify();
                    if (!empty($cssMinify)) {
                        $assets->write($_SERVER['DOCUMENT_ROOT'].$sourcePath, $cssMinify, true);
                        file_put_contents($mkTimeMinifyFile, filemtime($_SERVER['DOCUMENT_ROOT'].$sourcePath));
                    }
                }
            }
        }
    }



    public static function includeMinify() {
        include_once __DIR__ . '/path-converter/src/ConverterInterface.php';
        include_once __DIR__ . '/path-converter/src/Converter.php';
        include_once __DIR__ . '/minify/src/Minify.php';
        include_once __DIR__ . '/minify/src/Exception.php';
        include_once __DIR__ . '/minify/src/CSS.php';
    }
}
