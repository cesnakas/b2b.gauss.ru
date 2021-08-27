<?
if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();
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
$this->setFrameMode(true);

?>

<div class="lk-orders">
    <form class="lk-orders__top lk-orders__top--company b-form" action="<?= $arParams['SEF_FOLDER'] ?>">

        <?/* todo в /personal/orders/ смена поля от селекта уже реализовано */?>

        <div class="b-form__item b-form__item--select" data-f-item>
            <span class="b-form__label" data-f-label>Поиск компании</span>

            <select class="select--white" name="COMPANY_FILTER[SEARCH_TYPE]" id="" data-f-field>
                <? foreach ($arResult['FILTER_FIELDS'] as $code => $name): ?>
                    <option value="<?= $code ?>"
                        <? if ($code == $arResult['REQUEST_DATA']['COMPANY_FILTER']['SEARCH_TYPE']) echo 'selected' ?>
                    >
                        <?= $name ?>
                    </option>
                <? endforeach ?>
            </select>
        </div>

        <div class="lk-orders__search">
            <div class="b-form__item <?= ($arResult['REQUEST_DATA']['COMPANY_FILTER']['SEARCH_STRING'])?'clear active':'';?>" data-f-item>
                <span class="b-form__label <?= ($arResult['REQUEST_DATA']['COMPANY_FILTER']['SEARCH_STRING'])?'active':'';?>" data-f-label>Поиск</span>

                <input type="text"
                       name="COMPANY_FILTER[SEARCH_STRING]"
                       data-f-field
                       data-order-feald
                       value="<?= $arResult['REQUEST_DATA']['COMPANY_FILTER']['SEARCH_STRING'] ?>">


                <button type="submit">
                    <svg class='i-icon'>
                        <use xlink:href='#icon-search'/>
                    </svg>
                </button>

                <button type="submit" name="ACTION_CLEAR" value="clear" class="clear" id="clear">
                    <span class="plus plus--cross"></span>
                </button>

                <span class="b-form__text">

                </span>
            </div>
        </div>
    </form>
</div>

<? if ($arResult['ITEMS']): ?>
<div class="basket basket--company">
    <div class="basket-item basket-item--top">
        <div class="basket-item__description">Название компании</div>
        <div class="basket-item__info">ИНН</div>
        <div class="basket-item__info">Телефон</div>
        <div class="basket-item__info">E-mail</div>
        <div class="basket-item__btn basket-item__btn--companies"></div>
    </div>
    <div class="basket__items">
        <? foreach ($arResult['ITEMS'] as $item): ?>
            <div class="basket-item">
                <div class="basket-item__description">
                    <a href="<?= $item['URL'] ?>"><span class="basket-item__title"><?= $item['UF_NAME'] ?></span></a>
                </div>
                <div class="basket-item__info">
                    <div class="basket-item__t">ИНН</div>
                    <span><?= $item['UF_INN'] ?></span>
                </div>
                <div class="basket-item__info">
                    <div class="basket-item__t">Телефон</div>
                    <span><?= $item['UF_TELEFON'] ?></span>
                </div>
                <div class="basket-item__info">
                    <div class="basket-item__t">E-mail</div>
                    <span><?= $item['UF_ELEKTRONNAYAPOCHT'] ?></span>
                </div>
                <div class="basket-item__btn basket-item__btn--companies">
                    <a href="<?= $item['URL'] ?>" class="btn-contragent btn--transparent btn--small">
                        <img src="/local/templates/.default/images/user_icon.svg" alt="Иконка">
                        <span class="btn-contragent__users"><?= $item['ACCEPT_CONTRAGENTS'] ?></span>
                        <div class="position-notice">
                            <div class="notice <? if (empty($item['REQUEST_NEW_CONTRAGENTS'])) : ?> notice__hidden <? endif; ?> ">
                                <div class="notice--element">+<?= $item['REQUEST_NEW_CONTRAGENTS'] ?></div>
                            </div>
                        </div>
                    </a>
                </div>
            </div>
        <?endforeach;?>
    </div>

    <div class="b-catalog__footer pagination">
        <div class="pagination__load "></div>
        <?
        global $APPLICATION;
        $APPLICATION->IncludeComponent(
            "bitrix:main.pagenavigation",
            "",
            array(
                "NAV_OBJECT" => $arResult['NAV_OBJECT'],
                "SEF_MODE" => "N",
            ),
            false
        );
        ?>
    </div>
</div>


<? else: ?>
    Компании не найдены.
<? endif ?>
