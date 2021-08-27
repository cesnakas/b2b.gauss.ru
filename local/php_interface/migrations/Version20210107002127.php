<?php

namespace Sprint\Migration;


class Version20210107002127 extends Version
{
    protected $description = "SUBSCRIPTION_PROMOTION и SUBSCRIPTION_NEWSLETTER";

    protected $moduleVersion = "3.17.2";

    /**
     * @throws Exceptions\HelperException
     * @return bool|void
     */
    public function up()
    {
        $helper = $this->getHelperManager();
        $helper->Event()->saveEventType('SUBSCRIPTION_NEWSLETTER', array (
  'LID' => 'ru',
  'EVENT_TYPE' => 'email',
  'NAME' => 'Рассылка новостей по подписке пользователя',
  'DESCRIPTION' => '#NEWS# - список новостей
#ARTICLES# - список статей
#EMAIL# - email пользователей
#USERNAME# - имя пользователя
#HOST# - хост сервера
',
  'SORT' => '150',
));
        $helper->Event()->saveEventType('SUBSCRIPTION_NEWSLETTER', array (
  'LID' => 'en',
  'EVENT_TYPE' => 'email',
  'NAME' => 'Distribution of new products and promotions by user subscription',
  'DESCRIPTION' => '',
  'SORT' => '150',
));
        $helper->Event()->saveEventMessage('SUBSCRIPTION_NEWSLETTER', array (
  'LID' => 
  array (
    0 => 's1',
  ),
  'ACTIVE' => 'Y',
  'EMAIL_FROM' => 'no-reply@b2b.gauss.ru',
  'EMAIL_TO' => '#EMAIL#',
  'SUBJECT' => 'Актуальные новости на Gauss',
  'MESSAGE' => '<tr>
    <td align="center" style="width: 100%; margin: 0; padding: 40px 0;">
        <table width="800" cellspacing="0" cellpadding="0" border="0">
            <tbody>
            <tr>
                <td style="width: 800px; min-width: 800px; padding: 25px 0 35px; background: #FFFFFF; border: 1px solid #DDDDE2;">
                    <table style="width: 100%;">
                        <tbody>
                        <!--НОВОСТИ-->
                        <tr>
                            <td style="padding: 10px 30px 5px; font-size: 18px;">
                                #USERNAME#, пока Вас не было на нашем сайте появились интересные новости:
                            </td>
                        </tr>
                        <tr>
                            <td style="padding: 0 10px;">
                                <table cellspacing="15" style="width: 100%;">
                                    <tbody>
                                    <tr>
                                        <?/*
                                            выводить максимум 3 статьи/новости;
                                            если количество элементов меньше трех, то td со стилями обязательно
                                            оставляем, не отображаем только таблицу */?>
                                        #NEWS#
                                    </tr>
                                    </tbody>
                                </table>
                            </td>
                        </tr>
                        <tr>
                            <td style="text-align: center;">
                                <a href="#HOST#/press-center/news/" target="_blank" style="display: block; width: 232px; margin: 0 auto; padding: 10px; border: 1px solid #232323; font-size: 14px; font-weight: 500; color: #1A1A1A; text-decoration: none;">
                                    Смотреть все новости
                                </a>
                            </td>
                        </tr>

                        <!--СТАТЬИ-->
                        <tr>
                            <td style="padding: 35px 30px 5px; font-size: 18px;">
                                А также новые статьи:
                            </td>
                        </tr>
                        <tr>
                            <td style="padding: 0 10px;">
                                <table cellspacing="15" style="width: 100%;">
                                    <tbody>
                                    <tr>
                                        <?/*
                                            выводить максимум 3 статьи/новости;
                                            если количество элементов меньше трех, то td со стилями обязательно
                                            оставляем, не отображаем только таблицу */?>
                                        #ARTICLES#
                                    </tr>
                                    </tbody>
                                </table>
                            </td>
                        </tr>
                        <tr>
                            <td style="text-align: center;">
                                <a href="#HOST#/press-center/articles/" target="_blank" style="display: block; width: 232px; margin: 0 auto; padding: 10px; border: 1px solid #232323; font-size: 14px; font-weight: 500; color: #1A1A1A; text-decoration: none;">
                                    Смотреть все статьи
                                </a>
                            </td>
                        </tr>
                        </tbody>
                    </table>
                </td>
            </tr>
            </tbody>
        </table>
    </td>
</tr>',
  'BODY_TYPE' => 'html',
  'BCC' => '',
  'REPLY_TO' => '',
  'CC' => '',
  'IN_REPLY_TO' => '',
  'PRIORITY' => '',
  'FIELD1_NAME' => '',
  'FIELD1_VALUE' => '',
  'FIELD2_NAME' => '',
  'FIELD2_VALUE' => '',
  'SITE_TEMPLATE_ID' => 'email-actions',
  'ADDITIONAL_FIELD' => 
  array (
  ),
  'LANGUAGE_ID' => 'ru',
  'EVENT_TYPE' => '[ SUBSCRIPTION_NEWSLETTER ] Рассылка новостей по подписке пользователя',
));
        $helper->Event()->saveEventType('SUBSCRIPTION_PROMOTION', array (
  'LID' => 'ru',
  'EVENT_TYPE' => 'email',
  'NAME' => 'Рассылка новинок и акций по подписке пользователя',
  'DESCRIPTION' => '#PROMOTIONS# - список акций
#NEWS_ITEMS# - список новинок
#EMAIL# - email пользователей, который подписаны на рассылку
#USERNAME# - имя пользователя
#HOST# - хост сервера',
  'SORT' => '150',
));
        $helper->Event()->saveEventType('SUBSCRIPTION_PROMOTION', array (
  'LID' => 'en',
  'EVENT_TYPE' => 'email',
  'NAME' => 'Newsletter by user subscription',
  'DESCRIPTION' => '',
  'SORT' => '150',
));
        $helper->Event()->saveEventMessage('SUBSCRIPTION_PROMOTION', array (
  'LID' => 
  array (
    0 => 's1',
  ),
  'ACTIVE' => 'Y',
  'EMAIL_FROM' => 'no-reply@b2b.gauss.ru',
  'EMAIL_TO' => '#EMAIL#',
  'SUBJECT' => 'Акции и новинки на Gauss',
  'MESSAGE' => '<tr>
    <td align="center" style="width: 100%; margin: 0; padding: 40px 0;">
        <table width="800" cellspacing="0" cellpadding="0" border="0">
            <tbody>
            <tr>
                <td style="width: 800px; min-width: 800px; padding: 25px 0 35px; background: #FFFFFF; border: 1px solid #DDDDE2;">
                    <table style="width: 100%;">
                        <tbody>

                        <!--НОВОСТИ-->
                        <tr>
                            <td style="padding: 10px 30px 5px; font-size: 18px;">
                                #USERNAME#, для Вас появились новые акции:
                            </td>
                        </tr>
                        <tr>
                            <td style="padding: 0 12px;">
                                <table cellspacing="17" style="width: 100%;">
                                    <tbody>
                                    <tr>
                                        <?/*
                                            выводить максимум 3 статьи/новости;
                                            если количество элементов меньше трех, то td со стилями обязательно
                                            оставляем, не отображаем только таблицу */?>
                                        #PROMOTIONS#
                                    </tr>
                                    </tbody>
                                </table>
                            </td>
                        </tr>
                        <tr>
                            <td style="text-align: center;">
                                <a href="#HOST#/promotions/" target="_blank" style="display: block; width: 232px; margin: 0 auto; padding: 10px; border: 1px solid #232323; font-size: 14px; font-weight: 500; color: #1A1A1A; text-decoration: none;">
                                    Смотреть все акции
                                </a>
                            </td>
                        </tr>

                        <!--СТАТЬИ-->
                        <tr>
                            <td style="padding: 60px 30px 5px; font-size: 18px;">
                                На сайте появились новинки:
                            </td>
                        </tr>
                        <tr>
                            <td style="padding: 0 10px;">
                                <table cellspacing="15" style="width: 100%;">
                                    <tbody>
                                    <tr>
                                        #NEWS_ITEMS#
                                    </tr>
                                    </tbody>
                                </table>
                            </td>
                        </tr>
                        <tr>
                            <td style="text-align: center;">
                                <a href="#HOST#/catalog/" target="_blank" style="display: block; width: 232px; margin: 0 auto; padding: 10px; border: 1px solid #232323; font-size: 14px; font-weight: 500; color: #1A1A1A; text-decoration: none;">
                                    Смотреть все новинки
                                </a>
                            </td>
                        </tr>

                        </tbody>
                    </table>
                </td>
            </tr>
            </tbody>
        </table>
    </td>
</tr>',
  'BODY_TYPE' => 'html',
  'BCC' => '',
  'REPLY_TO' => '',
  'CC' => '',
  'IN_REPLY_TO' => '',
  'PRIORITY' => '',
  'FIELD1_NAME' => '',
  'FIELD1_VALUE' => '',
  'FIELD2_NAME' => '',
  'FIELD2_VALUE' => '',
  'SITE_TEMPLATE_ID' => 'email-actions',
  'ADDITIONAL_FIELD' => 
  array (
  ),
  'LANGUAGE_ID' => '',
  'EVENT_TYPE' => '[ SUBSCRIPTION_PROMOTION ] Рассылка новинок и акций по подписке пользователя',
));
    }

    public function down()
    {
        //your code ...
    }
}
