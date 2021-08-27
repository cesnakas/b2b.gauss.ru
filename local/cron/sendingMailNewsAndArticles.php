<?
/* ********************
Скрипт производит рассылку последних 3-х созданных новостей и статей.
Если между рассылками не было создано новых элементов, то отправка письма не производится.
Дата создания последнего отправленного элемента отслеживается в файле lastDatesCreateItem.txt.
********************** */
use Citfact\SiteCore\Core;

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
const EVENT_TYPE = 'SUBSCRIPTION_NEWSLETTER';

$months = [
    1 => 'Январь',
    2 => 'Февраль',
    3 => 'Март',
    4 => 'Апрель',
    5 => 'Май',
    6 => 'Июнь',
    7 => 'Июль',
    8 => 'Август',
    9 => 'Сентябрь',
    10 => 'Октябрь',
    11 => 'Ноябрь',
    12 => 'Декабрь',
];

if (file_exists($_SERVER["DOCUMENT_ROOT"] . '/local/cron/lastDatesCreateItem.txt')) {
    $contentFile = file_get_contents($_SERVER["DOCUMENT_ROOT"] . '/local/cron/lastDatesCreateItem.txt');
    $lastCreateElement = json_decode($contentFile, true);
}

$arFilterNews = [
    'IBLOCK_ID' => $core->getIblockId($core::IBLOCK_CODE_PRESS_CENTER_NEWS),
    'ACTIVE' => 'Y',
    '!PREVIEW_PICTURE' => false,
    ['LOGIC' => 'OR', [
            '>=DATE_ACTIVE_TO' => [false, ConvertTimeStamp(false, "FULL")],
            '<DATE_ACTIVE_FROM' => [false, ConvertTimeStamp(false, "FULL")],
        ], [
            '>=DATE_ACTIVE_TO' => [false, ConvertTimeStamp(false, "FULL")],
            'DATE_ACTIVE_FROM' => false,
        ], [
            'DATE_ACTIVE_TO' => false,
            '<DATE_ACTIVE_FROM' => [false, ConvertTimeStamp(false, "FULL")],
        ], [
            'DATE_ACTIVE_TO' => false,
            'DATE_ACTIVE_FROM' => false,
        ],
    ],
];

if (!empty($lastCreateElement['NEW'])) {
    $arFilterNews['>DATE_CREATE'] = $lastCreateElement['NEW'];
}

$arSelectNews = ['ID', 'NAME', 'DATE_CREATE', 'PREVIEW_PICTURE', 'DETAIL_PAGE_URL', 'PROPERTY_DATE', 'DATE_ACTIVE_TO', 'DATE_ACTIVE_FROM'];
$dbNews = \CIBlockElement::GetList(
    ['DATE_CREATE' => 'DESC'],
    $arFilterNews,
    false,
    ['nTopCount' => 3],
    $arSelectNews
);

$news = [];

$i = 0;
while ($arNews = $dbNews->GetNext()) {
    if ($i === 0) {
        $lastCreateElement['NEW'] = $arNews['DATE_CREATE'];
        $i++;
    }

    if (!empty($arNews['PROPERTY_DATE_VALUE'])) {
        $month = (int)date('m', strtotime($arNews['PROPERTY_DATE_VALUE']));
        $year = date('Y', strtotime($arNews['PROPERTY_DATE_VALUE']));
        $arNews['PROPERTY_DATE_VALUE'] = $months[$month] . ', ' . $year;
    }

    $arFileTmp = CFile::ResizeImageGet(
        $arNews['PREVIEW_PICTURE'],
        array("width" => 212, "height" => 212),
        BX_RESIZE_IMAGE_EXACT,
        true
    );

    $news[$arNews['ID']]['NAME'] = $arNews['NAME'];
    $news[$arNews['ID']]['DATE'] = $arNews['PROPERTY_DATE_VALUE'];
    $news[$arNews['ID']]['DETAIL_PAGE_URL'] = $arNews['DETAIL_PAGE_URL'];
    $news[$arNews['ID']]['PICTURE'] = $arFileTmp['src'];
}

if (!empty($news)) {
    $i = 0;
    $arFields['NEWS'] .= "<td width=\"30\"></td>";
    foreach ($news as $elem) {
        $arFields['NEWS'] .= "
            <td style=\"width: 233px; min-width: 233px; vertical-align: top; background-color: #E6E8EB; box-shadow: 0 6px 10px 0 #EEEEEE;\">
                <table align=\"center\">
                    <tbody>
                    <tr>
                        <td style=\"padding: 10px 10px 5px;\">
                            <a href=\"https://{$host}{$elem['DETAIL_PAGE_URL']}\"><img style=\"border:0 none; display:block; image-rendering: optimizeSpeed; image-rendering: -moz-crisp-edges; image-rendering: -o-crisp-edges;  image-rendering: -webkit-optimize-contrast; image-rendering: optimize-contrast;\"
                                 src=\"https://{$host}/{$elem['PICTURE']}\"
                                 alt=\"\"/></a>
                        </td>
                    </tr>
                    <tr>
                        <td style=\"padding: 5px 10px 3px; font-size: 13px;\">
                        {$elem['DATE']}
                    </td>
                    </tr>
                    <tr>
                        <td style=\"padding: 3px 10px 15px; font-size: 12px; font-weight: 500; line-height: 17px; text-transform: uppercase;\">
                            <a href=\"https://{$host}{$elem['DETAIL_PAGE_URL']}\" style=\"color: #0A0A0C; text-decoration: none;\" target=\"_blank\"><b>{$elem['NAME']}</b></a>
                        </td>
                    </tr>
                    </tbody>
                </table>
            </td>";
        $i++;

        if ($maxElements - $i !== 0) {
            $arFields['NEWS'] .= "<td width=\"20\"></td>";
        }
    }
    if ($maxElements - $i !== 0) {
        for ($j = 0; $j < $maxElements - $i; $j++) {
            $arFields['NEWS'] .= "
        <td style=\"width: 233px; min-width: 233px; vertical-align: top;\"></td>";
        }
    }
    $arFields['NEWS'] .= "<td width=\"30\"></td>";
}

$arFilterArticles = [
    'IBLOCK_ID' => $core->getIblockId($core::IBLOCK_CODE_PRESS_CENTER_ARTICLES),
    'ACTIVE' => 'Y',
    '!PREVIEW_PICTURE' => false,
    ['LOGIC' => 'OR', [
            '>=DATE_ACTIVE_TO' => [false, ConvertTimeStamp(false, "FULL")],
            '<DATE_ACTIVE_FROM' => [false, ConvertTimeStamp(false, "FULL")],
        ], [
            '>=DATE_ACTIVE_TO' => [false, ConvertTimeStamp(false, "FULL")],
            'DATE_ACTIVE_FROM' => false,
        ], [
            'DATE_ACTIVE_TO' => false,
            '<DATE_ACTIVE_FROM' => [false, ConvertTimeStamp(false, "FULL")],
        ], [
            'DATE_ACTIVE_TO' => false,
            'DATE_ACTIVE_FROM' => false,
        ]
    ],
];

if (!empty($lastCreateElement['ARTICLE'])) {
    $arFilterArticles['>DATE_CREATE'] = $lastCreateElement['ARTICLE'];
}

$arSelectArticles = ['ID', 'NAME', 'DATE_CREATE', 'PREVIEW_PICTURE', 'DETAIL_PAGE_URL', 'ACTIVE_FROM'];

$dbArticles = \CIBlockElement::GetList(
    ['DATE_CREATE' => 'DESC'],
    $arFilterArticles,
    false,
    ['nTopCount' => 3],
    $arSelectArticles
);

$articles = [];
$i = 0;
while ($arArticles = $dbArticles->GetNext()) {
    if ($i === 0) {
        $lastCreateElement['ARTICLE'] = $arArticles['DATE_CREATE'];
        $i++;
    }
    //смена формата даты
    if (!empty($arArticles['ACTIVE_FROM'])) {
        $month = (int)date('m', strtotime($arArticles['ACTIVE_FROM']));
        $year = date('Y', strtotime($arArticles['ACTIVE_FROM']));
        $arArticles['ACTIVE_FROM'] = $months[$month] . ', ' . $year;
    }

    $arFileTmp = CFile::ResizeImageGet(
        $arArticles['PREVIEW_PICTURE'],
        array("width" => 233, "height" => 233),
        BX_RESIZE_IMAGE_EXACT,
        true
    );

    $articles[$arArticles['ID']]['NAME'] = $arArticles['NAME'];
    $articles[$arArticles['ID']]['DETAIL_PAGE_URL'] = $arArticles['DETAIL_PAGE_URL'];
    $articles[$arArticles['ID']]['DATE'] = $arArticles['ACTIVE_FROM'];
    $articles[$arArticles['ID']]['PICTURE'] = $arFileTmp['src'];
}

if (!empty($articles)) {
    $i = 0;
    $arFields['ARTICLES'] .= "<td width=\"30\"></td>";
    foreach ($articles as $elem) {
        $arFields['ARTICLES'] .= "
            <td style=\"width: 233px; min-width: 233px; vertical-align: top;\">
                <table align=\"center\">
                    <tbody>
                    <tr>
                        <td style=\"padding: 0 0 3px;\">
                            <a href=\"https://{$host}{$elem['DETAIL_PAGE_URL']}\"><img style=\"border:0 none; display:block; image-rendering: optimizeSpeed; image-rendering: -moz-crisp-edges; image-rendering: -o-crisp-edges;  image-rendering: -webkit-optimize-contrast; image-rendering: optimize-contrast;\"
                                 src=\"https://{$host}/{$elem['PICTURE']}\"
                                 alt=\"\"/></a>
                        </td>
                    </tr>
                    <tr>
                        <td style=\"padding: 5px 0 3px; font-size: 14px; color: #7F8A9E;\">
                            {$elem['DATE']}
                        </td>
                    </tr>
                    <tr>
                        <td style=\"padding: 3px 0 15px; font-size: 12px; font-weight: 500; line-height: 17px; text-transform: uppercase;\">
                            <a href=\"https://{$host}{$elem['DETAIL_PAGE_URL']}\" style=\"color: #232323; text-decoration: none;\" target=\"_blank\"><b>{$elem['NAME']}</b></a>
                        </td>
                    </tr>
                    </tbody>
                </table>
            </td>";
        $i++;

        if ($maxElements - $i !== 0) {
            $arFields['ARTICLES'] .= "<td width=\"20\"></td>";
        }
    }
    if ($maxElements - $i !== 0) {
        for ($j = 0; $j < $maxElements - $i; $j++) {
            $arFields['ARTICLES'] .= "
        <td style=\"width: 233px; min-width: 233px; vertical-align: top;\"></td>";
        }
    }
    $arFields['ARTICLES'] .= "<td width=\"30\"></td>";
}

if (!empty($arFields['NEWS']) && !empty($arFields['ARTICLES'])) {
    $userFilter = [
        '=UF_EMAIL_NEWS' => 1
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