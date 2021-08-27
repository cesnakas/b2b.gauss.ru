
# Модуль минификации (пока CSS) на лету
---
При открытии страницы проверяет список css на странице из папки /bitrix/cache/css и минифицирует их. Безвозвратно.

Если стили поменяются - битрикис заново соберет css, модуль заново их минифицирует.

google speed радуется


Файлы:
/bitrix/cache/css/SITE_ID/SITE_TEMPLATE/kernel_main...

/bitrix/cache/css/SITE_ID/SITE_TEMPLATE/page_...

/bitrix/cache/css/SITE_ID/SITE_TEMPLATE/page_template_...



### PS
1) Работает на https://github.com/matthiasmullie/minify

2) Не тестировали на живых проектах. Будьте осторожны.

3) Для js минификация не работает - они ломаются.