<?
define('SITE_CLIENT_PATH', '/local/client');
define('SITE_CLIENT_FULL_PATH', $_SERVER['DOCUMENT_ROOT'] . SITE_CLIENT_PATH);

$curPage = $APPLICATION->GetCurPage(true);
define('IS_MAIN_PAGE', $curPage == '/index.php');

$pathNoPhoto = '/local/client/img/no_photo.png';
define('PATH_NO_PHOTO', $pathNoPhoto);

$pathDefaultSharePhoto = '/local/client/img/share.png';
define('PATH_SHARE', $pathDefaultSharePhoto);

define('IMAGE_PLACEHOLDER', 'data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAQAAAC1HAwCAAAAC0lEQVR42mNkYAAAAAYAAjCB0C8AAAAASUVORK5CYII=');

define('CALLBACK_FORM_ID', 3);