<?php

namespace Citfact\ImportHelper;

use Bitrix\Main\Diag\Debug;


class ImportHelper{

    public function readPostData(){
        global $HTTP_RAW_POST_DATA;
        $data = false;

        if (function_exists("file_get_contents")) {
            $data = file_get_contents("php://input");
        }
        elseif (isset($HTTP_RAW_POST_DATA))
            $data = &$HTTP_RAW_POST_DATA;

        return $data;
    }


    /**
     * @param $data
     * @param $filepath
     * @return array|int
     */
    public function saveDataToFile($data, $filepath){
        $result = array(
            'result' => false,
            'errors' => ''
        );

        $DATA_LEN = defined("BX_UTF") ? mb_strlen($data, 'latin1') : strlen($data);

        if (isset($data) && $data !== false) {
            CheckDirPath($filepath);
            if ($fp = fopen($filepath, "ab")) {
                $result = fwrite($fp, $data);
                if ($result === $DATA_LEN) {
                    $result['result'] = 'Архив сохранен';
                } else {
                    $result['result'] = 'Ошибка записи файла';
                }
            } else {
                $result['result'] = 'Ошибка открытия файла';
            }
        } else {
            $result['result'] = 'Ошибка чтения HTTP: пустые данные';
        }

        return $result;
    }


    public function unzipFile($filepath){
        $resultUnzip = \CIBlockXMLFile::UnZip($filepath);
        return $resultUnzip;
    }
}
