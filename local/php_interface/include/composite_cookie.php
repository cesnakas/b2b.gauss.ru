<?
session_start();


$strError = '';
$arFiles = [
    '/local/modules/citfact.sitecore/lib/cataloghelper/price.php',
];
if (!empty($arFiles)) {
    foreach ($arFiles as $file) {
        if (file_exists($_SERVER['DOCUMENT_ROOT'] . $file)) {
            require_once($_SERVER['DOCUMENT_ROOT'] . $file);

        } else {
            $strError = 'not found ' . $file;
        }
    }
}

if ($strError) {
    return;
}



$params = array(
    'view' => array(
        'DEFAULT' => 'table',
    ),
    'auth' => array(
        'DEFAULT' => 'N',
    ),
    'price' => array(
        'DEFAULT' => \Citfact\Sitecore\CatalogHelper\Price::PRICE_ID_MAIN,
    )
);

$pageName = 'page';

$isBot = (
    strpos($_SERVER['HTTP_USER_AGENT'], 'YandexBot') !== false
    || strpos($_SERVER['HTTP_USER_AGENT'], 'YandexScreenshotBot') !== false
    || strpos($_SERVER['HTTP_USER_AGENT'], 'Googlebot') !== false
) ? 'Y' : 'N';

foreach ($params as $code => $info) {
    if (!empty($_GET[$code]) && !$info['SKIP_GET']) {
        $pageName .= '_' . $code . '_' . $_GET[$code];
    } elseif (!empty($_SESSION[$code])) {
        $pageName .= '_' . $code . '_' . $_SESSION[$code];
    } else {
        $pageName .= '_' . $code . '_' . $info['DEFAULT'];
    }
}

$pageName .= '_isBot_' . $isBot;

$_COOKIE['BITRIX_SM_PK'] = $pageName;
setcookie('BITRIX_SM_PK', $pageName, 0, '/');


//var_dump($pageName);


unset($strError);
unset($pageName);
unset($isBot);
unset($params);