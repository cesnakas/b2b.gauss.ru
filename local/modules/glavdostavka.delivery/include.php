<?php

$module_id = 'glavdostavka.delivery';

CModule::AddAutoloadClasses(
    $module_id,
    array(
        "GlavDostavkaAPI" => "classes/general/GlavDostavkaAPI.php",
        "GlavDostavka" => "classes/general/GlavDostavka.php"
    )
);
