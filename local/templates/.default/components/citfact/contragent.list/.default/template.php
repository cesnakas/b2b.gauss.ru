<? if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

/** ajax */
$dir = $APPLICATION->GetCurDir();
$isProfile = strpos($dir, '/personal/') !== false && strpos($dir, '/personal/receivables/') === false;
?>
<div id="contragent_list_default" class="b-form__item b-form__item--select" data-f-item>
    <?
    /** @var \Bitrix\Main\Page\FrameBuffered $frame */
    $frame = $this->createFrame('contragent_list_default', false)->begin();
    ?>
    <select onchange="setContragent(this);" class="<?= ($isProfile) ? '' : 'select--white'?>" name="contragents" id="contragents"
            data-user-id="<?= $USER->GetID() ?>" data-f-field>
        <? foreach ($arResult['CONTRAGENTS'] as $contragent): ?>
            <option value="<?= $contragent['UF_XML_ID'] ?>" <?= $contragent['UF_XML_ID'] === $arResult['CURRENT_CONTRAGENT'] ? 'selected' : '' ?>>
                <?= $contragent['UF_NAME'] ?>
            </option>
        <? endforeach; ?>
    </select>
    <? $frame->beginStub();
    /** stub */
    ?>
    <select onchange="return false;" class="<?= ($isProfile) ? '' : 'select--white'?>" name="contragents" id="contragents"
            data-user-id="" data-f-field>
    </select>
    <? $frame->end(); ?>
</div>