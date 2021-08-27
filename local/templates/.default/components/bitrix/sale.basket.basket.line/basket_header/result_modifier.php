<?if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED!==true) die();
/** @var CBitrixComponentTemplate $this */
/** @var array $arParams */
/** @var array $arResult */
use Bitrix\Main;
use Bitrix\Sale;

$result = Sale\Internals\BasketTable::getList(array(
    'filter' => array(
        'FUSER_ID' => Sale\Fuser::getId(),
        'ORDER_ID' => null,
        'LID' => SITE_ID,
        'CAN_BUY' => 'Y',
    ),
    'select' => array('BASKET_COUNT'),
    'runtime' => array(
        new \Bitrix\Main\Entity\ExpressionField('BASKET_COUNT', 'COUNT(*)'),
    )
))->fetch();

$arResult['NUM_PRODUCTS'] = $result['BASKET_COUNT'];