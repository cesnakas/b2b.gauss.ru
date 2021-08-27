##citfact:mail.buffer.template

Компонет используется для формирования шаблона письма


Пример вызова:

```php
    global $APPLICATION;
    $templateBasket = $APPLICATION->IncludeComponent(
        "citfact:mail.buffer.template",
        "basket",
        Array(
            "ITEMS" => $productItems,
            "SITE_ID" => $sid,
            "MANUFACTURER_NAME" => $manufacturerName,
        )
    );
```


`$templateBasket` - возвращает весь html в переменную, без печати на страницу

`SITE_ID` испльзуется для определения сайта и языка.
(По умолчанию `Core::DEFAULT_SITE_ID`)

По `SITE_ID` определяется `FULL_DIR`



Пример шаблона :

```html
     <table>
         <tbody>
             <? foreach ($arResult['ITEMS'] as $item) { ?>
                 <tr>
                     <td>
                         <a href="<?= $arResult['FULL_DIR']; ?><?= $item['DETAIL_PAGE_URL']; ?>">
                             <img src="<?= $arResult['FULL_DIR']; ?><?= $item['PICTURE']['SRC']; ?>" alt="<?= $item['NAME']; ?>">
                         </a>
                     </td>
                     <td>
                         <a href="<?= $arResult['FULL_DIR']; ?><?= $item['DETAIL_PAGE_URL']; ?>">
                             <?= $item['NAME']; ?>
                         </a>
                     </td>
                     <td>
                         <?= $item['BASKET']['QUANTITY']; ?>
                     </td>
                     <td>
                         <?= $item['BASKET']['FINAL_PRICE']; ?>
                     </td>
                 </tr>
             <? } ?>
         </tbody>
     </table>
```