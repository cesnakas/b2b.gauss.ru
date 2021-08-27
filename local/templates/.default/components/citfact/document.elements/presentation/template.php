<? if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

use \Bitrix\Main\Localization\Loc;

/**
 * @global CMain $APPLICATION
 * @var array $arParams
 * @var array $arResult
 * @var CatalogSectionComponent $component
 * @var CBitrixComponentTemplate $this
 * @var string $templateName
 * @var string $componentPath
 */
$this->setFrameMode(true); ?>
<?php

function getSectionList($filter, $select)
{
    $dbSection = CIBlockSection::GetList(
        Array(
            'LEFT_MARGIN' => 'ASC',
        ),
        array_merge(
            Array(
                'ACTIVE' => 'Y',
                'GLOBAL_ACTIVE' => 'Y'
            ),
            is_array($filter) ? $filter : Array()
        ),
        false,
        array_merge(
            Array(
                'ID',
                'IBLOCK_SECTION_ID'
            ),
            is_array($select) ? $select : Array()
        )
    );

    while( $arSection = $dbSection-> GetNext(true, false) ){

        $SID = $arSection['ID'];
        $PSID = (int) $arSection['IBLOCK_SECTION_ID'];

        $arLincs[$PSID]['CHILDS'][$SID] = $arSection;

        $arLincs[$SID] = &$arLincs[$PSID]['CHILDS'][$SID];
    }

    return array_shift($arLincs);
}

?>
<?php

$arSections = getSectionList(
    Array(
        'IBLOCK_ID' => 55
    ),
    Array(
        'NAME',
        'SECTION_PAGE_URL'
    )
);


foreach ($arSections['CHILDS'] as $sect)
{
    $IBLOCK_ID = 55;
    $arFilter = Array('IBLOCK_ID' => $IBLOCK_ID, "SECTION_ID" =>$sect["ID"], "ACTIVE"=>"Y");
    $arSelect = Array("ID", "IBLOCK_ID", "NAME", "DATE_ACTIVE_FROM","PROPERTY_FILE");
    $res = CIBlockElement::GetList(array(), $arFilter, $arSelect);
    while($ar_fields = $res->GetNext())
    {
        $arSections['CHILDS'][$sect["ID"]]["ELEM"][$ar_fields["ID"]] = $ar_fields;
    }
}
?>
<div class="static-content static-content--mb">
    <?
    $APPLICATION->IncludeComponent("bitrix:main.include", "",
        [
            "AREA_FILE_SHOW" => "file",    // Показывать включаемую область
            "AREA_FILE_SUFFIX" => "inc",
            "EDIT_TEMPLATE" => "",    // Шаблон области по умолчанию
            "PATH" => '/local/include/areas/marketing-support/text.php',    // Путь к файлу области
        ],
        false
    ); ?>
</div>
<?foreach ($arSections['CHILDS'] as $section):?>
    <?if(!($section["ELEM"] == null)):?>
        <div class="s-toggle s-toggle--presentation" data-toggle-wrap>
            <a href="javascript:void(0);" data-presentation-section="<?=$section['ID']?>" class="s-toggle__file-presentation" title="<?= GetMessage('DOWNLOAD_ALL_PRESENTATIONS')?>">
                <svg class="i-icon">
                    <use xlink:href="#icon-file"/>
                </svg>
            </a>
            <div class="s-toggle__title s-toggle-arrow<?= $i == 0 ? ' active' : '' ?>" data-toggle-btn>
                <span ><?=$section["NAME"]?></span>
            </div>
            <div class="s-toggle__list t-doc-wrap" data-toggle-list> <? /* 2 лвл список */ ?>
                <? foreach ($section["ELEM"] as $subSubSection) { ?>
                    <?if ($subSubSection['NAME'] == trim($section["NAME"])):?>
                        <?$file = parse_url(CFile::GetPath($subSubSection['PROPERTY_FILE_VALUE']));
                        ?>
                        <div class="s-toggle s-toggle--in t-doc" >
                            <div class="s-toggle__title " > <? /* 3 лвл заголовок */ ?>
                                <?
                                global $USER;
                                if ($USER->IsAuthorized()):?>
                                    <a href="<?=$file['path']?>" download data-presentation-section-file>
                                        <svg class="i-icon">
                                            <use xlink:href="#icon-t-doc"/>
                                        </svg>
                                        <span><?/*= $subSubSection['NAME'] */?> Скачать презентацию раздела</span>
                                    </a>
                                <?else:?>
                                    <svg class="i-icon">
                                        <use xlink:href="#icon-t-doc"/>
                                    </svg>
                                    <span><?=$subSubSection['NAME']?></span>
                                <?endif;?>
                            </div>
                        </div>
                    <?endif;?>
                <? } ?>
                <? foreach ($section["ELEM"] as $subSubSection) { ?>
                    <?if (!($subSubSection['NAME'] == trim($section["NAME"]))):?>
                        <?$file = parse_url(CFile::GetPath($subSubSection['PROPERTY_FILE_VALUE']));
                        ?>
                        <div class="s-toggle s-toggle--in t-doc" >
                            <div class="s-toggle__title " > <? /* 3 лвл заголовок */ ?>
                                <?
                                global $USER;
                                if ($USER->IsAuthorized()):?>
                                    <a href="<?=$file['path']?>" download data-presentation-section-file>
                                        <svg class="i-icon">
                                            <use xlink:href="#icon-t-doc"/>
                                        </svg>
                                        <span><?= $subSubSection['NAME'] ?> cкачать</span>
                                    </a>
                                <?else:?>
                                    <svg class="i-icon">
                                        <use xlink:href="#icon-t-doc"/>
                                    </svg>
                                    <span><?=$subSubSection['NAME']?></span>
                                <?endif;?>
                            </div>
                        </div>
                    <?endif;?>
                <? } ?>
            </div>
        </div>
    <?endif;?>
<?endforeach;?>
