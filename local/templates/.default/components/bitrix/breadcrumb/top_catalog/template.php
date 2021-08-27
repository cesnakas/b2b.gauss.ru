<?php
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

/**
 * @global CMain $APPLICATION
 */

global $APPLICATION;

//delayed function must return a string
if(empty($arResult))
	return "";

$strReturn = '';

$strReturn .= '<div class="b-breadcrumbs">';

$itemSize = count($arResult);
for($index = 0; $index < $itemSize; $index++)
{
	$title = htmlspecialcharsex($arResult[$index]["TITLE"]);
	$arrow = ($index > 0? '<i class="fa fa-angle-right"></i>' : '');

	if($arResult[$index]["LINK"] <> "" && $index != $itemSize-1)
	{
		$strReturn .= '
			<div class="b-breadcrumbs__item">
			    <a class="b-breadcrumbs__link" href="'.$arResult[$index]["LINK"].'">'.$title.'</a>
			</div>';
	}
	else {
        $strReturn .= '
			<div class="b-breadcrumbs__item">' .
                $title .
            '</div>';
    }
}

$strReturn .= '</div>';

return $strReturn;
