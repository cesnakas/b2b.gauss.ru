<?
define("NEED_AUTH", true);

use Citfact\SiteCore\Definition\Mobile;
use Citfact\SiteCore\Core;

require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/header.php");
$APPLICATION->SetTitle("Возврат товара");

global $USER;
$userId = $GLOBALS["USER"]->GetID();
?><div class="lk__section lk-return">
	<div class="lk-return__text active" data-show-more="">
		<div class="title-1">
 <span>
			<?$APPLICATION->IncludeComponent(
	"bitrix:main.include",
	"",
	Array(
		"AREA_FILE_SHOW" => "file",
		"AREA_FILE_SUFFIX" => "inc",
		"EDIT_TEMPLATE" => "",
		"PATH" => "/local/include/areas/personal/purchase-returns/title-1.php"
	)
);?> </span>
		</div>
		 <?$APPLICATION->IncludeComponent(
	"bitrix:main.include",
	"",
	Array(
		"AREA_FILE_SHOW" => "file",
		"AREA_FILE_SUFFIX" => "inc",
		"EDIT_TEMPLATE" => "",
		"PATH" => "/local/include/areas/personal/purchase-returns/text-1.php"
	)
);?> <?$APPLICATION->IncludeComponent(
	"bitrix:main.include",
	"",
	Array(
		"AREA_FILE_SHOW" => "file",
		"AREA_FILE_SUFFIX" => "inc",
		"EDIT_TEMPLATE" => "",
		"PATH" => "/local/include/areas/personal/purchase-returns/docs-1.php"
	)
);?>
		<div class="title-1">
 <span>
			<?$APPLICATION->IncludeComponent(
	"bitrix:main.include",
	"",
	Array(
		"AREA_FILE_SHOW" => "file",
		"AREA_FILE_SUFFIX" => "inc",
		"EDIT_TEMPLATE" => "",
		"PATH" => "/local/include/areas/personal/purchase-returns/title-2.php"
	)
);?> </span>
		</div>
	</div>
	<div class="link-toggle hidden" data-show-more-btn="" title="Подробнее">
        <span>Подробнее</span>
        <span>Скрыть</span>
	</div>
	<div class="b-checkbox">
 <label class="b-checkbox__label"> <input type="checkbox" id="checkbox" class="b-checkbox__input" data-checkbox-agree=""> <span class="b-checkbox__box"> <span class="b-checkbox__line b-checkbox__line--short"></span> <span class="b-checkbox__line b-checkbox__line--long"></span> </span> <span class="b-checkbox__text">Я согласен с условиями возврата товара</span> </label>
	</div>
	 <?$APPLICATION->IncludeComponent(
	"citfact.lib:webform.ajax",
	"purchase_returns",
	Array(
		"COMPONENT_TEMPLATE" => "purchase_returns",
		"HIDDEN_FIELD" => "COMPLEX_NAME",
		"HIDDEN_FIELD_VALUE" => "",
		"PARAM" => "",
		"SET_PLACEHOLDER" => "N",
		"SUCCESS_MESSAGE" => "Ваша Заявка на обмен/возврат товара принята [ID - #id#]. В течение 1 рабочего часа с Вами свяжется сотрудник Клиентского сервиса.",
		"WEB_FORM_CODE" => "SIMPLE_FORM_10"
	)
);?>
</div>
<br><? require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/footer.php"); ?>