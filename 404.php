<?
$isProlog = true;
if(!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) {
    $isProlog = false;
    include_once($_SERVER['DOCUMENT_ROOT'].'/bitrix/modules/main/include/urlrewrite.php');
}


CHTTP::SetStatus("404 Not Found");
@define("ERROR_404","Y");
@define("ERROR_404_PAGE","Y");

require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");

$APPLICATION->SetTitle("Страница не найдена");
?>
    <div class="e-404">
        <div class="title"><span>Страница не найдена</span></div>
        <p>Возможно, вы неправильно набрали адрес, или такой страницы на сайте больше не существует.</p>
        <a href="/" class="btn btn--transparent" title="Перейти на главную"><span>Перейти на главную</span></a>
        <div class="e-404__img">
            <img src="/local/client/img/404.png" alt="Ошибка" title="404_error" class="lazy">
        </div>
    </div>
<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");
?>