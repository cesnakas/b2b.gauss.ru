<?php

namespace Citfact\SiteCore\Pictures;

use Citfact\SiteCore\Core;
use \Gumlet\ImageResize;

class ResizeManager
{
    const PATH_RESIZE = 'upload/resize_image_custom';

    /**
     * @param $file
     * @param int $width
     * @param int $height
     * @param bool $quality
     * @return mixed|string
     * @throws \Gumlet\ImageResizeException
     */
    public static function resizeImageGet($file, int $width=635, int $height=635, $quality=false)
    {
        $fileId = 0;
        $filePath = '';
        if (!is_array($file) && intval($file) > 0) {
            $fileId = $file;
            $filePath = $_SERVER['DOCUMENT_ROOT'] . \CFile::GetPath($file);

        } elseif ($file['ID']) {
            $fileId = $file['ID'];
            $filePath = $_SERVER['DOCUMENT_ROOT'] . \CFile::GetPath($file['ID']);

        } else if ($file['SRC'] && file_exists($_SERVER['DOCUMENT_ROOT'] . $file['SRC'])) {
            $fileInfo = pathinfo($file['SRC']);
            $fileAr = \CFile::GetList([], [
                'SUBDIR' => str_replace('/upload/', '', $fileInfo['dirname']),
                'FILE_NAME' => $fileInfo['basename'],
            ])->Fetch();
            $fileId = $fileAr['ID'];
            $filePath = $_SERVER['DOCUMENT_ROOT'] . $file['SRC'];

        } else if ($file && file_exists($_SERVER['DOCUMENT_ROOT'] . $file)) {
            $fileInfo = pathinfo($file);
            $fileAr = \CFile::GetList([], [
                'SUBDIR' => str_replace('/upload/', '', $fileInfo['dirname']),
                'FILE_NAME' => $fileInfo['basename'],
            ])->Fetch();
            $fileId = $fileAr['ID'];
            $filePath = $_SERVER['DOCUMENT_ROOT'] . $file;
        }


        if (!file_exists($filePath) || !$fileId) {
            return '';
        }


        $fileInfo = pathinfo($filePath);
        /**
         * если PNG - совй ресайз, иначе битрикса
         */
        if ($fileInfo['extension'] == 'png') {

            /**
             * директория для ресайза
             */
            $resizeFileDir = implode('/', [
                $_SERVER['DOCUMENT_ROOT'],
                self::PATH_RESIZE,
                'iblock',
                $fileId
            ]);
            mkdir($resizeFileDir, 0775, true); // создаем директорию

            /**
             * имя файла ресайза
             */

            if($quality === false)
                $quality = intval(\COption::GetOptionString('main', 'image_resize_quality', '95'));
            if($quality <= 0 || $quality > 100)
                $quality = 95;

            $resizeFileName = implode('_', [
                $fileInfo['filename'],
                $width,
                $height,
                $quality,
            ]) . '.' . $fileInfo['extension'];



            $resizeFilePath = $resizeFileDir . '/' . $resizeFileName;
            if (file_exists($resizeFilePath)) {
                return str_replace($_SERVER['DOCUMENT_ROOT'], '', $resizeFilePath);
            }

            $image = new ImageResize($filePath);
            $image->resize($width, $height);
            $image->save($resizeFilePath, IMAGETYPE_PNG, $quality);

            if (file_exists($resizeFilePath)) {
                return str_replace($_SERVER['DOCUMENT_ROOT'], '', $resizeFilePath);

            } else {
                return str_replace($_SERVER['DOCUMENT_ROOT'], '', $filePath);
            }

        /**
         * если JPEG испоьзуем ресайз битрикса
         */
        } else {
            $pictureStub = \CFile::ResizeImageGet(
                $fileId,
                ['width' => $width, 'height' => $height],
                BX_RESIZE_IMAGE_PROPORTIONAL_ALT,
                true
            );
            return $pictureStub['src'];
        }
    }


    /**
     * @param $file
     * @param int $width
     * @param int $height
     * @param int $widthPreview
     * @param int $heightPreview
     * @param int $widthPreviewLow
     * @param int $heightPreviewLow
     * @return array
     * @throws \Gumlet\ImageResizeException
     */
    public static function getResizePictures($file, int $width, int $height, int $widthPreview = 0, int $heightPreview = 0,
                               int $widthPreviewLow = 0, int $heightPreviewLow = 0): array
    {

        $pictureStub = self::resizeImageGet(
            $file,
            70,
            103
        );

        $pictureMobile = self::resizeImageGet(
            $file,
            290,
            290
        );

        $pictureOrigin = self::resizeImageGet(
            $file,
            $width,
            $height
        );

        $picturePreview = self::resizeImageGet(
            $file,
            $widthPreview,
            $heightPreview
        );

        $pictureLowQuality = self::resizeImageGet(
            $file,
            $widthPreviewLow?:$width - 1,
            $heightPreviewLow?:$height - 1
        );

        $pictureScreen = self::resizeImageGet(
            $file,
            1900,
            1900
        );


        return [
            'SRC' => [
                'STUB' => $pictureStub ?: Core::NO_PHOTO_SRC,
                'MOBILE' => $pictureMobile ?: Core::NO_PHOTO_SRC,
                'SMALL' => $pictureOrigin ?: Core::NO_PHOTO_SRC,
                'LOW' => $pictureLowQuality ?: Core::NO_PHOTO_SRC,
                'ORIGIN' => $pictureOrigin ?: Core::NO_PHOTO_SRC,
                'PREVIEW' => $picturePreview ?: Core::NO_PHOTO_SRC,
                'SCREEN' => $pictureScreen ?: Core::NO_PHOTO_SRC,
            ],

            'EXT' => pathinfo($pictureStub, PATHINFO_EXTENSION),
        ];
    }
}