<?php

/*
 * This file is part of the Studio Fact package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

use Bitrix\Main\Localization\Loc;

Loc::loadMessages(__FILE__);

$menuList = array(
	0 => Array(
        'parent_menu' => 'global_menu_sitecore',
        'sort' => 10,
		'text' => 'Настройки',
		'title' => 'Настройки',
		'url' => 'citfact_sitecore_homepage.php',
		'icon' => 'sys_menu_icon',
        'items' => Array(
            /*0 => Array(
                'text' => 'Товарные рекомендации',
                'title' => 'Настройка блока товарных рекомендаций в карточке товара',
                'url' => 'citfact_tools_similar.php',
                'icon' => 'fileman_sticker_icon',
                'page_icon' => 'fileman_sticker_icon_sections',
                'items_id' => 'menu_testsubsection',
            )*/
        )
	),
    1 => Array(
        'parent_menu' => 'global_menu_sitecore',
        'sort' => 10,
        'text' => 'Настройки выгрузки свойств',
        'title' => 'Настройки',
        'url' => 'citfact_sitecore_properties.php',
        'icon' => 'sys_menu_icon',
        'items' => Array(
        )
    ),
);

return isset($menuList) ? $menuList : array();
