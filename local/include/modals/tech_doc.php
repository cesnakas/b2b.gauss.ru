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

$XML_ID = $_REQUEST['XML_ID'];

$item = \Bitrix\Iblock\ElementTable::getlist([
    'filter' => ['ACTIVE' => 'Y', 'XML_ID' => $XML_ID],
    'select' => ['NAME'],
])->fetch();

$arDocuments = \Citfact\SiteCore\Dokumentatsiya\DokumentatsiyaManager::getListByNomenclature($XML_ID);

if (!empty($arDocuments) && !empty($item)) {
    ?>
    <div class="b-modal" data-modal-form>
        <div class="plus plus--cross b-modal__close" data-modal-close></div>
        <div class="title-1">
            <span>Скачать документы <?= $item['NAME']; ?></span>
        </div>

        <div class="b-modal__content">
            <? foreach ($arDocuments as $document) { ?>
                <a href="/local/include/php/downloader.php?path=<?= $document['FILE'] ?>" class="t-doc__item link-download"
                   download>
                    <svg class="i-icon">
                        <use xlink:href="#icon-t-doc"/>
                    </svg>
                    <span><?= $document['UF_NAZNACHENIE'] ?></span>
                </a>
            <? } ?>
        </div>
    </div>
    <?
} else {
    ?>
    <div class="b-modal" data-modal-form>
        <div class="plus plus--cross b-modal__close" data-modal-close></div>
        <div class="b-modal__title">При загрузке произошли ошибка. Обратитесь к администратору портала.</div>
    </div>
    <?
}
?>
<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/epilog_after.php");?>