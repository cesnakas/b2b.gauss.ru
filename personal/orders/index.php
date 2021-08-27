<?
define("NEED_AUTH",true);

use Citfact\SiteCore\Definition\Mobile;
use Citfact\SiteCore\Core;

require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("Персональные данные");

global $USER;
$userId = $GLOBALS["USER"]->GetID();
?>
	<?$APPLICATION->IncludeComponent(
		"citfact:sale.personal.order",
		".default",
		array(
			"STATUS_COLOR_N" => "green",
			"STATUS_COLOR_P" => "yellow",
			"STATUS_COLOR_F" => "gray",
			"STATUS_COLOR_PSEUDO_CANCELLED" => "red",
			"SEF_MODE" => "Y",
			"ORDERS_PER_PAGE" => "20",
			"PATH_TO_PAYMENT" => "payment.php",
			"PATH_TO_BASKET" => "/cart/",
			"SET_TITLE" => "Y",
			"SAVE_IN_SESSION" => "Y",
			"NAV_TEMPLATE" => "",
			"ACTIVE_DATE_FORMAT" => "d.m.Y H:i:s",
			"PROP_1" => "",
			"PROP_2" => "",
			"CACHE_TYPE" => "A",
			"CACHE_TIME" => "3600",
			"CACHE_GROUPS" => "Y",
            'XML_ID' => $_REQUEST['XML_ID'],
			"CUSTOM_SELECT_PROPS" => array(
			),
			"HISTORIC_STATUSES" => array(
				0 => "F",
			),
			"SEF_FOLDER" => "/personal/orders/",
			"COMPONENT_TEMPLATE" => ".default",
			"DETAIL_HIDE_USER_INFO" => array(
				0 => "0",
			),
			"PATH_TO_CATALOG" => "/catalog/",
			"RESTRICT_CHANGE_PAYSYSTEM" => array(
				0 => "0",
			),
			"REFRESH_PRICES" => "N",
			"ORDER_DEFAULT_SORT" => "STATUS",
			"ALLOW_INNER" => "N",
			"ONLY_INNER_FULL" => "N",
			"SEF_URL_TEMPLATES" => array(
				"list" => "",
				"detail" => "#ID#/",
				"cancel" => "",
			)
		),
		false
	);?>
	<script>
		window.setMinDateForDatePicker = false;
	</script>
<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>