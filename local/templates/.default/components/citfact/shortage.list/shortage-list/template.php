<?

use Citfact\SiteCore\Definition\Mobile;
use Citfact\SiteCore\Tools\CountdownTimer;

if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();
$hours = new CountdownTimer();
?>

<div class="lk__section lk-shortage">

    <div class="lk-shortage__info">
        <p>
            <?$APPLICATION->IncludeComponent(
                "bitrix:main.include",
                "",
                Array(
                    "AREA_FILE_SHOW" => "file",
                    "AREA_FILE_SUFFIX" => "inc",
                    "EDIT_TEMPLATE" => "",
                    "PATH" => "/local/include/areas/personal/shortage-list/shortage-description.php"
                ) 
            );?>
        </p>
        <a href="/personal/shortage-form" class="btn btn--transparent btn--big">Подать заявку</a>
    </div>

    <form class="b-form lk-shortage__top" >

        <div class="lk-shortage-search">
            <div class="b-form__item" data-f-item>
                <span class="b-form__label" data-f-label>Поиск по №</span>
                <input type="text" name="value_id" data-f-field autocomplete="off">
                <button type="submit" name="ACTION_SEARCH" value="search">
                    <svg class='i-icon'>
                        <use xlink:href='#icon-search'/>
                    </svg>
                </button>
                <button type="submit" name="ACTION_CLEAR" value="clear" class="clear" id="clear">
                    <span class="plus plus--cross"></span>
                </button>
            </div>
        </div>
        <a href="/personal/shortage-list/" class="btn btn--transparent ">Сбросить фильтр</a>

        <div class="lk-shortage-sort">
            <span>Статус:</span>
            <select name="type" id="type" class="select--white" data-f-field>
                <option value="">Все статусы</option>
                <option value="online">Отправлено</option>
                <option value="online">Зарегистрировано</option>
                <option value="online">Согласовано</option>
                <option value="online">Отклонено</option>
            </select>
        </div>

    </form>

    <div class="lk-shortage__list">
        <?if(empty($arResult['RESULTS'])):?>
            <div style="text-align: center"> Записи не найдены</div>
        <?endif;?>
        <? foreach ($arResult['RESULTS'] as $item): ?>
            <?

            $waybillNum = $item["ANSWERS"]['INVOICE_NUMBER'][0]['USER_TEXT'];//номер накладной
            $comment = $item["ANSWERS"]['SIMPLE_QUESTION_904'][0]['USER_TEXT'];
            $file = CFile::GetPath($item["ANSWERS"]['SIMPLE_QUESTION_234'][0]['USER_FILE_ID']);
            $fileName = $item["ANSWERS"]['SIMPLE_QUESTION_234'][0]['USER_FILE_NAME'];
            $photo = CFile::GetPath($item["ANSWERS"]['SIMPLE_QUESTION_267'][0]['USER_FILE_ID']);
            $photoName = $item["ANSWERS"]['SIMPLE_QUESTION_267'][0]['USER_FILE_NAME'];
            ?>
            <div class="lk-shortage-i" data-toggle-wrap>

                <div class="lk-shortage-i__head">

                    <div class="lk-shortage-i__num">
                        <span class="lk-shortage-i__label">Номер накладной:</span>
                        <span><?= $waybillNum ?></span>
                    </div>

                    <div class="lk-shortage-i__process">

                        <div class="lk-shortage-i__status">
                            <span class="lk-shortage-i__label">Статус:</span>
                            <span class="<?= $item['STATUS_COLOR'] ?>"><?= $item['STATUS'] ?></span>
                        </div>

                        <? if ($item['TIMESTAMP_X']): ?>
                            <div class="lk-shortage-i__time">
                                <span class="lk-shortage-i__label">На обработку осталось:</span>
                                <span class="grey"><?= $hours->calcTicketHours($item['TIMESTAMP_X']) . $hours->getHourConjugation($item['TIMESTAMP_X']) ?> </span>
                            </div>
                        <? endif; ?>

                    </div>

                    <div class="lk-shortage-i-toggle">
                        <div class="lk-shortage-i-toggle__inner" data-toggle-btn></div>
                    </div>

                </div>

                <div class="lk-shortage-i-body" data-toggle-list>
                    <div class="lk-shortage-i-body__inner">

                        <div class="lk-shortage-i__comment">
                            <div class="lk-shortage-i__label">Комментарий к заявке:</div>
                            <div><?= $comment ?></div>
                        </div>

                        <div class="lk-shortage-i__docs">
                            <div class="lk-shortage-i__label">Прикрепленный документ:</div>
                            <div>
                                <a href="<?= $file ?>">
                                    <? if (!empty($fileName)) { ?>
                                        <svg class='i-icon'>
                                            <use xlink:href='#icon-bill'/>
                                        </svg>
                                    <? } ?>
                                    <span><?= $fileName ?></span>
                                </a>
                            </div>
                        </div>

                        <div class="lk-shortage-i__photos">
                            <div class="lk-shortage-i__label">Отправленное фото:</div>
                            <div>
                                <a href="<?= $photo ?>">
                                    <? if (!empty($photoName)) { ?>
                                        <svg class='i-icon'>
                                            <use xlink:href='#icon-bill'/>
                                        </svg>
                                    <? } ?>
                                    <span><?= $photoName ?></span>
                                </a>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        <? endforeach; ?>
    </div>
</div>


