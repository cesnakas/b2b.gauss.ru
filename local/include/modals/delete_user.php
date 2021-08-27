<?php
define("STOP_STATISTICS", true);
define("NO_KEEP_STATISTIC", "Y");
define("NO_AGENT_STATISTIC","Y");
define("DisableEventsCheck", true);
define("BX_SECURITY_SHOW_MESSAGE", true);
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");

//ajax
if (!isset($_SERVER['HTTP_X_REQUESTED_WITH']) || empty($_SERVER['HTTP_X_REQUESTED_WITH'])
    || strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) != 'xmlhttprequest') {
    \Bitrix\Iblock\Component\Tools::process404(
        '404 Not Found'
        ,true
        ,"Y"
        ,"Y"
        , ""
    );
}

use Bitrix\Main\Application;
use Citfact\SiteCore\User\UserHelper;

$request = Application::getInstance()->getContext()->getRequest();
$userId = $request->getQuery('user_id');
$contragentXmlId = $request->getQuery('contragent_xml_id');

$fio = UserHelper::getFullNameByUser(CUser::GetByID($userId)->Fetch());

?>
    <div class="b-modal" data-delete-user-modal>
        <div class="b-modal__close" data-modal-close="">
            <div class="plus plus--cross"></div>
        </div>

        <div class="title-1">
            <span>Вы действительно хотите удалить пользователя<?php echo  ' ' .trim($fio); ?>?</span>
        </div>

        <form class="b-form" data-form-delete-user>

            <input type="hidden" name="id" value="<?php echo $userId; ?>">
            <input type="hidden" name="contragentXmlId" value="<?php echo $contragentXmlId; ?>">

            <div class="b-modal__bottom">
                <button type="submit" data-submit-reg-form class="btn btn--transparent btn--big">Подтвердить</button>
                <button type="button" data-submit-reg-form class="btn btn--transparent btn--big" data-modal-close>Отмена</button>
            </div>
        </form>
    </div>

    <script>
      Am.user.initDeactivation();
    </script>

<?php
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/epilog_after.php");