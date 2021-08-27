<?php

use \Bitrix\Main\Loader;

class ImagesLoader
{
    private $iblockId;
    private $zipFilename;
    private $workDir;

    private $absZipFilePath;

    private $strStatus = '';
    private $strLog;
    private $countUpdated = 0;

    public $errorsString = '';

    private $el;


    public function __construct($iblockId, $zipFilename, $workDir)
    {
        Loader::includeModule('iblock');

        if (!$iblockId){
            throw new \Exception('Incorrect iblockId');
        }
        if (!$zipFilename){
            throw new \Exception('Incorrect zip filename');
        }
        if (!$workDir){
            throw new \Exception('Incorrect workdir');
        }

        $this->setIblockId($iblockId);
        $this->setZipFilename($zipFilename);
        $this->setWorkDir($workDir);

        $this->absZipFilePath = $_SERVER['DOCUMENT_ROOT'].'/'.$this->zipFilename;
        $this->absWorkDir = $_SERVER['DOCUMENT_ROOT'].'/'.$this->workDir;
        $this->absUnpackDir = $this->absWorkDir.'/unpack';

        $this->strLog = '';

        $this->el = new CIBlockElement;
    }


    private function unzipFile($filepath){
        $this->addToLog('Распаковываем файл '.$filepath);

        $resultUnpack = false;

        $zip = new ZipArchive;
        if ($zip->open($this->absZipFilePath) === TRUE) {
            $resultUnpack = $zip->extractTo($this->absUnpackDir);

            if (!$resultUnpack) {
                $this->addToLog('Ошибка распаковки архива');
            }

            $zip->close();

        } else {
            $this->addToLog('Ошибка открытия архива');
        }

        return $resultUnpack;
    }


    private function getDirTree($dir){
        if ($dir == '') return false;

        $files = array();
        $handle = opendir($dir);
        if ($handle !== false) {
            while (false !== ($item = readdir($handle))) {
                if (is_file("$dir/$item")) {
                    $files[$dir][] = "$dir/$item";
                }
                elseif (is_dir("$dir/$item") && ($item != ".") && ($item != "..")){
                    $files = array_merge($files, $this->getDirTree("$dir/$item"));
                }
            }
            closedir($handle);
        }
        return $files;
    }


    public function load(){
        $this->addToLog('Запущен импорт изображений '.date('Y-m-d H-i-s'));
        \Bitrix\Main\Diag\Debug::startTimeLabel('test');

        //$this->unzipFile($this->absZipFilePath);
        //$arDirs = $this->getDirTree($this->absUnpackDir);

        $arDirs = $this->getDirTree($this->absWorkDir);

        $this->updateElements($arDirs);

        \Bitrix\Main\Diag\Debug::endTimeLabel('test');
        $arLabels = \Bitrix\Main\Diag\Debug::getTimeLabels();
        $this->addToLog('Импорт завершен за '.$arLabels['test']['time']);
        $this->saveLog();

        $this->addToStatus('Обновлено элементов: ' . $this->countUpdated);
        $this->showStatus();
    }


    private function updateElements($arDirs){
        if (empty($arDirs)){
            $this->addToLog('Пустой массив файлов');
        }

        @set_time_limit(0);

        foreach ($arDirs as $arDir){
            foreach ($arDir as $filePath){
                if (!file_exists($filePath)){
                    $this->addToLog('Файл не найден '.$filePath);
                    continue;
                }
                else{
                    $arFileName = explode('_', basename($filePath));

                    if (empty($arFileName[0])){
                        continue;
                    }
                    else{
                        $articul = $arFileName[0];

                        $num = IntVal(substr($arFileName[1], 0, strpos($arFileName[1],".")));

                        // Ищем элемент ИБ с данным артикулом
                        $arOrder = array("ID" => "ASC");
                        $arFilter = array('IBLOCK_ID' => $this->iblockId, 'PROPERTY_CML2_ARTICLE' => $articul);
                        $arSelectFields = array("ID","PROPERTY_MORE_PHOTO");
                        $rsElements = CIBlockElement::GetList($arOrder, $arFilter, FALSE, FALSE, $arSelectFields);
                        if($arElement = $rsElements->GetNext())
                        {
                            if ($arElement['ID'] == ''){
                                $this->addToLog('Пустой ID элемента');
                                continue;
                            }

                            if ($num==1) {
                                $arFile = CFile::MakeFileArray($filePath);
                                $arFile2 = CFile::MakeFileArray($filePath);

                                $res = $this->el->Update(
                                    $arElement['ID'],
                                    array(
                                        'PREVIEW_PICTURE' => $arFile,
                                        'DETAIL_PICTURE' => $arFile2,
                                    )
                                );

                                if ($res === true) {
                                    $this->countUpdated++;
                                    $this->addToLog('Обновлен элемент ID = ' . $arElement['ID']);
                                    unlink($filePath);
                                } else {
                                    $this->addToLog('Ошибка обновления элемента ID =  ' . $arElement['ID'] .  ' filename= ' . $filePath . ' ' . $this->el->LAST_ERROR);
                                }
                            }
                            elseif($num>1){
                                //блок добавления доп фоток и проверки на существование уже загруженных
                                $arNames= Array();
                                $arNames2= Array();
                                $md5 = md5_file($filePath);

                                $arFilter = array('IBLOCK_ID' => $this->iblockId, 'PROPERTY_CML2_ARTICLE' => $articul);
                                $arSelectFields = array("ID","PROPERTY_MORE_PHOTO");
                                $rsElements2 = CIBlockElement::GetList($arOrder, $arFilter, FALSE, FALSE, $arSelectFields);

                                while ($arElement2 = $rsElements2->GetNext()){
                                    $arNames[] = CFile::GetFileArray($arElement2["PROPERTY_MORE_PHOTO_VALUE"])["ORIGINAL_NAME"];
                                    $arNames2[CFile::GetFileArray($arElement2["PROPERTY_MORE_PHOTO_VALUE"])["ORIGINAL_NAME"]] =
                                        Array(
                                            "ID" =>$arElement2["PROPERTY_MORE_PHOTO_VALUE_ID"],
                                            "PROPERTY_MORE_PHOTO_VALUE" =>$arElement2["PROPERTY_MORE_PHOTO_VALUE"],
                                            "DESCRIPTION" =>CFile::GetFileArray($arElement2["PROPERTY_MORE_PHOTO_VALUE"])["DESCRIPTION"]
                                        );
                                    //в DESCRIPTION храним md5 от файла
                                }

                                if(in_array(basename($filePath),$arNames)){
                                    //уже существует картинка в доп массиве с таким именем
                                    //сравним md5 файлов
                                    //потом удаляем
                                    //потом добавляем файл

                                    if($md5<>$arNames2[basename($filePath)]["DESCRIPTION"]) {

                                        $arFile_del["del"] = "Y";
                                        $arFile_del["MODULE_ID"] = "iblock";
                                        CIBlockElement::SetPropertyValueCode($arElement['ID'], "MORE_PHOTO", Array ($arNames2[basename($filePath)]["ID"] => Array("VALUE"=>$arFile_del)));
                                        CFile::Delete($arNames2[basename($filePath)]["PROPERTY_MORE_PHOTO_VALUE"]);


                                        $arFile = array(
                                            $arNames2[basename($filePath)]["ID"] => array("VALUE" =>
                                                CFile::MakeFileArray($filePath),"DESCRIPTION"=>$md5,"MODULE_ID" => "iblock"
                                            )
                                        );

                                        $res = CIBlockElement::SetPropertyValueCode(
                                            $arElement['ID'],
                                            "MORE_PHOTO",
                                            $arFile
                                        );

                                        if ($res === true) {
                                            $this->countUpdated++;
                                            $this->addToLog('Обновлен элемент ID = ' . $arElement['ID']);
                                            unlink($filePath);
                                        } else {
                                            $this->addToLog('Ошибка обновления элемента ID =  ' . $arElement['ID'] . ' filename= ' . $filePath . ' ' . $this->el->LAST_ERROR);
                                        }
                                    }
                                    else{
                                        unlink($filePath);
                                        $this->addToLog('md5 совпал = ' . $filePath);
                                    }
                                }
                                else {
                                    $arFile = array(
                                        n0 => array("VALUE" =>
                                            CFile::MakeFileArray($filePath),"DESCRIPTION"=>$md5,"MODULE_ID" => "iblock"
                                        )
                                    );
                                    $res = CIBlockElement::SetPropertyValueCode($arElement['ID'], "MORE_PHOTO", $arFile);
                                    if ($res === true) {
                                        $this->countUpdated++;
                                        $this->addToLog('Обновлен элемент ID = ' . $arElement['ID']);
                                        unlink($filePath);
                                    } else {
                                        $this->addToLog('Ошибка обновления элемента ID =  ' . $arElement['ID'] . ' filename= ' . $filePath . ' ' . $this->el->LAST_ERROR);
                                    }
                                }
                            }
                            else{
                                $this->addToLog('Ошибка в имени файла ? = ' . $filePath);
                            }
                        }
                    }
                }
            }
        }
    }


    private function addToStatus($str){
        $this->strStatus .= (string)$str."\n";
    }

    private function showStatus(){
        echo $this->strStatus ."<br/><br/>";
    }

    private function addToLog($str){
        $this->strLog .= (string)$str."\n";
    }

    private function saveLog(){
        \Bitrix\Main\Diag\Debug::writeToFile($this->strLog, "", $this->workDir."/import_images_".date('Y-m-d_H-i-s').".log");
    }

    /**
     * @param mixed $iblockId
     */
    private function setIblockId($iblockId)
    {
        $this->iblockId = (int)$iblockId;
    }

    /**
     * @param mixed $zipFilename
     */
    private function setZipFilename($zipFilename)
    {
        $this->zipFilename = $zipFilename;
    }

    /**
     * @param mixed $workDir
     */
    private function setWorkDir($workDir)
    {
        $this->workDir = $workDir;
    }
}