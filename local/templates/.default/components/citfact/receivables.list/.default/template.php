<? if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

use Citfact\Tools\Tools;
$sortParams = $this->__component->getSortParamsForSelect();
?>
<div class="lk-receivables__content-inner">
    <?if(empty($arParams['XML_ID'])):?>
    <div class="lk-receivables__contragent">
        <form action="" class="b-form">
            <? $APPLICATION->IncludeComponent(
                "citfact:contragent.list",
                ".default",
                Array()
            ); ?>
        </form>
    </div>
    <?endif;?>
    <div class="lk-receivables__sort">
        <div class="sort" style="margin-bottom: 20px;">
            <form action="" method="get" class="">
                <label for="">Сортировать</label>
                <select name="sort" class="select--white" onchange="selectSortChange($(this))">
                    <? foreach ($sortParams as $sortParam) {
                        $selected = (isset($arResult['arOrder'][$sortParam['name']]) && $arResult['arOrder'][$sortParam['name']] == $sortParam['sort']) ? true : false;
                        ?>
                        <option value="<?= $sortParam['name'] . '|' . $sortParam['sort'] ?>" <?= ($selected) ? 'selected' : '' ?>><?= $sortParam['label'] ?></option>
                    <? } ?>
                </select>
            </form>
        </div>
    </div>
</div>
<div class="lk-receivables__top">
        <span>Общая задолженность:
            <span>
                <?= CurrencyFormat($arResult['TOTAL_RECEIVABLES'], 'RUB'); ?>
            </span>
        </span>
    <span class="red">Просроченная задолженность:
            <span>
                <?= $arResult['OVERDUE_RECEIVABLES'] > 0 ? CurrencyFormat($arResult['OVERDUE_RECEIVABLES'], 'RUB') : 0; ?>
            </span>
        </span>
    <span>Количество дней просрочки: <?= $arResult['OVERDUE_DAYS'] ?></span>
</div>
<? foreach ($arResult['RECEIVABLES'] as $receivable): ?>
    <div class="lk-receivables__item" data-toggle-wrap>

        <div class="lk-receivables__header" data-toggle-btn>
            <div class="lk-receivables__status <?php echo $receivable['OFFLINE'] ? 'red' : 'green'; ?>">
                <span><?php echo $receivable['OFFLINE'] ? 'Offline' : 'Online'; ?></span>
            </div>
            <div class="lk-receivables__title">
                <a href="/personal/orders/<?= $receivable['UF_NOMER']; ?>"
                   class="lk-receivables__detail-link"
                   data-link-stop-propagation
                >
                    Заказ № <?= $receivable['UF_NOMER'] ?> от <?= $receivable['ORDER_CREATE_DATE'] ?>
                </a>
                <?/*php if (!empty($receivable['ORDER_PRICE'])) { ?>
                    <span>
                        <?= Tools::declension($receivable['ORDER_ITEMS_COUNT'], ['товар', 'товара', 'товаров']); ?> на сумму
                        <span>
                            <?= CurrencyFormat($receivable['ORDER_PRICE'], 'RUB'); ?>
                        </span>
                    </span>
                <?php } else { ?>
                    <span>Нет данных</span>
                <?php } */?>
            </div>
            <?php if (!empty($receivable['ORDER_USER_NAME'])) { ?>
                <div class="lk-receivables__text">Оформил заказ:&nbsp;&nbsp;<?= $receivable['ORDER_USER_NAME']; ?></div>
            <?php } ?>
        </div>
        <div class="lk-receivables__content" data-toggle-list style="display: none;">
            <div class="lk-receivables__content-inner">
                <?php if ($receivable['UF_SUMMA'] < 0) { ?>
                    <div class="lk-receivables__content-col green">
                        <span>Предоплата:</span>
                        <span><?= CurrencyFormat(abs($receivable['UF_SUMMA']), 'RUB'); ?></span>
                    </div>
                <?php } else { ?>
                    <div class="lk-receivables__content-col red">
                        <span>Просроченная задолженность:</span>
                        <span><?= CurrencyFormat($receivable['UF_SUMMAPROSROCHENO'], 'RUB'); ?></span>
                    </div>
                <?php } ?>

                <div class="lk-receivables__content-col lk-receivables__content-col--middle">
                    <span>Сумма задолженности:</span>
                    <span><?= $receivable['UF_SUMMA'] > 0 ? CurrencyFormat($receivable['UF_SUMMA'], 'RUB') : 0; ?></span>
                </div>
                <div class="lk-receivables__content-col lk-receivables__content-col--small">
                    <span>Дата оплаты:</span>
                    <span><?= $receivable['UF_DATA'] ?></span>
                </div>
                <div class="lk-receivables__content-col">
                    <span>Количество дней до завершения срока оплаты:</span>
                    <span><?= $receivable['DAYS_TO_PAY'] ?></span>
                </div>
                <div class="lk-receivables__content-col">
                    <span>Количество просроченных дней оплаты:</span>
                    <span><?= $receivable['DAYS_DELAY'] ?></span>
                </div>
                <div class="lk-receivables__content-col lk-receivables__content-col--small lk-receivables-bill">
                    <span>Счет на оплату:</span>
                    <div class="lk-receivables-bill__file">
                        <?
                        foreach ($receivable['ORDER_FILES'] as $file) {
                            if ($file['UF_TIP'] == 'Счет') { ?>
                                <a
                                        target="_blank"
                                        href="<?= $file['FILE']['SRC']; ?>"
                                        class="lk-receivables-bill__link"
                                >
                                    Скачать счет
                                </a>
                            <? }
                        }
                        ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
<? endforeach ?>
<?// if ($arResult['CURRENT_PAGE'] < $arResult['LAST_PAGE']): ?>
    <a href="javascript:void(0);" class="btn btn--loading btn--orange" data-show-more
       title="Загрузить ещё"
       data-current-page="<?= $arResult["CURRENT_PAGE"] ?>"
       data-last-page="<?= $arResult["LAST_PAGE"] ?>">
        <svg class='i-icon'>
            <use xlink:href='#icon-loading'/>
        </svg>
        <span>Загрузить ещё</span>
        <span>Загружается</span>
    </a>
<?// endif ?>
