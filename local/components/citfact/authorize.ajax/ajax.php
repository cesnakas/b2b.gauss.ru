<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");

use Bitrix\Main\Application;
use Bitrix\Main\Localization\Loc;



Loc::loadMessages(__FILE__);

global $USER;
$arReturn = array('errors' => array(), 'result' => array(), 'debug' => array());

try {
    $application = Application::getInstance();
    $request = $application->getContext()->getRequest();

    if ($request->isPost() && check_bitrix_sessid()) {
        $postData = array_map('strip_tags', $request->getPostList()->toArray());
//        $arReturn['debug']['post'] = $postData;

        $login = $_POST['LOGIN'];
        $pass = htmlspecialcharsbx(trim($_POST['USER_PASSWORD']));

        if (!check_email($login)) {
            //throw new Exception(Loc::getMessage('USER_INCORRECT_EMAIL'));
        }

        // Если пользователь существует, пытаемся его авторизовать
        $tableEntity = \Bitrix\Main\UserTable::getEntity();
        $query = new \Bitrix\Main\Entity\Query($tableEntity);

        $arFilter = array(
            array("LOGIN" => $login),
        );
        $query
            ->setSelect(array('ID', 'LOGIN', 'EMAIL', 'PERSONAL_PHONE'))
            ->setFilter($arFilter);
        $result = $query->exec();


        $userAuth = false;
        $userFound = false;
        // Перебираем людей
        while ($row = $result->fetch()) {
            $userFound = true;

            // Если удалось авторизовать, то выходим из цикла
//            if ($USER->Authorize(79)) {
            if ($USER->Login($row['LOGIN'], $pass, "Y") === true) {
                $userAuth = true;
                break;
            }
        }



        // Если нашли людей, но ни одного не авторизовали
        if ($userFound === true && $userAuth === false) {
            throw new Exception(Loc::getMessage('USER_NOT_AUTH'));
        }

        // Если не нашли людей
        if ($userFound === false) {
            throw new Exception(Loc::getMessage('USER_NOT_FOUND'));
        }

        if ($userAuth) {
            $arReturn['result'][] = Loc::getMessage('USER_AUTHORIZED');
            $_SESSION['auth'] = 'Y';
        }
    }


} catch (Exception $e) {
    $arReturn['errors'][] = $e->getMessage();
}


$strReturn = json_encode($arReturn);
echo $strReturn;

//LocalRedirect('/account/settings/');
require_once($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/epilog_after.php");