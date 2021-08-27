<? use Bitrix\Main\Localization\Loc;

if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
/** @var array $arParams */
/** @var array $arResult */
/** @global CMain $APPLICATION */
/** @global CUser $USER */
/** @global CDatabase $DB */
/** @var CBitrixComponentTemplate $this */
/** @var string $templateName */
/** @var string $templateFile */
/** @var string $templateFolder */
/** @var string $componentPath */
/** @var CBitrixComponent $component */
$this->setFrameMode(true);?>
<?
$INPUT_ID = trim($arParams["~INPUT_ID"]);
if(strlen($INPUT_ID) <= 0)
	$INPUT_ID = "title-search-input-mobile";
$INPUT_ID = CUtil::JSEscape($INPUT_ID);

$CONTAINER_ID = trim($arParams["~CONTAINER_ID"]);
if(strlen($CONTAINER_ID) <= 0)
	$CONTAINER_ID = "title-search";
$CONTAINER_ID = CUtil::JSEscape($CONTAINER_ID);

if($arParams["SHOW_INPUT"] !== "N"):?>
    <form action="<?echo $arResult["FORM_ACTION"]?>"
          id="<?echo $CONTAINER_ID?>"
          class="b-form h__search-m" data-search-wrap>
        <div class="b-form__item">
            <input type="text"
                   name="q"
                   id="<?echo $INPUT_ID?>"
                   maxlength="50"
                   autocomplete="off"
                   placeholder="Поиск по товарам"
                   value=""
                   data-f-field>
        </div>
        <button type="submit" data-search-btn="" name="s" value="<?=GetMessage("CT_BST_SEARCH_BUTTON");?>">
            <svg class='i-icon'>
                <use xlink:href='#icon-search'/>
            </svg>
        </button>
    </form>
<?endif?>
<script>
	BX.ready(function(){
		new JCTitleSearch({
			'AJAX_PAGE' : '<?echo CUtil::JSEscape(POST_FORM_ACTION_URI)?>',
			'CONTAINER_ID': '<?echo $CONTAINER_ID?>',
			'INPUT_ID': '<?echo $INPUT_ID?>',
			'MIN_QUERY_LEN': 2
		});
	});
</script>
