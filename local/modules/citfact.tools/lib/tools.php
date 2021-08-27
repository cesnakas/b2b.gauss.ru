<?php

namespace Citfact\Tools;

class Tools
{

    private static $whiteListExtensions = [
        'pdf',
        'xslx',
        'xlsx',
        'png',
        'svg',
        'jpeg',
        'jpg',
        'doc',
        'docx',
        'gif',
        'xsl',
        'bmp',
        'mp4',
        'mp3',
        'wmv',
        'ldt',
        'ies',
        'pptx',
    ];

    private static $availableUploadDirs = [
        '/upload/download_prices_tmp/',
        '/upload/iblock/',
        '/upload/tech-doc/',
        '/upload/orders/docs/price_lists/',
    ];


    /**
     * @param $var
     * @param bool $stdin
     * @param bool $die
     * @param bool $all
     * @return mixed
     */
    public static function pre($var, $stdin = false, $die = false, $all = false)
    {
        global $USER;
        if ($USER->IsAdmin() || $all) {
            if ($stdin) {
                return print_r($var, $stdin);
            } ?>
            <pre><? print_r($var, $stdin) ?></pre>
            <?
        }
        if ($die) die;
    }

    public static function requestSpecialChars($requestData)
    {
        foreach ($requestData as &$value) {
            if (is_array($value)) {
                continue;
            }
            $value = htmlspecialcharsbx(trim($value));
        }
        unset($value);
        return $requestData;
    }

    /**
     * @param int $number
     * @param array $titles
     *
     * @param bool $onlyTitles
     * @return string word
     */
    public static function declension($number, $titles, $onlyTitles = false)
    {
        $cases = array(2, 0, 1, 1, 1, 2);
        $pref = $number . ' ';
        if ($onlyTitles === true) {
            $pref = '';
        }
        return $pref . $titles[($number % 100 > 4 && $number % 100 < 20) ? 2 : $cases[min($number % 10, 5)]];
    }


    /**
     * @param string $date_from
     * @param string $date_to
     * @return array
     */
    public static function datediff($date_from, $date_to)
    {
        $date_from = new \DateTime($date_from);
        $date_to = new \DateTime($date_to);
        $interval = $date_from->diff($date_to);
        $arReturn = array(
            'days' => $interval->days,
            'm' => $interval->m,
            'd' => $interval->d,
            'invert' => $interval->invert,
        );

        return $arReturn;
    }


    /**
     * Получаем список всех файлов картинок по указаному пути
     * @param string $path
     * @return array|null
     */
    public static function getImageFiles($path)
    {

        if (empty($path)) return null;
        $dir = array();
        $el = new \CFile();

        $dir_raw = scandir($path);
        foreach ($dir_raw as $item) {
            if ($item == '.' || $item == '..') continue;
            if (!$el->IsImage($item)) continue;
            $dir[] = $item;
        }

        return $dir;
    }


    /**
     * Приводим номер телефона к 11 цифрам
     * @param $phone
     * @param bool $addPrefix
     * @return bool|string
     */
    public static function formatPhone($phone, $addPrefix = true)
    {
        $phone = preg_replace('~\D~', '', $phone);
        $phone = preg_replace('/[^0-9]/', '', $phone);

        if (strlen($phone) == 0) {
            return false;
        }

        if ($addPrefix === true) {
            if (strlen($phone) == 10) {
                $phone = '7' . $phone;
            }
        }

        if (strlen($phone) == 11 && substr($phone, 0, 1) == 8) {
            $phone = substr_replace($phone, "7", 0, 1);
        }

        return $phone;
    }


    /**
     * @param $user_id
     * @return mixed
     */
    public static function getUserInfo($user_id)
    {
        $userData = \CUser::getList(($by = 'ID'), ($order = 'desc'), array('ID' => $user_id),
            array('SELECT' => array('UF_*'), 'FIELDS' => array(/*'ID', 'LOGIN', 'EMAIL'*/))
        )
            ->getNext(true, false);

        return $userData;
    }


    /**
     * Return first letter uppercase
     * @param $str
     * @return string
     */
    public static function my_mb_ucfirst($str)
    {
        $fc = mb_strtoupper(mb_substr($str, 0, 1));
        return $fc . mb_substr($str, 1);
    }


    /**
     * Скачиваем файл по указанному пути
     * @param string $filepath
     * @param string $filename
     */
    public static function file_force_download($filepath, $filename = '')
    {
		\setlocale(LC_ALL, 'ru_RU.UTF-8');

        $isFileInAvailableDir = false;

        foreach (self::$availableUploadDirs as $dir) {
            if (strpos($filepath, $dir) !== false) {
                $isFileInAvailableDir = true;
            }
        }

        $fileExtension = mb_strtolower(pathinfo($filepath, PATHINFO_EXTENSION));

        if (file_exists($filepath)
            && true === in_array($fileExtension, self::$whiteListExtensions)
            && true === $isFileInAvailableDir) {

            if (empty($filename)) {
                $filename = basename($filepath);
            }

            // сбрасываем буфер вывода PHP, чтобы избежать переполнения памяти выделенной под скрипт
            // если этого не сделать файл будет читаться в память полностью!

            if (ob_get_level()) {
                ob_end_clean();
            }
            // заставляем браузер показать окно сохранения файла
            header('Content-Description: File Transfer');
            header('Content-Type: application/octet-stream');
            header('Content-Disposition: attachment; filename=' . $filename);
            header('Content-Transfer-Encoding: binary');
            header('Expires: 0');
            header('Cache-Control: must-revalidate');
            header('Pragma: public');
            header('Content-Length: ' . filesize($filepath));
            // читаем файл и отправляем его пользователю
            readfile($filepath);
            exit;
        }
    }


    /**
     * Достаем значения из highload-инфоблоков
     * @param string $table
     * @param array $arXmlId
     * @param bool $getFullArray
     * @return array
     */
    public static function GetHighloadValueByXmlId($table, $arXmlId, $getFullArray = false)
    {
        $rsData = \Bitrix\Highloadblock\HighloadBlockTable::getList(array('filter' => array('TABLE_NAME' => $table)));
        // Если не нашли инфоблок с таким названием, то выводим ошибку (только для админа)
        if (!($arData = $rsData->fetch())) {
            global $USER;
            if ($USER->IsAdmin()) {
                //echo 'Highload-инфоблок не найден';
            }
        } else {
            $Entity = \Bitrix\Highloadblock\HighloadBlockTable::compileEntity($arData);
            $dataClass = $Entity->getDataClass();

            //Создадим объект - запрос
            $Query = new \Bitrix\Main\Entity\Query($dataClass::getEntity());

            //Зададим параметры запроса, любой параметр можно опустить
            $Query->setSelect(array('*'));
            $Query->setFilter(array('UF_XML_ID' => $arXmlId));
            //$Query->setOrder(array('UF_SORT' => 'ASC'));

            //Выполним запрос
            $result = $Query->exec();

            //Получаем результат по привычной схеме
            $result = new \CDBResult($result);
            $arValues = array();
            while ($row = $result->Fetch()) {
                if ($getFullArray === true) {
                    $arValues[$row['UF_XML_ID']] = $row;
                } else {
                    $arValues[$row['UF_XML_ID']] = $row['UF_NAME'];
                }
            }

            return $arValues;
        }
    }


    /**
     * Возвращает время в timestamp
     * @param $datetime
     * @return false|int
     */
    public static function getActionTime($datetime) {
        $arDate = explode(' ', $datetime);
        if (count($arDate) > 1) {
            return strtotime($datetime);
        }

        return strtotime($arDate[0] . ' 23:59:59');
    }


    public static function formatNumber($number)
    {
        return str_replace('.00', '', number_format($number, 2, '.', ' '));
    }
}