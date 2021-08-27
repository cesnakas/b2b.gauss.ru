<?php
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

use Bitrix\Main\Localization\Loc;

$this->setFrameMode(false);
?>
<? if ($component->isAjax == true) {
    $GLOBALS['APPLICATION']->RestartBuffer();
} ?>
<div class="lk-main">
    <? if ($arResult['arUser']['UF_ACTIVATE_PROFILE']) { ?>
        <div class="lk-main__columns">
            <div class="lk-main__column">
                <?if(empty($arParams['XML_ID'])):?>
                <div class="lk-main__item">
                    <div class="lk-main__head">
                        <div class="lk-main__title">
                            Личная информация
                        </div>
                        <a href="/personal/profile/" class="link-underline" title="Подробнее">Подробнее</a>
                    </div>
                    <div class="lk-main__params">
                        <div class="lk-main__param">
                            <div>Название компании</div>
                            <div><?= $arResult['arUser']['WORK_COMPANY'] ?></div>
                        </div>
                        <div class="lk-main__param">
                            <div>Ф.И.О</div>
                            <div><?= $arResult['arUser']['LAST_NAME'] . ' ' . $arResult['arUser']['NAME'] . ' ' . $arResult['arUser']['SECOND_NAME'] ?></div>
                        </div>
                        <div class="lk-main__param">
                            <div>Телефон</div>
                            <div><?= $arResult['arUser']['PERSONAL_PHONE'] ?></div>
                        </div>
                        <div class="lk-main__param">
                            <div>E-mail</div>
                            <div><?= $arResult['arUser']['EMAIL'] ?></div>
                        </div>
                    </div>
                </div>
                <?else:?>
                    <div class="lk-main__item">
                        <div class="lk-main__head">
                            <div class="lk-main__title">
                                Личная информация
                            </div>
                        </div>
                        <div class="lk-main__params">
                            <div class="lk-main__param">
                                <div>Название контрагента</div>
                                <div><?= $arResult['arUser']['WORK_COMPANY'] ?></div>
                            </div>
                        </div>
                    </div>
                <?endif;?>
                <div class="lk-main__item">
                    <div class="lk-main__head">
                        <div class="lk-main__title">
                            Настройка оповещений
                        </div>
                    </div>
                    <div class="lk-main__params">
                        <form method="post">
                            <div class="lk-main__subscribe">
                                <div class="lk-main__subscribe-title">Новости и статьи</div>
                                <div class="b-checkbox">
                                    <label for="newsSubscribe" class="b-checkbox__label">
                                        <input type="checkbox" class="b-checkbox__input" name="news" id="newsSubscribe"
                                               onclick="subscribe('news')" <? if ($arResult['UF_EMAIL_NEWS']) : ?> checked <? endif; ?>
                                        >
                                        <span class="b-checkbox__box">
                                            <span class="b-checkbox__line b-checkbox__line--short"></span>
                                            <span class="b-checkbox__line b-checkbox__line--long"></span>
                                        </span>
                                    </label>
                                </div>
                            </div>
                            <div class="lk-main__subscribe">
                                <div class="lk-main__subscribe-title">Новинки и акции</div>
                                <div class="b-checkbox">
                                    <label for="promotionsSubscribe" class="b-checkbox__label">
                                        <input type="checkbox" class="b-checkbox__input" name="promotions" id="promotionsSubscribe"
                                               onclick="subscribe('promotions')" <? if ($arResult['UF_EMAIL_PROMOTIONS']) : ?> checked <? endif; ?>
                                        >
                                        <span class="b-checkbox__box">
                                            <span class="b-checkbox__line b-checkbox__line--short"></span>
                                            <span class="b-checkbox__line b-checkbox__line--long"></span>
                                        </span>
                                    </label>
                                </div>
                            </div>
                            <div class="lk-main__notice"></div>
                        </form>
                    </div>
                </div>
                <div class="lk-main__item">
                    <div class="lk-main__head">
                        <div class="lk-main__title">
                            Ваш менеджер
                        </div>
                    </div>
                    <div class="lk-main__params">
                        <div class="lk-main__param">
                            <div>Ф.И.О менеджера</div>
                            <div><?= trim($arResult['arUser']['MANAGER']['FIO']) ?: '---'; ?></div>
                        </div>
                        <div class="lk-main__param">
                            <div>E-mail менеджера</div>
                            <? if (trim($arResult['arUser']['MANAGER']['EMAIL'])): ?>
                                <a href="mailto:<?= htmlspecialcharsbx($arResult['arUser']['MANAGER']['EMAIL']) ?>" title="<?= trim($arResult['arUser']['MANAGER']['EMAIL']); ?>">
                                    <?= trim($arResult['arUser']['MANAGER']['EMAIL']); ?>
                                </a>
                            <? else: ?>
                                <div><?= '---'; ?></div>
                            <? endif; ?>
                        </div>
                        <div class="lk-main__param">
                            <div>Телефон менеджера</div>
                            <? if (trim($arResult['arUser']['MANAGER']['PHONE'])): ?>
                                <a href="tel:<?= htmlspecialcharsbx($arResult['arUser']['MANAGER']['PHONE']) ?>" title="<?= trim($arResult['arUser']['MANAGER']['PHONE']); ?>">
                                    <?= trim($arResult['arUser']['MANAGER']['PHONE']); ?>
                                </a>
                            <? else: ?>
                                <div><?= '---'; ?></div>
                            <? endif; ?>
                        </div>
                    </div>

                    <?php
                    if (!empty($arResult['arUser']['ASSISTANTS'])) {
                        foreach ($arResult['arUser']['ASSISTANTS'] as $assistant) { ?>
                            <div class="lk-main__params">
                                <div class="lk-main__param">
                                    <div>Ф.И.О ассистента</div>
                                    <div><?= trim($assistant['FIO']) ?: '---'; ?></div>
                                </div>
                                <div class="lk-main__param">
                                    <div>E-mail ассистента</div>
                                    <? if (trim($assistant['EMAIL'])): ?>
                                        <a href="mailto:<?= htmlspecialcharsbx($assistant['EMAIL']) ?>" title="<?= trim($assistant['EMAIL']); ?>">
                                            <?= trim($assistant['EMAIL']); ?>
                                        </a>
                                    <? else: ?>
                                        <div><?= '---'; ?></div>
                                    <? endif; ?>
                                </div>
                                <div class="lk-main__param">
                                    <div>Телефон ассистента</div>
                                    <? if (trim($assistant['PHONE'])): ?>
                                        <a href="tel:<?= htmlspecialcharsbx($assistant['PHONE']) ?>" title="<?= trim($assistant['PHONE']); ?>">
                                            <?= trim($assistant['PHONE']); ?>
                                        </a>
                                    <? else: ?>
                                        <div><?= '---'; ?></div>
                                    <? endif; ?>
                                </div>
                            </div>
                        <?php }
                    } ?>

                    <a href="/local/include/modals/ask-question.php" title="Задать вопрос" class="btn btn--grey" data-modal="ajax">Задать
                        вопрос</a>
                </div>
            </div>
            <div class="lk-main__column">
                <div class="lk-main__item">
                    <div class="lk-main__head">
                        <div class="lk-main__title">
                            Дебиторская задолженность
                        </div>
                        <?if(!empty($arParams['XML_ID'])):?>
                            <a href="/personal/receivables/<?=$arParams['XML_ID']?>/" class="link-underline" title="Подробнее">Подробнее</a>
                        <?else:?>
                             <a href="/personal/receivables/" class="link-underline" title="Подробнее">Подробнее</a>
                        <?endif;?>
                    </div>
                    <div class="debmoney">
                        <b><?= CurrencyFormat($arResult["OVERDUE_RECEIVABLES"], 'RUB');?></b>
                    </div>
                    <div class="lk-main__text">
                        <?
                        $APPLICATION->IncludeComponent("bitrix:main.include", "",
                            [
                                "AREA_FILE_SHOW" => "file",    // Показывать включаемую область
                                "AREA_FILE_SUFFIX" => "inc",
                                "EDIT_TEMPLATE" => "",    // Шаблон области по умолчанию
                                "PATH" => '/local/include/areas/personal/orders-text.php',    // Путь к файлу области
                            ],
                            false
                        ); ?>
                    </div>
                </div>

                <div class="lk-main__item">
                    <div class="lk-main__head">
                        <div class="lk-main__title">
                            План-факт
                        </div>
                        <?if(!empty($arParams['XML_ID'])):?>
                            <a href="/personal/plan-fact/<?=$arParams['XML_ID']?>/" class="link-underline" title="Подробнее">Подробнее</a>
                        <?else:?>
                            <a href="/personal/plan-fact/" class="link-underline" title="Подробнее">Подробнее</a>
                        <?endif;?>
                    </div>
                <div class="lk-main__img">
                        <span>  <?
                            $APPLICATION->IncludeComponent("bitrix:main.include", "",
                                [
                                    "AREA_FILE_SHOW" => "file",    // Показывать включаемую область
                                    "AREA_FILE_SUFFIX" => "inc",
                                    "EDIT_TEMPLATE" => "",    // Шаблон области по умолчанию
                                    "PATH" => '/local/include/areas/personal/fact-text.php',    // Путь к файлу области
                                ],
                                false
                            ); ?>
                        </span>

                        <?
                        $APPLICATION->IncludeComponent("bitrix:main.include", "",
                            [
                                "AREA_FILE_SHOW" => "file",    // Показывать включаемую область
                                "AREA_FILE_SUFFIX" => "inc",
                                "EDIT_TEMPLATE" => "",    // Шаблон области по умолчанию
                                "PATH" => '/local/include/areas/personal/fact-img.php',    // Путь к файлу области
                            ],
                            false
                        ); ?>
                    </div>
                </div>
            </div>
        </div>
        <div class="lk-main__columns">
            <div class="lk-main__column">
                <div class="lk-main__item">
                    <div class="lk-main__head">
                        <div class="lk-main__title">
                            Мои заказы
                        </div>
                        <?if(!empty($arParams['XML_ID'])):?>
                            <a href="/personal/<?=$arParams['XML_ID']?>/orders/" class="link-underline" title="Подробнее">Подробнее</a>
                        <?else:?>
                            <a href="/personal/orders/" class="link-underline" title="Подробнее">Подробнее</a>
                        <?endif;?>
                    </div>
                    <div class="lk-main__params">
                        <div class="lk-main__param">
                            <div>Заказов в работе:</div>
                            <?if(!empty($arParams['XML_ID'])):?>
                                <div><a href="/personal/<?=$arParams['XML_ID']?>/orders/" title="<?= $arParams['COUNT_ORDERS'] ?>"><?= $arParams['COUNT_ORDERS'] ?></a></div>
                            <?else:?>
                                <div><a href="/personal/orders/" title="<?= $arParams['COUNT_ORDERS'] ?>"><?= $arParams['COUNT_ORDERS'] ?></a></div>
                            <?endif;?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    <? } else {
        echo "Ваша заявка находится на рассмотрении у менеджера компании “Gauss”. Ваш личный кабинет пока недоступен. Пожалуйста, дождитесь подтверждения заявки менеджером.";
    }; ?>
</div>

<script>
    function subscribe(name) {
        let isActive = $(`input[name=${name}]`).prop("checked") ? 'Y' : 'N';

        $.ajax({
            type: "POST",
            url: "/local/include/ajax/saveSubscribeUser.php",
            data: {'name' : name, 'active' : isActive},
            success: (data) => {
                $('.lk-main__notice').text('Изменения сохранены');
                setTimeout(() => $('.lk-main__notice').text(''), 1500);
            }
        })
    }
</script>

<? if ($component->isAjax === true) {
    require_once($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/epilog_after.php");
} ?>
