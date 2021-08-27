<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();
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
global $APPLICATION; ?>
<script>
    function getTimeRemaining(endtime) {
        var t = Date.parse(endtime) - Date.parse(new Date());
        var seconds = Math.floor((t / 1000) % 60);
        var minutes = Math.floor((t / 1000 / 60) % 60);
        var hours = Math.floor((t / (1000 * 60 * 60)) % 24);
        var days = Math.floor(t / (1000 * 60 * 60 * 24));
        return {
            'total': t,
            'days': days,
            'hours': hours,
            'minutes': minutes,
            'seconds': seconds
        };
    }

    function initializeClock(id, endtime) {
        var clock = document.getElementById(id);
        var daysSpan = clock.querySelector('.days');
        var hoursSpan = clock.querySelector('.hours');
        var minutesSpan = clock.querySelector('.minutes');
        var secondsSpan = clock.querySelector('.seconds');

        function updateClock() {
            var t = getTimeRemaining(endtime);
            daysSpan.innerHTML = t.days;
            hoursSpan.innerHTML = ('0' + t.hours).slice(-2);
            minutesSpan.innerHTML = ('0' + t.minutes).slice(-2);
            secondsSpan.innerHTML = ('0' + t.seconds).slice(-2);

            if (t.total <= 0) {
                clearInterval(timeinterval);
            }
        }

        updateClock();
        var timeinterval = setInterval(updateClock, 1000);
    }

    var deadline="<?= $arResult['DEADLINE']; ?>";

    initializeClock('countdown', deadline);
</script>
<? foreach ($arResult['ITEMS'] as $item) {
    $this->AddEditAction($item['ID'], $item['EDIT_LINK'], CIBlock::GetArrayByID($item["IBLOCK_ID"], "ELEMENT_EDIT"));
    $this->AddDeleteAction($item['ID'], $item['DELETE_LINK'], CIBlock::GetArrayByID($item["IBLOCK_ID"], "ELEMENT_DELETE"), array("CONFIRM" => GetMessage('CT_BNL_ELEMENT_DELETE_CONFIRM')));
    ?>
    <div class="b-tabs__item active" id="<?= $this->GetEditAreaId($item['ID']); ?>">
      <div class="ed-webinar__banner">
        <div class="ed-webinar__inner">
          <div class="ed-webinar__title">
            Зарегистрируйтесь на вебинар<br>
            "Светодиодное освещение GAUSS"<br>
            прямо сейчас
          </div>
          <div class="ed-webinar__text">
            до начала мероприятия осталось
          </div>
          <div class="ed-webinar__timer" id="countdown">
              <div class="ed-webinar__timer-item">
                <span class="days ed-webinar__timer-time"></span>
                <span class="ed-webinar__timer-text">дней</span>
              </div>
              <div class="ed-webinar__timer-item">
                <span class="hours countdown-time"></span>
                <span class="ed-webinar__timer-text">часов</span>
              </div>
              <div class="ed-webinar__timer-item">
                <span class="minutes ed-webinar__timer-time"></span>
                <span class="ed-webinar__timer-text">минут</span>
              </div>
              <div class="ed-webinar__timer-item">
                <span class="seconds ed-webinar__timer-time"></span>
                <span class="ed-webinar__timer-text">секунд</span>
              </div>
          </div>
          <a class="btn btn--grey" href="<?= $item['DISPLAY_PROPERTIES']['LINK']['VALUE']; ?>" target="_blank">
            Зарегистрироваться
          </a>

        </div>
      </div>

        <a href="<?= $item['DISPLAY_PROPERTIES']['FILE']['FILE_VALUE']['SRC']; ?>"
           class="btn btn--download">
            <svg class='i-icon'>
                <use xlink:href='#icon-file'/>
            </svg>
            Скачать презентацию
        </a>

        <?php
        $fileExtension = pathinfo($item['DISPLAY_PROPERTIES']['FILE']['FILE_VALUE']['SRC'], PATHINFO_EXTENSION);
        ?>

        <?php if ('pdf' === $fileExtension) { ?>
            <a href="<?php echo $item['DISPLAY_PROPERTIES']['FILE']['FILE_VALUE']['SRC']; ?>"
               class="btn btn--download"
               target="_blank" download>
                <svg class='i-icon'>
                    <use xlink:href='#icon-file'/>
                </svg>
                Инструкция по подключению к платформе
            </a>
        <?php } ?>


    </div>
<? } ?>