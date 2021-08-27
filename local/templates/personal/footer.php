<?
if(!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true)
    die();
use Citfact\SiteCore\Tools\ModalManager;
use Citfact\SiteCore\Tools\VoteManager;

$basketHelper = new Citfact\SiteCore\Order\Basket;
$basketHelper->setBasketItemsOnLoad();
?>
        </div> <?/* .lk end */?>
    </div> <?/* .container end */?>
</main>
<footer class="f">

    <div class="container">
        <div class="f__bottom">
            <div class="f__copyright">
                © 2015-<?=date('Y')?>&nbsp;
                <? $APPLICATION->IncludeComponent(
                    "bitrix:main.include",
                    "",
                    array(
                        "COMPONENT_TEMPLATE" => ".default",
                        "AREA_FILE_SHOW" => "file",
                        "AREA_FILE_SUFFIX" => "",
                        "AREA_FILE_RECURSIVE" => "Y",
                        "EDIT_TEMPLATE" => "",
                        "PATH" => "/local/include/areas/footer/copyright.php"
                    ),
                    false
                ); ?>
            </div>

            <a href="https://fact.digital/" rel="nofollow" target="_blank" title="Разработка сайта — компания «Факт»">Разработка сайта — компания «Факт»</a>
        </div>
    </div>
</footer>
</div> <?/* .lk-aside__main end */?>
</div> <?/* .lk-aside end */?>

<div id="cookie-alert" class="cookie-alert animated fadeInUp hidden" data-scrollbar-fix>
    <div>
        Сайт использует файлы Cookie и данные об ip-адресе пользователя. Продолжая пользоваться сайтом, вы автоматически
        с этим соглашаетесь.
        <a href="/policy/" class="link" target="_blank" title="Обработка персональных данных">Обработка персональных данных</a>
    </div>
    <div id="cookie-alert-close" class="plus plus--cross"></div>
</div>

<?$APPLICATION->IncludeComponent("bitrix:menu", "m_left_personal", [
    "ALLOW_MULTI_SELECT" => "N",	// Разрешить несколько активных пунктов одновременно
    "CHILD_MENU_TYPE" => "left",	// Тип меню для остальных уровней
    "DELAY" => "N",	// Откладывать выполнение шаблона меню
    "MAX_LEVEL" => "1",	// Уровень вложенности меню
    "MENU_CACHE_GET_VARS" => "",	// Значимые переменные запроса
    "MENU_CACHE_TIME" => "3600",	// Время кеширования (сек.)
    "MENU_CACHE_TYPE" => "A",	// Тип кеширования
    "MENU_CACHE_USE_GROUPS" => "Y",	// Учитывать права доступа
    "MENU_THEME" => "green",	// Тема меню
    "ROOT_MENU_TYPE" => "left_personal",	// Тип меню для первого уровня
    "USE_EXT" => "N",	// Подключать файлы с именами вида .тип_меню.menu_ext.php
    "COMPONENT_TEMPLATE" => "m_left_personal",
    "COMPOSITE_FRAME_MODE" => "A",
    "COMPOSITE_FRAME_TYPE" => "STATIC",
],
    false
);?>

<script type="text/javascript" defer>
    window.jsFileTime = <?= filemtime($_SERVER['DOCUMENT_ROOT'] . '/local/client/build/m.js') ?>;

    var cssLink = document.createElement('link');
    cssLink.rel = 'stylesheet';
    cssLink.href = '/local/client/build/m.css?<?= filemtime($_SERVER['DOCUMENT_ROOT'] . '/local/client/build/m.css'); ?>';
    cssLink.type = 'text/css';
    var headLink = document.getElementsByTagName('link')[0];
    headLink.parentNode.appendChild(cssLink);
</script>
<?
$modalManager = new ModalManager();
if ($modalManager->canOpenModal()) { ?>
    <script>
        document.addEventListener('App.Ready', function (e) {
           // $('[data-modal-info]')[0].click();
        })
    </script>
<?}?>

<? if (\CModule::IncludeModule("vote")) {
    global $USER;
    $voteManager = new VoteManager();
    if ($voteManager->checkConditionForShowingVote()) {
        ?>
        <script>
            function get_cookie_fact(cookie_name) {
                var results = document.cookie.match('(^|;) ?' + cookie_name + '=([^;]*)(;|$)');
                if (results)
                    return (unescape(results[2]));
                else
                    return null;
            }

            function set_cookie_fact(name, value, exp_y, exp_m, exp_d, path, domain, secure) {
                var cookie_string = name + "=" + escape(value);
                if (exp_y) {
                    var expires = new Date(exp_y, exp_m, exp_d);
                    cookie_string += "; expires=" + expires.toGMTString();
                }
                if (path)
                    cookie_string += "; path=" + escape(path);
                if (domain)
                    cookie_string += "; domain=" + escape(domain);
                if (secure)
                    cookie_string += "; secure";
                document.cookie = cookie_string;
            }

            document.addEventListener('App.Ready', function (e) {
                if (get_cookie_fact('IS_MODAL_SHOW') === 'Y') {
                    $('[data-modal-voting-form]')[0].click();
                    set_cookie_fact('IS_MODAL_SHOW', 'N');
                }
            })
        </script>
        <?
    }
}

$curDir = $APPLICATION->GetCurDir();
if($curDir != "/personal/"){?>
    <script>
        localStorage.removeItem("open_manager");
    </script>
<?}
?>



<?php $APPLICATION->ShowCSS(true); ?>
</body>
</html>