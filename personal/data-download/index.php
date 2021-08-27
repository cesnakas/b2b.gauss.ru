<?
define("NEED_AUTH", true);

use Citfact\SiteCore\Definition\Mobile;
use Citfact\SiteCore\Core;

require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/header.php");
$APPLICATION->SetTitle("Выгрузка данных");

global $USER;
$userId = $GLOBALS["USER"]->GetID();
?>

    <div class="lk__section lk-download">

        <div class="b-tabs" data-tab-group>
            <div class="b-tabs-head" data-tab-header>
                <a href="javascript:void(0);" class="b-tabs-link active" data-tab-btn="ftp">Скачивание c FTP</a>
                <a href="javascript:void(0);" class="b-tabs-link" data-tab-btn="etim">Свойство ETIM</a>
            </div>
            <div class="b-tabs__content" data-tab-content>
                <div class="b-tabs__item lk-download__tab--ftp active" data-tab-body="ftp">
                    <div class="lk-download__text">
                        <p>
                        <?$APPLICATION->IncludeComponent(
                            "bitrix:main.include",
                            "",
                            Array(
                                "AREA_FILE_SHOW" => "file",
                                "AREA_FILE_SUFFIX" => "inc",
                                "EDIT_TEMPLATE" => "",
                                "PATH" => "/local/include/areas/personal/data-download/description.php"
                            )
                        );?>
                        </p>
                        <p>
                        <?$APPLICATION->IncludeComponent(
                            "bitrix:main.include",
                            "",
                            Array(
                                "AREA_FILE_SHOW" => "file",
                                "AREA_FILE_SUFFIX" => "inc",
                                "EDIT_TEMPLATE" => "",
                                "PATH" => "/local/include/areas/personal/data-download/login-data.php"
                            )
                        );?>
                        </p>
                    </div>
                    <div class="lk-download__form">
                        <div class="title-2">Преимущества</div>
                        <p>- Пакетное скачивание</p>
                        <p>- Простая навигация</p>
                        <div class="lk-download__btn">
                            <a href="https://file.varton.ru" class="btn btn--transparent" target="_blank">Перейти к скачиванию</a>
                        </div>
                    </div>
                </div>
                <div class="b-tabs__item lk-download__tab--etim" data-tab-body="etim">
                    <div class="lk-download__text">
                        <?$APPLICATION->IncludeComponent(
                            "bitrix:main.include",
                            "",
                            Array(
                                "AREA_FILE_SHOW" => "file",
                                "AREA_FILE_SUFFIX" => "inc",
                                "EDIT_TEMPLATE" => "",
                                "PATH" => "/local/include/areas/personal/data-download/etim.php"
                            )
                        );?>
                    </div>
                    <div class="lk-download__form">
                        <div class="title-2">Формат</div>
                        <form class="b-form" method="post">
                            <div class="b-form__item">
                                <div class="b-checkbox b-checkbox--radio">
                                    <label class="b-checkbox__label">
                                        <input type="radio" id="form_radio_1" name="form_radio_TYPE" value="1" class="b-checkbox__input" checked data-url='/upload/orders/docs/etim/features_ETIM.xlsx'>
                                        <span class="b-checkbox__box"></span>
                                        <span class="b-checkbox__text">XLMS с минимальной розничной ценой</span>
                                    </label>
                                </div>
                                <div class="b-checkbox b-checkbox--radio">
                                    <label class="b-checkbox__label">
                                        <input type="radio" id="form_radio_2" name="form_radio_TYPE" value="2" class="b-checkbox__input" data-url='/upload/orders/docs/etim/features_ETIM.csv'>
                                        <span class="b-checkbox__box"></span>
                                        <span class="b-checkbox__text">CSV с минимальной розничной ценой</span>
                                    </label>
                                </div>
                                <div class="b-checkbox b-checkbox--radio">
                                    <label class="b-checkbox__label">
                                        <input type="radio" id="form_radio_3" name="form_radio_TYPE" value="3" class="b-checkbox__input" data-url='/upload/orders/docs/etim/features_ETIM.yml'>
                                        <span class="b-checkbox__box"></span>
                                        <span class="b-checkbox__text">YML с минимальной розничной ценой</span>
                                    </label>
                                </div>
                            </div>
                            <div class="b-form__bottom">
                                <a class="btn btn--transparent" href="/upload/orders/docs/etim/features_ETIM.xlsx"
                                   id="formatUrl"  download >
                                    <span>Скачать файл выгрузки</span>
                                    <svg class='i-icon'>
                                        <use xlink:href='#icon-file'/>
                                    </svg>
                                </a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
        const a = document.getElementById("formatUrl");
        const inputs = document.querySelectorAll('.b-checkbox__input');
        inputs.forEach(i => i.addEventListener('click', (e)=> {a.href= e.target.dataset.url}));
    </script>

<? require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/footer.php"); ?>