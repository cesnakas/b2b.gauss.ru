<?

if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) {
    die();
}

use Bitrix\Main\Localization\Loc;
use Citfact\SiteCore\Core;
use Citfact\SiteCore\User\Service\PortalAuthorizeByUser;

Loc::loadMessages(__FILE__);

class PortalUserAuthorizeBackComponent extends \CBitrixComponent
{
    /**
     * {@inheritdoc}
     */
    public function executeComponent()
    {
        $portalAuthorizeByUser = new PortalAuthorizeByUser();
        $userName = $portalAuthorizeByUser->getUserName();
        $backUserId = $portalAuthorizeByUser->getBackUserId();
        $backUrl = $portalAuthorizeByUser->getBackUrl();

        if (!$userName || !$backUserId || !$backUrl) {
            return;
        }

        $this->arResult['USER_NAME'] = $userName;

        if ($_REQUEST['AUTHORIZE_BY_MANAGER'] == 'Y') {
            $this->authorizeByManager($backUserId);
        }

        $this->IncludeComponentTemplate();
    }

    private function authorizeByManager($userId)
    {

        $core = Core::getInstance();

        $arGroupManagers = $core->GetGroupByCode($core::USER_GROUP_MANAGER . '|' . $core::USER_GROUP_ASSISTANT);
        $portalAuthorizeByUser = new PortalAuthorizeByUser();
        $userGroups = CUser::GetUserGroup($userId);
        $user = new \CUser();
        $backUrl = $portalAuthorizeByUser->getBackUrl();

        if (empty(array_intersect($userGroups, $arGroupManagers))) {
            throw new \Exception('Недостаточно прав для авторизации под пользователем ' . $userId);
        }

        $portalAuthorizeByUser->unsetSessionData();

        if (true === $user->Authorize($userId)) {
            LocalRedirect($backUrl);
        }
    }
}

