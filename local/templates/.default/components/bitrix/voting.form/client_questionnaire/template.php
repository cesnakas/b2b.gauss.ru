<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

if (!empty($arResult["OK_MESSAGE"])):
    ?>
    <div class="vote-note-box vote-note-note">
        <div class="vote-note-box-text"><?= ShowNote($arResult["OK_MESSAGE"]) ?></div>
    </div>
<?
endif;

if (empty($arResult["VOTE"])):
    return false;
elseif (empty($arResult["QUESTIONS"])):
    return true;
endif;
?>
<div class="voting-form">
    <form action="<?= POST_FORM_ACTION_URI ?>" method="post" class="b-form" data-vote-form>
        <input type="hidden" name="vote" value="Y">
        <input type="hidden" name="PUBLIC_VOTE_ID" value="<?= $arResult["VOTE"]["ID"] ?>">
        <input type="hidden" name="VOTE_ID" value="<?= $arResult["VOTE"]["ID"] ?>">
        <?= bitrix_sessid_post() ?>

        <div class="title-1">
            <span>Нам важно Ваше мнение!</span>
        </div>

        <div class="voting-form-items">
            <? foreach ($arResult["QUESTIONS"] as $arQuestion): ?>
                <div class="voting-form-item">
                    <div class="voting-form-item__question " <?if($arQuestion['QUESTION'] == 'Контрагент'):?> style="display: none"<?endif;?>>
                        <? if ($arQuestion['QUESTION'] != "Комментарий по оценке функционала" && $arQuestion['QUESTION'] != "Комментарий по оценке портала") {
                            if ($arQuestion["REQUIRED"] == "Y") {
                                ?>
                                *
                            <? } ?>
                            <?= $arQuestion["QUESTION"] ?>
                            <?
                        } ?>
                    </div>
                    <? if ($arQuestion['QUESTION'] == "Дайте общую оценку нашего портала."): ?>
                        <div class="slidecontainer">
                            <input type="range" min="1" max="10" value="1" class="slider" id="myRange">
                        </div>
                        <div class="voting-form-range">
                            <? foreach ($arQuestion["ANSWERS"] as $arAnswer): ?>
                                <? if ($arAnswer["FIELD_TYPE"] == 0): ?>
                                    <? $value = (isset($_REQUEST['vote_radio_' . $arAnswer["QUESTION_ID"]]) &&
                                        $_REQUEST['vote_radio_' . $arAnswer["QUESTION_ID"]] == $arAnswer["ID"]) ? 'checked="checked"' : ''; ?>
                                    <div class="b-checkbox b-checkbox--radio" data-vote-point>
                                        <label for="vote_radio_<?= $arAnswer["QUESTION_ID"] ?>_<?= $arAnswer["ID"] ?>"
                                               class="b-checkbox__label">
                                            <input class="b-checkbox__input" type="radio" <?= $value ?>
                                                   name="vote_radio_<?= $arAnswer["QUESTION_ID"] ?>"
                                                   id="vote_radio_<?= $arAnswer["QUESTION_ID"] ?>_<?= $arAnswer["ID"] ?>"
                                                   value="<?= $arAnswer["ID"] ?>" <?= $arAnswer["~FIELD_PARAM"] ?>/>
                                            <span class="b-checkbox__box"></span>
                                            <span class="b-checkbox__text"><?= $arAnswer["MESSAGE"] ?></span>
                                        </label>
                                    </div>
                                <? endif; ?>
                            <? endforeach; ?>
                        </div>
                        <script>
                            var arrayPoint = [];
                            arrayPoint = document.querySelectorAll('[data-vote-point]');
                            var range = document.querySelector('#myRange');
                            range.addEventListener('change', function () {
                                let indexActive = this.value;
                                for (var i = 0; i < arrayPoint.length; i++) {
                                    $('arrayPoint[i]').attr('checked', false);
                                    if (i + 1 == indexActive)
                                        $(arrayPoint[i]).find('input').attr('checked', true);

                                }
                                var percent = (indexActive * 10);
                                $('#myRange').css('background', 'linear-gradient(to right, #F7971D calc(' + percent + '% - 24px), transparent calc(' + percent + '% - 24px))');
                            }, false);

                            range.addEventListener('input', function () {
                                let indexActive = this.value;
                                for (var i = 0; i < arrayPoint.length; i++) {
                                    $('arrayPoint[i]').attr('checked', false);
                                    if (i + 1 == indexActive)
                                        $(arrayPoint[i]).find('input').attr('checked', true);
                                }
                            }, false);
                        </script>

                        <div class="voting-form-range-d" style="margin-bottom: 10px; font-style: italic">
                            <span>1-3 – Не удовлетворительно</span>
                            <span>4-5 – Удовлетворительно</span>
                            <span>6-7 – Хорошо</span>
                            <span>8-10 – Отлично</span>
                        </div>
                    <? else: ?>
                        <div class="voting-form-item__answers">
                            <? foreach ($arQuestion["ANSWERS"] as $arAnswer): ?>
                                <?
                                switch ($arAnswer["FIELD_TYPE"]):
                                    case 0://radio
                                        $value = (isset($_REQUEST['vote_radio_' . $arAnswer["QUESTION_ID"]]) &&
                                            $_REQUEST['vote_radio_' . $arAnswer["QUESTION_ID"]] == $arAnswer["ID"]) ? 'checked="checked"' : '';
                                        break;
                                    case 1://checkbox
                                        $value = (isset($_REQUEST['vote_checkbox_' . $arAnswer["QUESTION_ID"]]) &&
                                            array_search($arAnswer["ID"], $_REQUEST['vote_checkbox_' . $arAnswer["QUESTION_ID"]]) !== false) ? 'checked="checked"' : '';
                                        break;
                                    case 2://select
                                        $value = (isset($_REQUEST['vote_dropdown_' . $arAnswer["QUESTION_ID"]])) ? $_REQUEST['vote_dropdown_' . $arAnswer["QUESTION_ID"]] : false;
                                        break;
                                    case 3://multiselect
                                        $value = (isset($_REQUEST['vote_multiselect_' . $arAnswer["QUESTION_ID"]])) ? $_REQUEST['vote_multiselect_' . $arAnswer["QUESTION_ID"]] : array();
                                        break;
                                    case 4://text field
                                        $value = isset($_REQUEST['vote_field_' . $arAnswer["ID"]]) ? htmlspecialcharsbx($_REQUEST['vote_field_' . $arAnswer["ID"]]) : '';
                                        break;
                                    case 5://memo
                                        $value = isset($_REQUEST['vote_memo_' . $arAnswer["ID"]]) ? htmlspecialcharsbx($_REQUEST['vote_memo_' . $arAnswer["ID"]]) : '';
                                        break;
                                endswitch; ?>

                                <? switch ($arAnswer["FIELD_TYPE"]):

                                    case 0://radio
                                        ?>
                                        <div class="b-checkbox b-checkbox--radio">
                                            <label for="vote_radio_<?= $arAnswer["QUESTION_ID"] ?>_<?= $arAnswer["ID"] ?>"
                                                   class="b-checkbox__label">
                                                <input class="b-checkbox__input" type="radio" <?= $value ?>
                                                       name="vote_radio_<?= $arAnswer["QUESTION_ID"] ?>"
                                                       id="vote_radio_<?= $arAnswer["QUESTION_ID"] ?>_<?= $arAnswer["ID"] ?>"
                                                       value="<?= $arAnswer["ID"] ?>" <?= $arAnswer["~FIELD_PARAM"] ?>/>
                                                <span class="b-checkbox__box"></span>
                                                <span class="b-checkbox__text"><?= $arAnswer["MESSAGE"] ?></span>
                                            </label>
                                        </div>

                                        <? break;

                                    case 1://checkbox
                                        ?>
                                        <div class="b-checkbox">
                                            <label for="vote_checkbox_<?= $arAnswer["QUESTION_ID"] ?>_<?= $arAnswer["ID"] ?>"
                                                   class="b-checkbox__label">
                                                <input type="checkbox" class="b-checkbox__input"
                                                       id="vote_checkbox_<?= $arAnswer["QUESTION_ID"] ?>_<?= $arAnswer["ID"] ?>"
                                                       name="vote_checkbox_<?= $arAnswer["QUESTION_ID"] ?>[]"
                                                        <?= $value ?>
                                                       value="<?= $arAnswer["ID"] ?>" <?= $arAnswer["~FIELD_PARAM"] ?>>
                                                <span class="b-checkbox__box">
                                                     <span class="b-checkbox__line b-checkbox__line--short"></span>
                                                     <span class="b-checkbox__line b-checkbox__line--long"></span>
                                                </span>
                                                <span class="b-checkbox__text"><?= $arAnswer["MESSAGE"] ?></span>
                                            </label>
                                        </div>
                                        <? break;

                                    case 2://dropdown
                                        ?>
                                        <select name="vote_dropdown_<?= $arAnswer["QUESTION_ID"] ?>" <?= $arAnswer["~FIELD_PARAM"] ?>>
                                            <option value="" disabled selected
                                                    hidden><?= GetMessage("VOTE_DROPDOWN_SET") ?></option>
                                            <?
                                            foreach ($arAnswer["DROPDOWN"] as $arDropDown):?>
                                                <option value="<?= $arDropDown["ID"] ?>" <?= ($arDropDown["ID"] === $value) ? 'selected="selected"' : '' ?>><?= $arDropDown["MESSAGE"] ?></option>
                                            <? endforeach ?>
                                        </select>
                                        <? break;

                                    case 3://multiselect
                                        ?>
                                        <span class="vote-answer-item vote-answer-item-multiselect" >
                                        <select name="vote_multiselect_<?= $arAnswer["QUESTION_ID"] ?>[]" <?= $arAnswer["~FIELD_PARAM"] ?> multiple="multiple">
                                        <?
                                        foreach ($arAnswer["MULTISELECT"] as $arMultiSelect):?>
                                            <option value="<?= $arMultiSelect["ID"] ?>" <?= (array_search($arMultiSelect["ID"], $value) !== false) ? 'selected="selected"' : '' ?>><?= $arMultiSelect["MESSAGE"] ?></option>
                                        <? endforeach ?>
                                        </select>
                                    </span>
                                        <? break;

                                    case 4://text field
                                        ?>
                                        <div class="b-form__item" <?if($arQuestion['QUESTION'] == 'Контрагент'):?> style="display: none"<?endif;?>>
                                            <input placeholder="<?= $arAnswer["MESSAGE"] ?>" type="text"
                                                   class="vote-item-text" maxlength="500"
                                                   name="vote_field_<?= $arAnswer["ID"] ?>"
                                                   id="vote_field_<?= $arAnswer["ID"] ?>"
                                                   <?if($arQuestion['QUESTION'] == 'Контрагент'):?>
                                                        value="<?=$arResult['CONTRAGENT_INFO']?>"
                                                   <?else:?>
                                                       value="<?= $value ?>"
                                                   <?endif;?>
                                                <? if ($arQuestion['REQUIRED'] == 'Y' && $arQuestion['QUESTION'] != "Выделите критерии, которые вам больше всего понравились на портале."): ?>
                                                    data-vote-text
                                                    required <? endif; ?>
                                                   size="<?= $arAnswer["FIELD_WIDTH"] ?>" <?= $arAnswer["~FIELD_PARAM"] ?>
                                                   class="keyCombination"/>
                                        </div>
                                        <? break;

                                    case 5://memo
                                        ?>
                                        <span class="vote-answer-item vote-answer-item-memo">
                                        <label for="vote_memo_<?= $arAnswer["ID"] ?>"><?= $arAnswer["MESSAGE"] ?></label><br/>
                                        <textarea name="vote_memo_<?= $arAnswer["ID"] ?>"
                                                  id="vote_memo_<?= $arAnswer["ID"] ?>" <?
                                        ?><?= $arAnswer["~FIELD_PARAM"] ?> cols="<?= $arAnswer["FIELD_WIDTH"] ?>" <?
                                                  ?>rows="<?= $arAnswer["FIELD_HEIGHT"] ?>"><?= $value ?></textarea>
                                    </span>
                                        <?
                                        break;
                                endswitch;
                                ?>
                            <?
                            endforeach
                            ?>
                        </div>
                    <? endif; ?>
                </div>
            <?
            endforeach
            ?>
        </div>

        <? if (isset($arResult["CAPTCHA_CODE"])): ?>
            <div class="vote-item-header">
                <div class="vote-item-title vote-item-question"><?= GetMessage("F_CAPTCHA_TITLE") ?></div>
                <div class="vote-clear-float"></div>
            </div>
            <div class="vote-form-captcha">
                <input type="hidden" name="captcha_code" value="<?= $arResult["CAPTCHA_CODE"] ?>"/>
                <div class="vote-reply-field-captcha-image">
                    <img src="/bitrix/tools/captcha.php?captcha_code=<?= $arResult["CAPTCHA_CODE"] ?>"
                         alt="<?= GetMessage("F_CAPTCHA_TITLE") ?>"/>
                </div>
                <div class="vote-reply-field-captcha-label">
                    <label for="captcha_word"><?= GetMessage("F_CAPTCHA_PROMT") ?></label><br/>
                    <input type="text" size="20" name="captcha_word" autocomplete="off"/>
                </div>
            </div>
        <? endif // CAPTCHA_CODE ?>

        <input type="hidden" name="vote" value="Y">

        <div class="b-modal__bottom">
            <div class="b-form__pp">
                Нажимая на кнопку, я подтверждаю свое согласие на
                <a href="/policy/" rel="noopener noreferrer" title="Политика в отношении обработки персональных данных"
                   target="_blank">«Политику в отношении обработки персональных данных»</a>
            </div>
            <button class="btn btn--transparent btn--big" type="submit" id="vote-btn">
                <?= GetMessage("VOTE_SUBMIT_BUTTON") ?>
            </button>
        </div>

    </form>

</div>

<? if (!empty($arResult["ERROR_MESSAGE"])): ?>
    <div class="vote-note-box vote-note-error">
        <div class="vote-note-box-text red" id="errorVote">Вы не ответили на все обязательные вопросы</div>
    </div>
<? endif; ?>

<script>

    (function () {
        var voteFormClient = document.querySelector("[data-vote-form]");
        const inputs = document.querySelectorAll('input[data-vote-text]');
        const voteButton = document.querySelector('#vote-btn');

        function findParent (node, className)  {
            while (node) {
                if (node.classList.contains(className)) {
                    return node;
                } else {
                    node = node.parentElement;
                }
            }
            return null;
        }

        voteFormClient.addEventListener('keydown', function (e) {
            if (e.key === 'Enter') {
                e.preventDefault();
            }
        });

        voteButton.addEventListener('click', function (e) {
            var errInputs = false;

            inputs.forEach(function (input) {

                if (!checkInput(input.value)) {
                    errInputs = true;
                    input.value = "";
                    const parent = findParent(input, 'voting-form-item');
                    if (parent) {
                        const question = parent.querySelector('.voting-form-item__question');
                        if (question) {
                            question.classList.add('error');
                            input.classList.add('error');
                        }
                    }
                }
            })

            if (errInputs) {
                e.preventDefault();
            }
        });

        inputs.forEach(function (input) {
            const parent = findParent(input, 'voting-form-item');
            var question = '';
            if (parent) {
                question = parent.querySelector('.voting-form-item__question');
            }

            input.addEventListener("change", function (e) {
                input.classList.remove('error')
                if (question) {
                    question.classList.remove('error');
                }
            })
        })

        function checkInput(text_from_input) {
            return /[^\s]/gim.test(text_from_input);
        }
    })();

    if (typeof Ac !== 'undefined') {
        Ac.select.run();
    } else {
        document.addEventListener('App.Ready', function (e) {
            Ac.select.run();
        })
    }

    BX.ready(function(){
        var pos = $('#errorVote').offset();
        if(pos) {
            $('html,body').animate({scrollTop: pos.top - 170}, 500);
        }
    });

</script>
