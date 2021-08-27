<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

if (isset($arParams["TEMPLATE_THEME"]) && !empty($arParams["TEMPLATE_THEME"]))
{
	$arAvailableThemes = array();
	$dir = trim(preg_replace("'[\\\\/]+'", "/", dirname(__FILE__)."/themes/"));
	if (is_dir($dir) && $directory = opendir($dir))
	{
		while (($file = readdir($directory)) !== false)
		{
			if ($file != "." && $file != ".." && is_dir($dir.$file))
				$arAvailableThemes[] = $file;
		}
		closedir($directory);
	}

	if ($arParams["TEMPLATE_THEME"] == "site")
	{
		$solution = COption::GetOptionString("main", "wizard_solution", "", SITE_ID);
		if ($solution == "eshop")
		{
			$templateId = COption::GetOptionString("main", "wizard_template_id", "eshop_bootstrap", SITE_ID);
			$templateId = (preg_match("/^eshop_adapt/", $templateId)) ? "eshop_adapt" : $templateId;
			$theme = COption::GetOptionString("main", "wizard_".$templateId."_theme_id", "blue", SITE_ID);
			$arParams["TEMPLATE_THEME"] = (in_array($theme, $arAvailableThemes)) ? $theme : "blue";
		}
	}
	else
	{
		$arParams["TEMPLATE_THEME"] = (in_array($arParams["TEMPLATE_THEME"], $arAvailableThemes)) ? $arParams["TEMPLATE_THEME"] : "blue";
	}
}
else
{
	$arParams["TEMPLATE_THEME"] = "blue";
}
//Проверяем является ли текущий раздел каталога последним, для вывода фильтра по сериям
if(CModule::IncludeModule("iblock")){
    $haveSections=[];
    $lastSection=true;
    $arFilter = Array(
        'IBLOCK_ID'=>$arParams["IBLOCK_ID"],
        'GLOBAL_ACTIVE'=>'Y',
        'SECTION_ID'=>$arParams['SECTION_ID']);
    $db_list = CIBlockSection::GetList(Array($by=>$order), $arFilter, true);
    while($ar_result = $db_list->GetNext())
    {
        $haveSections[$ar_result['ID']] = $ar_result;
    }
    if ($haveSections)
    {
        $lastSection = false;
        foreach ($haveSections as $section)
        {
            if ($section['DEPTH_LEVEL'] >=4)
            {
                $lastSection=true;
                break;
            }
        }
    }
    $arParams['LAST_SECTION'] = $lastSection;
}
$arParams["FILTER_VIEW_MODE"] = (isset($arParams["FILTER_VIEW_MODE"]) && toUpper($arParams["FILTER_VIEW_MODE"]) == "HORIZONTAL") ? "HORIZONTAL" : "VERTICAL";
$arParams["POPUP_POSITION"] = (isset($arParams["POPUP_POSITION"]) && in_array($arParams["POPUP_POSITION"], array("left", "right"))) ? $arParams["POPUP_POSITION"] : "left";
