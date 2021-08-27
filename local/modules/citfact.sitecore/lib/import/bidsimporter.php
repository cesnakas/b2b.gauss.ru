<?php

namespace Citfact\Sitecore\Import;

//use \Bitrix\Main\Loader;
use Bitrix\Main\Diag\Debug;
use Bitrix\Main\Entity\Query as EntityQuery;
use Bitrix\Main\UserTable;
use Citfact\Tools\HLBlock;
use Citfact\Tools\Logger\Logger;


class BidsImporter
{
    private $logger;
    private $filePath;
    private $arBids = [];
    private $bidsMap = [];
    private $countUpdated = 0;


    public function __construct($filePath)
    {
        if (!file_exists($filePath) || filesize($filePath) == 0){
            throw new \Exception('File is not exists or file is empty');
        }

        $this->filePath = $filePath;

        $this->logger = new Logger();
        $this->logger->setLogPath('/local/var/logs');
        $this->logger->setLogName("exchange_bids_".date('Y-m-d'));
    }


    public function run(){
        $this->logger->addToLog('Запущен обмен заявками '.date('Y-m-d H-i-s'));
        Debug::startTimeLabel('exchange_bids');

        $this->readFile();

        if (!empty($this->arBids)){
            $this->updateBids();
        }

        Debug::endTimeLabel('exchange_bids');
        $arLabels = Debug::getTimeLabels();
        $this->logger->addToLog('Обмен завершен за '.$arLabels['exchange_bids']['time']);

        // TODO: добавление ошибок в лог + вывод статуса failure и ошибок
        $this->logger->addToStatus("success\n");
        $this->logger->addToStatus('Обновлено заявок: ' . $this->countUpdated . "\n");
        $this->logger->showStatus();
    }


    /**
     * @throws \Exception
     * @internal param $filePath
     */
    private function readFile()
    {
        $reader = new \XMLReader();
        $filePath = $this->filePath;

        if (!$reader->open($filePath)) {
            $this->logger->addToLog('Ошибка открытия XML');
            throw new \Exception('Ошибка открытия XML '.$filePath);
        }

        // move to the <w:body> node
        //while ($reader->read() && $reader->name !== 'w:body');

        $arBid = array();
        while ($reader->read()) {
            //var_dump($reader->name);
            //var_dump($reader->nodeType);
            //var_dump($reader->value);

            // <BID>
            if ($reader->nodeType == \XMLREADER::ELEMENT && $reader->name == 'BID'){
                $arBid = array();
            }

            // Поля заявки
            if ($reader->nodeType == \XMLREADER::ELEMENT && $reader->name != 'BID'){
                $nodeName = $reader->name;
            }
            if ($reader->nodeType == \XMLREADER::TEXT){
                $arBid[$nodeName] = $reader->value;
            }

            // </BID>
            if ($reader->nodeType == \XMLREADER::END_ELEMENT && $reader->name == 'BID'){
                $this->arBids[] = $arBid;
            }
        }

        $reader->close();
    }


    private function updateBids()
    {
        $this->setBidsMap();

        // 1 - Сформирована
        // 2 - В обработке у менеджера
        // 3 - Обработана
        // 4 - Отменена

        $hlBlock = new HLBlock();
        $bidsEntity = $hlBlock->getHlEntityByName($hlBlock::HL_NAME_BIDS);

        foreach ($this->arBids as $arBid) {
            // Если нашли заявку, то обновляем статус
            if (array_search($arBid['ID'], $this->bidsMap) !== false) {
                $bidsEntity::update(
                    $arBid['ID'],
                    [
                        'UF_STATUS' => $arBid['STATUS'],
                        'UF_NEED_EXPORT' => 0,
                    ]
                );
                $this->logger->addToLog('Bid is updated. ID = ' . $arBid['ID']);
                $this->countUpdated++;
            }
        }
    }


    private function setBidsMap()
    {
        $arIds = [];
        foreach ($this->arBids as $arBid){
            $arIds[] = $arBid['ID'];
        }

        if (!empty($arIds)){
            $hlBlock = new HLBlock();
            $bidsEntity = $hlBlock->getHlEntityByName($hlBlock::HL_NAME_BIDS);
            $filter = [
                'ID' => $arIds,
            ];
            $res = $bidsEntity::getList([
                'order' => ['ID' => 'DESC'],
                'filter' => $filter,
            ]);
            while ($bid = $res->fetch()) {
                $this->bidsMap[] = $bid['ID'];
            }
        }
    }
}