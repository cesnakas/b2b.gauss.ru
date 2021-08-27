<?
use Citfact\SiteCore\Core;

require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/header.php");
$APPLICATION->SetPageProperty("class_page", "main--about");
$APPLICATION->SetPageProperty("TITLE", "B2B Портал");
$APPLICATION->SetTitle("B2B Портал");

$core = Core::getInstance();
?>

<div class="b2b">
    <div class="b2b__section b2b__section--white">
        <div class="b2b__wrapper">
            <div class="b2b__img lazy" style="background-image: url(/local/client/img/section-1.jpg);"></div>
            <div class="b2b__inner">
                <div class="b2b__main">
                    <svg class="i-icon">
                        <use xlink:href="#icon-logo-white"></use>
                    </svg>
                    <div class="b2b__main-title">
                        <h1>B2B Портал</h1>
                        <a href="www.b2b.gauss.ru" class="b2b__link">www.b2b.gauss.ru</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="b2b__section">
        <div class="b2b__wrapper">
            <div class="b2b__img lazy" data-animation="fadeIn" style="background-color: #fff; background-image: url(/local/client/img/section-2.png);"></div>
            <div class="b2b__inner">
                <div class="b2b__content">
                    <div class="b2b__subtitle wow fadeInLeftSmall">Зачем нужен b2b.Gauss.ru</div>
                    <div class="b2b__items">
                        <div class="b2b__item wow fadeInLeftSmall">
                            <div class="b2b__item-number">01</div>
                            <div>
                                <div class="b2b__item-title">Просто</div>
                                <div class="b2b__item-subtitle">
                                    Качественно новый уровень сервиса для&nbsp;B2B-клиентов компании
                                </div>
                                <div class="b2b__item-text">
                                    Ускоренная обработка заказов, актуальные остатки, отгрузочные документы
                                </div>
                            </div>
                        </div>
                        <div class="b2b__item wow fadeInLeftSmall">
                            <div class="b2b__item-number">02</div>
                            <div>
                                <div class="b2b__item-title">Легко</div>
                                <div class="b2b__item-subtitle">
                                    Прямые коммуникации
                                </div>
                                <div class="b2b__item-text">
                                    и актуальная информация о&nbsp;бренде Gauss (акции, техническая документация,
                                    маркетинговые материалы, листовки, пресс-центр)
                                </div>
                            </div>
                        </div>
                        <div class="b2b__item wow fadeInLeftSmall">
                            <div class="b2b__item-number">03</div>
                            <div>
                                <div class="b2b__item-title">Удобно</div>
                                <div class="b2b__item-subtitle">
                                    Автоматизация ежедневных бизнес-процессов
                                </div>
                                <div class="b2b__item-text">
                                    и минимизация рутинных операций
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="b2b__section b2b__section--white">
        <div class="b2b__wrapper">
            <div class="b2b__img lazy" data-animation="fadeIn" style="background-image: url(/local/client/img/section-3.jpg);"></div>
            <div class="b2b__inner">
                <div class="b2b__list-wrapper">
                    <div class="b2b__title wow fadeInRightSmall">
                        Просто
                    </div>
                    <div class="b2b__list">
                        <div class="b2b__list-item wow fadeInRightSmall">
                            <svg class="i-icon">
                                <use xlink:href="#icon-icon-hand"></use>
                            </svg>
                            <div>Оформляй заказ в&nbsp;один клик с&nbsp;функцией «Быстрый заказ»</div>
                        </div>
                        <div class="b2b__list-item wow fadeInRightSmall">
                            <svg class="i-icon">
                                <use xlink:href="#icon-icon-notebook"></use>
                            </svg>
                            <div>Получай актуальную информация о&nbsp;статусе заказа</div>
                        </div>
                        <div class="b2b__list-item wow fadeInRightSmall">
                            <svg class="i-icon">
                                <use xlink:href="#icon-icon-box"></use>
                            </svg>
                            <div>Резервируй товар на&nbsp;складе в&nbsp;момент оформления заказа</div>
                        </div>
                        <div class="b2b__list-item wow fadeInRightSmall">
                            <svg class="i-icon">
                                <use xlink:href="#icon-icon-mouse"></use>
                            </svg>
                            <div>Отслеживай наличие и актульность товара</div>
                        </div>
                        <div class="b2b__list-item wow fadeInRightSmall">
                            <svg class="i-icon">
                                <use xlink:href="#icon-icon-folders"></use>
                            </svg>
                            <div>Загружай отгрузочные документы онлайн</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="b2b__section">
        <div class="b2b__wrapper">
            <div class="b2b__img lazy" data-animation="fadeIn" style="background-image: url(/local/client/img/section-4.jpg);"></div>
            <div class="b2b__inner">
                <div class="b2b__list-wrapper">
                    <div class="b2b__title wow fadeInLeftSmall">Легко</div>
                    <div class="b2b__list">
                        <div class="b2b__list-item wow fadeInLeftSmall">
                            <svg class="i-icon">
                                <use xlink:href="#icon-icon-doc"></use>
                            </svg>
                            <div>Документооборот</div>
                        </div>
                        <div class="b2b__list-item wow fadeInLeftSmall">
                            <svg class="i-icon">
                                <use xlink:href="#icon-icon-notebook"></use>
                            </svg>
                            <div>Актуальный прайс-лист</div>
                        </div>
                        <div class="b2b__list-item wow fadeInLeftSmall">
                            <svg class="i-icon">
                                <use xlink:href="#icon-icon-folders"></use>
                            </svg>
                            <div>Возможность для&nbsp;работы от&nbsp;нескольких юр.лиц</div>
                        </div>
                        <div class="b2b__list-item wow fadeInLeftSmall">
                            <svg class="i-icon">
                                <use xlink:href="#icon-icon-mouse"></use>
                            </svg>
                            <div>Мои заказы - блок с&nbsp;активными заказами пользователя</div>
                        </div>
                        <div class="b2b__list-item wow fadeInLeftSmall">
                            <svg class="i-icon">
                                <use xlink:href="#icon-icon-hand"></use>
                            </svg>
                            <div>Дебиторская задолженность</div>
                        </div>
                        <div class="b2b__list-item wow fadeInLeftSmall">
                            <svg class="i-icon">
                                <use xlink:href="#icon-icon-box"></use>
                            </svg>
                            <div>План-факт (статистика отгрузок)</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="b2b__section b2b__section--white">
        <div class="b2b__wrapper">
            <div class="b2b__img lazy" data-animation="fadeIn" style="background-image: url(/local/client/img/section-5.jpg);"></div>
            <div class="b2b__inner">
                <div class="b2b__list-wrapper">
                    <div class="b2b__title wow fadeInRightSmall">Удобно</div>
                    <div class="b2b__list">
                        <div class="b2b__list-item wow fadeInRightSmall">
                            <svg class="i-icon">
                                <use xlink:href="#icon-icon-book"></use>
                            </svg>
                            <div>Обучающий центр (презентация, тесты, обучающие видео, вебинары, академия gauss)</div>
                        </div>
                        <div class="b2b__list-item wow fadeInRightSmall">
                            <svg class="i-icon">
                                <use xlink:href="#icon-icon-map"></use>
                            </svg>
                            <div>Маркетинговая поддержка (презентации, торговое оборудование, каталоги и листовки)</div>
                        </div>
                        <div class="b2b__list-item wow fadeInRightSmall">
                            <svg class="i-icon">
                                <use xlink:href="#icon-icon-doc"></use>
                            </svg>
                            <div>Техническая документация (сертификаты качества, инструкции, IES файлы)</div>
                        </div>
                        <div class="b2b__list-item wow fadeInRightSmall">
                            <svg class="i-icon">
                                <use xlink:href="#icon-icon-camera"></use>
                            </svg>
                            <div>Пресс-центр (новости, статьи, фото и видео материалы)</div>
                        </div>
                        <div class="b2b__list-item wow fadeInRightSmall">
                            <svg class="i-icon">
                                <use xlink:href="#icon-icon-award"></use>
                            </svg>
                            <div>Акции, хиты продаж, новинки</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="b2b__section">
        <div class="b2b__wrapper">
            <div class="b2b__img lazy" data-animation="fadeIn" style="background-image: url(/local/client/img/section-6.jpg);"></div>
            <div class="b2b__inner">
                <div class="b2b__content">
                    <div class="b2b__items b2b__items--last">
                        <div class="b2b__item wow fadeInLeftSmall">
                            <div class="b2b__item-number">01</div>
                            <div>
                                <div class="b2b__item-title">Получите приглашение на&nbsp;почту</div>
                            </div>
                        </div>
                        <div class="b2b__item wow fadeInLeftSmall">
                            <div class="b2b__item-number">02</div>
                            <div>
                                <div class="b2b__item-title">Пройдите быструю авторизацию</div>
                            </div>
                        </div>
                        <div class="b2b__item wow fadeInLeftSmall">
                            <div class="b2b__item-number">03</div>
                            <div>
                                <div class="b2b__item-title">Почувствуйте удобство и комфорт работы на&nbsp;портале&nbsp;*</div>
                                <div class="b2b__item-note">
                                    * Дополнительный бонус в&nbsp;2% доступен с&nbsp;первого заказа
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<? require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/footer.php"); ?>