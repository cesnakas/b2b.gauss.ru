<?php
use Bitrix\Highloadblock\HighloadBlockTable;

class ListWaitHandler
{
    /**
     * Удаление из листов ожидания товаров, которые находятся там дольше 3 месяцев
     *
     * @return boolean - результат выполнения
     */
    public static function clean() {
        $hlblock = HighloadBlockTable::getList([
            'filter' => ['=NAME' => 'ListWait']
        ])->fetch();
        if ($hlblock) {
            $hlClassName = (HighloadBlockTable::compileEntity($hlblock))->getDataClass();

            $date = new DateTime('-3 months'); // текущая дата -3 месяца
            $formatDate = $date->format('d.m.Y');

            $rsData = $hlClassName::getList([
                'filter' => ['<UF_DATE_ADD' => $formatDate]
            ]);
             
            while ($arData = $rsData->Fetch()) {
                $hlClassName::delete($arData['ID']);
            } 
        }
    }
}
