<?

/* ********************
Скрипт производит рассылку последних 3-х созданных продуктов и акций.
Если между рассылками не было создано новых элементов, то отправка письма не производится.
Дата создания последнего отправленного элемента отслеживается в файле lastDatesCreateItem.txt.
********************** */

use Citfact\SiteCore\Core;
use Citfact\Sitecore\CatalogHelper;

define("STOP_STATISTICS", true);
define("NO_KEEP_STATISTIC", "Y");
define("NO_AGENT_STATISTIC", "Y");
define("DisableEventsCheck", true);
define("BX_SECURITY_SHOW_MESSAGE", true);

$_SERVER["DOCUMENT_ROOT"] = str_replace('/local/cron', '', __DIR__);

if ($_SERVER['HOSTNAME'] == 'testfact.ru') {
    $host = 'gaussb2b.testfact.ru';
} else {
    $host = 'b2b.gauss.ru';
}

require_once($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/prolog_before.php");

$core = Core::getInstance();

$maxElements = 3;

$arFields = [];
$lastCreateElement = [];
const EVENT_TYPE = 'SUBSCRIPTION_PROMOTION';

if (file_exists($_SERVER["DOCUMENT_ROOT"] . '/local/cron/lastDatesCreateItem.txt')) {
    $contentFile = file_get_contents($_SERVER["DOCUMENT_ROOT"] . '/local/cron/lastDatesCreateItem.txt');
    $lastCreateElement = json_decode($contentFile, true);
}

$arFilterCatalog = [
    'IBLOCK_ID' => $core->getIblockId($core::IBLOCK_CODE_CATALOG),
    'ACTIVE' => 'Y',
    '>QUANTITY' => 0,
    '!PREVIEW_PICTURE' => false,
    '!DETAIL_PICTURE' => false
];

if (!empty($lastCreateElement['PRODUCT'])) {
    $arFilterCatalog['>DATE_CREATE'] = $lastCreateElement['PRODUCT'];
}

$arSelectCatalog = ['ID', 'NAME', 'DATE_CREATE', 'PREVIEW_PICTURE', 'DETAIL_PAGE_URL', 'PROPERTY_CML2_ARTICLE', 'QUANTITY'];
$dbItem = \CIBlockElement::GetList(
    ['DATE_CREATE' => 'DESC'],
    $arFilterCatalog,
    false,
    ['nTopCount' => 3],
    $arSelectCatalog
);

$items = [];
$priceObject = new CatalogHelper\Price();
$priceFormat = new CatalogHelper\ElementRepository();

$i = 0;
while ($arItems = $dbItem->GetNext()) {
    if ($priceObject->getWithoutDiscountPrices($arItems['ID'])['PRICE'] == '0.00' || $priceObject->getWithoutDiscountPrices($arItems['ID'])['PRICE'] == false) {
        continue;
    }
    if ($i === 0) {
        $lastCreateElement['PRODUCT'] = $arItems['DATE_CREATE'];
        $i++;
    }

    if ($arItems['QUANTITY'] >= 1000) {
        $arItems['QUANTITY'] = 'много';
    } else {
        $arItems['QUANTITY'] = 'Свободный остаток: ' . $arItems['QUANTITY'] . 'шт.';
    }

    $arFileTmp = CFile::ResizeImageGet(
        $arItems['PREVIEW_PICTURE'],
        array("width" => 145, "height" => 145),
        BX_RESIZE_IMAGE_PROPORTIONAL,
        true
    );

    $items[$arItems['ID']]['PRICE'] = $priceObject->getWithoutDiscountPrices($arItems['ID'])['PRICE'];
    $items[$arItems['ID']]['PRICE'] = $priceFormat->formatPrice($items[$arItems['ID']]['PRICE']) . ' ₽';
    $items[$arItems['ID']]['NAME'] = mb_strimwidth($arItems['NAME'], 0, 22, '...');
    $items[$arItems['ID']]['ARTICLE'] = $arItems['PROPERTY_CML2_ARTICLE_VALUE'];
    $items[$arItems['ID']]['DETAIL_PAGE_URL'] = $arItems['DETAIL_PAGE_URL'];
    $items[$arItems['ID']]['PICTURE'] = $arFileTmp['src'];
    $items[$arItems['ID']]['QUANTITY'] = $arItems['QUANTITY'];
}

if (!empty($items)) {
    $i = 0;
    $arFields['NEWS_ITEMS'] .= "<td width=\"30\"></td>";
    foreach ($items as $elem) {
        $arFields['NEWS_ITEMS'] .= "
        <td style=\"width: 233px; min-width: 233px; vertical-align: top; padding: 0; border: 1px solid #E5E7EB; box-shadow: 0 6px 10px 0 #EEEEEE;\">
    <table align=\"center\" style=\"width: 100%; border-collapse: collapse;\">
        <tbody>
        <tr>
            <td colspan=\"2\" style=\"padding: 0; padding-bottom: 5px;\">
                <table style=\"width: 100%; border-collapse: collapse;\">
                    <tbody>
                    <tr>
                        <td style=\"width: 45px; vertical-align: top; padding: 0; padding-top: 20px;\">
                            <table style=\"width: 100%; border-collapse: collapse;\">
                                <tbody>
                                <tr>
                                    <td style=\"padding: 5px; background-color: #5BCEFF; color: #FFFFFF; font-size: 12px; font-weight: 500; text-align: center;\">
                                        NEW
                                    </td>
                                </tr>
                                </tbody>
                            </table>
                        </td>
                        <td style=\"width: 145px; height: 151.7px; padding: 10px 0 5px; text-align: center; vertical-align: middle;\">
                            <a href=\"https://{$host}{$elem['DETAIL_PAGE_URL']}\"><img style=\"max-width: 145px; max-height: 145px; width: auto; height: auto;  border:0 none; image-rendering: optimizeSpeed; image-rendering: -moz-crisp-edges; image-rendering: -o-crisp-edges;  image-rendering: -webkit-optimize-contrast; image-rendering: optimize-contrast;\"
                                 src=\"https://{$host}/{$elem['PICTURE']}\"
                                 alt=\"{$_SERVER['SERVER_NAME']}\"/></a>
                        </td>
                        <td style=\"width: 40px; padding: 0;\"></td>
                    </tr>
                    </tbody>
                </table>
            </td>
        </tr>
        <tr>
            <td colspan=\"2\" style=\"padding: 5px 10px; font-size: 13px; font-weight: 500; text-transform: uppercase;\">
                <b><a href=\"https://{$host}{$elem['DETAIL_PAGE_URL']}\" style=\"color: #232323; text-decoration: none;\" target=\"_blank\">{$elem['NAME']}</a></b>
            </td>
        </tr>
        <tr>
            <td style=\"width: 100px; padding: 5px 0 5px 10px; color: #7F8A9E; font-size: 12px;\">
                Наличие
            </td>
            <td style=\"padding: 5px 10px 5px 0; color: #40D24A; font-size: 12px;\">
                {$elem['QUANTITY']}
            </td>
        </tr>
        <tr>
            <td style=\"width: 100px; padding: 5px 0 5px 10px; color: #7F8A9E; font-size: 12px;\">
                Артикул
            </td>
            <td style=\"padding: 5px 10px 5px 0; height: 36px; color: #7F8A9E; font-size: 12px;\">
                {$elem['ARTICLE']}
            </td>
        </tr>
        <tr>
            <td colspan=\"2\" style=\"padding: 5px 10px; color: #232323; font-size: 16px; font-weight: 500;\">
                <b>{$elem['PRICE']}</b>
            </td>
        </tr>
        <tr>
            <td colspan=\"2\" style=\"padding: 5px 10px 10px;\">
                <a href=\"https://{$host}{$elem['DETAIL_PAGE_URL']}\" target=\"_blank\" style=\"display: block; padding: 10px; border: 1px solid #232323; color: #232323; font-size: 14px; font-weight: 500; text-align: center; text-decoration: none;\">
                    <b>Купить</b>
                </a>
            </td>
        </tr>
        </tbody>
    </table>
</td>";
        $i++;

        if ($maxElements - $i !== 0) {
            $arFields['NEWS_ITEMS'] .= "<td width=\"20\"></td>";
        }

    }
    if ($maxElements - $i !== 0) {
        for ($j = 0; $j < $maxElements - $i; $j++) {
            $arFields['NEWS_ITEMS'] .= "
        <td style=\"width: 233px; min-width: 233px; vertical-align: top; padding: 0; border: 1px solid #E5E7EB;\"></td>";
        }
    }
    $arFields['NEWS_ITEMS'] .= "<td width=\"30\"></td>";
}

$arFilterPromotion = [
    'IBLOCK_ID' => $core->getIblockId($core::IBLOCK_CODE_PROMOTIONS),
    'ACTIVE' => 'Y',
    '!PREVIEW_PICTURE' => false,
    '!DETAIL_PICTURE' => false,
    ['LOGIC' => 'OR', [
            '>=PROPERTY_DATE_TO' => date('Y-m-d'),
            '<PROPERTY_DATE_FROM' => date('Y-m-d'),
        ], [
            '>=PROPERTY_DATE_TO' => date('Y-m-d'),
            'PROPERTY_DATE_FROM' => false,
        ], [
            'PROPERTY_DATE_TO' => false,
            '<PROPERTY_DATE_FROM' => date('Y-m-d'),
        ], [
            'PROPERTY_DATE_TO' => false,
            'PROPERTY_DATE_FROM' => false,
        ],
    ],

];

if (!empty($lastCreateElement['PROMOTION'])) {
    $arFilterPromotion['>DATE_CREATE'] = $lastCreateElement['PROMOTION'];
}

$arSelectPromotion = ['ID', 'NAME', 'DATE_CREATE', 'PREVIEW_PICTURE', 'DETAIL_PAGE_URL', 'PROPERTY_DATE_FROM', 'PROPERTY_DATE_TO'];

$dbPromotions = \CIBlockElement::GetList(
    ['DATE_CREATE' => 'DESC'],
    $arFilterPromotion,
    false,
    ['nTopCount' => 3],
    $arSelectPromotion
);

$promotions = [];
$i = 0;
while ($arPromotions = $dbPromotions->GetNext()) {
    if ($i === 0) {
        $lastCreateElement['PROMOTION'] = $arPromotions['DATE_CREATE'];
        $i++;
    }

    $arFileTmp = CFile::ResizeImageGet(
        $arPromotions['PREVIEW_PICTURE'],
        array("width" => 233, "height" => 233),
        BX_RESIZE_IMAGE_EXACT,
        true
    );


    $promotions[$arPromotions['ID']]['NAME'] = $arPromotions['NAME'];

    if ($arPromotions['PROPERTY_DATE_FROM_VALUE'] != false && $arPromotions['PROPERTY_DATE_TO_VALUE'] != false) {
        $promotions[$arPromotions['ID']]['DATE_FROM'] = 'с ' . $arPromotions['PROPERTY_DATE_FROM_VALUE'];
        $promotions[$arPromotions['ID']]['DATE_TO'] = ' по ' . $arPromotions['PROPERTY_DATE_TO_VALUE'];
    } elseif ($arPromotions['PROPERTY_DATE_FROM_VALUE'] == false && $arPromotions['PROPERTY_DATE_TO_VALUE'] != false) {
        $promotions[$arPromotions['ID']]['DATE_TO'] = 'по ' . $arPromotions['PROPERTY_DATE_TO_VALUE'];
    } elseif ($arPromotions['PROPERTY_DATE_FROM_VALUE'] != false && $arPromotions['PROPERTY_DATE_TO_VALUE'] == false) {
        $promotions[$arPromotions['ID']]['DATE_TO'] = 'с ' . $arPromotions['PROPERTY_DATE_FROM_VALUE'];
    }

    $promotions[$arPromotions['ID']]['DETAIL_PAGE_URL'] = $arPromotions['DETAIL_PAGE_URL'];
    $promotions[$arPromotions['ID']]['PICTURE'] = $arFileTmp['src'];
}
if (!empty($promotions)) {
    $arFields['PROMOTIONS'] .= "<td width=\"30\"></td>";
        $i = 0;
    foreach ($promotions as $elem) {
        $arFields['PROMOTIONS'] .= "
    <td style=\"width: 233px; min-width: 233px; vertical-align: top; padding: 0; border: 1px solid #E5E7EB; border-top: 0; box-shadow: 0 6px 10px 0 #EEEEEE;\">
        <table align=\"center\" style=\"border-collapse: collapse;\">
            <tbody>
            <tr>
                <td colspan=\"2\" style=\"padding: 0;\">
                    <a href=\"https://{$host}{$elem['DETAIL_PAGE_URL']}\"><img style=\"width: 100%; height: auto; border:0 none; display:block; image-rendering: optimizeSpeed; image-rendering: -moz-crisp-edges; image-rendering: -o-crisp-edges;  image-rendering: -webkit-optimize-contrast; image-rendering: optimize-contrast;\"
                         src=\"https://{$host}/{$elem['PICTURE']}\"
                         alt=\"\"/></a>
                </td>
            </tr>
            <tr>
                <td colspan=\"2\" style=\"padding: 10px 10px 3px; font-size: 12px; font-weight: 500; line-height: 17px;\">
                    <a href=\"https://{$host}{$elem['DETAIL_PAGE_URL']}\" style=\"color: #232323; text-decoration: none;\" target=\"_blank\"><b>{$elem['NAME']}</b></a>
                </td>
            </tr>
            <tr>
                <td style=\"padding: 5px 10px 25px; font-size: 12px;\">
                    {$elem['DATE_FROM']}{$elem['DATE_TO']}
                </td>
                <td style=\"padding: 0; vertical-align: bottom; text-align: right;\">
                    <img style=\"vertical-align: bottom; border:0 none; image-rendering: optimizeSpeed; image-rendering: -moz-crisp-edges; image-rendering: -o-crisp-edges;  image-rendering: -webkit-optimize-contrast; image-rendering: optimize-contrast;\"
                         src=\"https://{$host}/local/templates/email-actions/img/arrow.png\"
                         alt=\"\"/>
                </td>
            </tr>
            </tbody>
        </table>
    </td>";
        $i++;

        if ($maxElements - $i !== 0) {
            $arFields['PROMOTIONS'] .= "<td width=\"20\"></td>";
        }
    }
    if ($maxElements - $i !== 0) {
        for ($j = 0; $j < $maxElements - $i; $j++) {
            $arFields['PROMOTIONS'] .= "
        <td style=\"width: 233px; min-width: 233px; vertical-align: top; padding: 0;\"></td>";
        }
    }

    $arFields['PROMOTIONS'] .= "<td width=\"30\"></td>";
}

if (!empty($arFields['NEWS_ITEMS']) && !empty($arFields['PROMOTIONS'])) {
    $userFilter = [
        '=UF_EMAIL_PROMOTIONS' => 1
    ];
    $dbUsers = CUser::GetList(($by="ID"), ($order="ASC"), $userFilter);

    $users = [];
    while($arUser = $dbUsers->Fetch()) {
        $users[$arUser['EMAIL']] = $arUser['NAME'];
    }

    $arFields['HOST'] = $host;

    $testsEmails = [];

    if (file_exists($_SERVER["DOCUMENT_ROOT"] . '/email_for_send.txt')) {
        $testsEmails = explode(',', file_get_contents($_SERVER["DOCUMENT_ROOT"] . '/email_for_send.txt'));
        foreach ($testsEmails as $k => $testEmail) {
            $testsEmails[$k] = trim($testEmail);
        }
    }

$cnt = 0;
    foreach ($users as $email => $userName) {
        $arFields['EMAIL'] = $email;
        if ($userName == '') {
            $arFields['USERNAME'] = 'Уважаемый пользователь';
        } else {
            $arFields['USERNAME'] = $userName;
        }
        if (!empty($testsEmails)) {
            if (in_array($arFields['EMAIL'], $testsEmails)) {
                CEvent::Send(
                    EVENT_TYPE,
                    SITE_ID,
                    $arFields
                );
            }
        } else {
            CEvent::Send(
                EVENT_TYPE,
                SITE_ID,
                $arFields
            );
        }
    }
    file_put_contents($_SERVER["DOCUMENT_ROOT"] . '/local/cron/lastDatesCreateItem.txt',
        json_encode($lastCreateElement));
}

