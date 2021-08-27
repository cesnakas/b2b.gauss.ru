<?php

namespace Citfact\Sitecore\Favorites;

use Bitrix\Main\Entity;

class FavoritesTable extends Entity\DataManager
{
    public static function getTableName()
    {
        return 'citfact_favorites';
    }

    /**
     * @return array
     */
    public static function getMap()
    {
        return array(
            new Entity\IntegerField('id', array(
                'primary' => true,
                'autocomplete' => true,
            )),
            new Entity\IntegerField('user', array(
                'required' => true,
            )),
            new Entity\IntegerField('item_id', array(
                'required' => true,
            )),
            new Entity\IntegerField('item_count', array(
                'required' => true,
            )),
        );
    }
}

/*
use Citfact\Sitecore\Favorites\FavoritesTable;
$query = FavoritesTable::getEntity()->compileDbTableStructureDump();
global $DB;
$DB->Query($query[0]);
*/

class Favorites
{
    /**
     * Добавляем товар в избранное. Как id пользователя используем id корзины.
     * @param $itemId
     * @param $quantity
     * @return array
     * @throws \Bitrix\Main\ArgumentException
     * @throws \Bitrix\Main\ObjectPropertyException
     * @throws \Bitrix\Main\SystemException
     */
    public static function addToFavorites($itemId, $quantity) {
        /*global $USER;
        $userId = $USER->GetID();*/
        $cBasket = new \CSaleBasket();
        $userId = $cBasket->GetBasketUserID();

        // Ищем элемент в списке избранного текущего пользователя
        $arFavorites = FavoritesTable::getList([
            'filter' => ['user' => $userId, 'item_id' => (int)$itemId],
            'select' => ['*'],
        ])->fetch();

        // Если нашли запись, то удаляем её
        if (!empty($arFavorites)){
            $event = 'REMOVE';
            $result = FavoritesTable::delete($arFavorites['id']);
            if ($result->isSuccess()) {
                $addResult = [
                    'STATUS' => 'OK',
                    'MESSAGE' => 'CATALOG_SUCCESSFUL_DELETE_FROM_FAVORITES',
                    'EVENT' => $event,
                ];
            } else {
                $addResult = [
                    'STATUS' => 'ERROR',
                    'MESSAGE' => empty($result->getErrorMessages()) ?: $result->getErrorMessages(),
                ];
            }
        }
        // Иначе добавляем запись в таблицу избранного
        else{
            $event = 'ADD';
            $result = FavoritesTable::add([
                'user' => $userId,
                'item_id' => (int)$itemId,
                'item_count' => (int)$quantity,
            ]);
            if ($result->isSuccess()) {
                $addResult = [
                    'STATUS' => 'OK',
                    'MESSAGE' => 'CATALOG_SUCCESSFUL_ADD_TO_FAVORITES',
                    'EVENT' => $event,
                ];
            } else {
                $addResult = [
                    'STATUS' => 'ERROR',
                    'MESSAGE' => empty($result->getErrorMessages()) ?: $result->getErrorMessages(),
                ];
            }
        }

        // Считаем количество записей в избранном для текущего пользователя
        $arRes = FavoritesTable::getList([
            'filter' => ['user' => $userId],
            'select' => ['id'],
        ])->fetchAll();
        $addResult['COUNT'] = count($arRes);

        return $addResult;
    }


    /**
     * Получаем товары в избранном для нужного пользователя.
     * Если id пользователя не указан, то для текущего. Как id пользователя используем id корзины.
     * @param $userId
     * @return array
     * @throws \Bitrix\Main\ArgumentException
     * @throws \Bitrix\Main\ObjectPropertyException
     * @throws \Bitrix\Main\SystemException
     */
    public static function getForUser($userId = 0){
        if (!$userId){
            /*global $USER;
            $userId = $USER->GetID();*/
            $cBasket = new \CSaleBasket();
            $userId = $cBasket->GetBasketUserID();
        }

        $res = FavoritesTable::getList([
            'filter' => ['user' => (int)$userId],
            'select' => ['*'],
        ]);
        $arItems = [];
        while($arRes = $res->fetch()){
            $arItems[$arRes['item_id']] = $arRes;
        }

        return $arItems;
    }
}
