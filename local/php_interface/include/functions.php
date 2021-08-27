<?php

/**
 * @param $var
 * @param bool $admin
 * @param bool $stdin
 * @param bool $die
 * @param bool $all
 * @return mixed
 */
function pre($var, $admin = false, $stdin = false, $die = false, $all = false)
{
    if (true === $admin) {
        global $USER;
        if ($USER->IsAdmin() || $all) {
            if ($stdin) {
                return print_r($var, $stdin);
            }

            dump($var);
        }
    } else {
        if ($stdin) {
            return print_r($var, $stdin);
        }

       dump($var);
    }

    if ($die) die;
}

if (!function_exists("printLogs"))
{
    /**
     * Функция логирования
     * по умолчанию печатает в "/local/var/logs/printLogs.log"
     * обазательно добавьте файл .htaccess deny from all
     *
     * @param array $arFields массив, который необходимо записать в лог
     * @param string $namePrintFileLog куда печатать. Можно передать название, тогда по умолчанию будет печатать в /local/var/logs/
     * @param bool $isInfo выводить информацию о файле запроса или просто текст
     */
    function printLogs($arFields, $namePrintFileLog = "printLogs.log", $isInfo = true)
    {
        $defaultFileDir = '/local/var/logs';
        $arDirFile = explode('/', $namePrintFileLog);
        if (count($arDirFile) > 1) {
            $fileName = array_pop($arDirFile);
            $dirFile = implode('/', $arDirFile);
        }else{
            $dirFile = $defaultFileDir;
            $fileName = $namePrintFileLog;
        }

        $arFileName = explode('.', $fileName);
        if (empty($arFileName[1])) {
            $fileName .= '.txt';
        }

        $trace = debug_backtrace();
        $date = date("Y-m-d H:i:s");
        $file = str_replace($_SERVER["DOCUMENT_ROOT"], '', $trace[0]['file']);
        $arInfo = array('file'=>$file,'line'=>$trace[0]['line'], 'date'=>$date);
        mkdir($_SERVER["DOCUMENT_ROOT"].$dirFile, 0775, true); // создаем директорию если ее нет, т.к. file_put_contents не делает этого
        if ($isInfo) {
            file_put_contents($_SERVER["DOCUMENT_ROOT"].'/'.$dirFile.'/'.$fileName, print_r(array("PRINT_R" => $arFields, "INFO" => $arInfo), true), FILE_APPEND);
        } else {
            file_put_contents($_SERVER["DOCUMENT_ROOT"].'/'.$dirFile.'/'.$fileName, print_r($arFields, true), FILE_APPEND);
        }
    }
}

function isDev()
{
    return (bool) strpos($_SERVER['SERVER_NAME'], 'testfact');
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
function getResizePictures($file, int $width, int $height, int $widthPreview = 0, int $heightPreview = 0,
    int $widthPreviewLow = 0, int $heightPreviewLow = 0): array
{
    return \Citfact\SiteCore\Pictures\ResizeManager::getResizePictures($file, $width, $height,  $widthPreview,$heightPreview, $widthPreviewLow, $heightPreviewLow);
}
