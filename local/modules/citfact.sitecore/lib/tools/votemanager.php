<?php


namespace Citfact\SiteCore\Tools;

use Bitrix\Main\Config\Option;


class VoteManager
{
    const DATA_LIMIT_SHOW = 'date_limit_vote';

    public function checkConditionForShowingVote()
    {
        global $USER;
        $showVote = false;
        $rsUsers = \CUser::GetList(($by = "id"), ($order = "asc"), ['ACTIVE' => 'Y', 'ID' => $USER->GetID()]);
        if ($userData = $rsUsers->Fetch()) {
            $dateRegister = $userData['DATE_REGISTER'];
        }
        $dateLimitVote = Option::get("main", self::DATA_LIMIT_SHOW, '01-05-2021');
        $dateRegUnix = strtotime($dateRegister);
        $dateLimit = strtotime($dateLimitVote);
        $VID = \GetCurrentVote("CLIENT_QUESTIONNAIRE");
        if (\IsUserVoted($VID) == false && $VID != 0 && $dateLimit > $dateRegUnix) {
            $showVote = true;
        }
        $checkAuth = $this->checkAuth();
        return $showVote && $checkAuth;
    }

    protected function checkAuth()
    {
        global $USER;
        return (bool)$USER->IsAuthorized();
    }

    public function init()
    {
        if ($this->checkConditionForShowingVote()) {
            if (!isset($_COOKIE['IS_MODAL_SHOW'])) {
                setcookie('IS_MODAL_SHOW', 'Y');
            }
        } else {
            if($this->checkAuth()){
                setcookie('IS_MODAL_SHOW', 'N');
            } else {
                setcookie('IS_MODAL_SHOW', '', time()-86400);
            }
        }
    }
}