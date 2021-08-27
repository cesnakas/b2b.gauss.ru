<?
$sSectionName = ($_GET['register'] === 'yes') ? "Регистрация" :
    (($_GET['forgot_password'] === 'yes') ? "Запрос пароля" :
        (($_GET['change_password'] === 'yes') ? "Изменение пароля" : 'Авторизация'));
$arDirProperties = [];