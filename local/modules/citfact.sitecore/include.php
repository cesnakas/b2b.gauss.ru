<?php

require('constants.php');

class CitfactSitecoreModuleEventsHandler
{
    public function OnBuildGlobalMenuHandler(&$aGlobalMenu, &$aModuleMenu)
    {
        /** @global CMain $APPLICATION */
        global $APPLICATION;
        $APPLICATION->SetAdditionalCSS('/bitrix/themes/.default/citfact_sitecore.css');
        $aGlobalMenu['global_menu_sitecore'] = array(
            'menu_id' => 'citfact_sitecore',
            'page_icon' => 'sitecore_title_icon',
            'index_icon' => 'sitecore_page_icon',
            'text' => 'Студия «Факт»',
            'title' => 'Студия «Факт»',
            //'url' => '#',
            'sort' => '70',
            'items_id' => 'global_menu_sitecore',
            'help_section' => 'sitecore',
            'items' => array()
        );
    }
}