### Выгрузка 1С обработчики ###
<pre>
Метод updateOrderStatus вызывается при загрузке "references_productsStatuses.xml".
Устанавливет статусы заказов в "b_sale_order" в "выполнен" если у всех элементов 
ХЛ-блока "StatusyTovarov" стоит статус "Отгружен". При это этом необходимо проверить, 
что в ХЛ-блоке "StatusyTovarov" отображены статусы всех товаров из корзины иначе по отсутствующим 
товарам статусы не известны и заказ не выполнен.

Алгоритм:
     - Выбираем все элементы из ХЛ-блока, если у всех товаров заказа стоит статус "Отгружен"
     - Сохраняем строки в $shippedOrders, если у всех товаров заказа стоит статус "Отгружен".
       Делается для дальнейшей проверки на то, что в ХЛ-блоке были данные по всем товарам заказа.
       В $shippedOrderID_1C сохраняем их ID 1С.
     - Строим двумерный массив по ID 1С заказа и ID 1С товара, чтобы
       можно было быстро проверить есть ли товар в ХЛ блоке.
     - По $shippedOrderID_1C получаем ID заказов из b_sale_order
     - Получаем товары из корзины (b_sale_basket) для полученых ID заказов.
     - Пробегаем по всем товарам и проверяем был текущий товар в ХЛ-блоке StatusyTovarov с
       помощью массива полученного в пункте 3
     - Если хотя бы один товар не был в ХЛ-блоке StatusyTovarov, то с заказом ничего не делаем иначе устанавливаем
       статус заказ "выполнен"
 </pre>