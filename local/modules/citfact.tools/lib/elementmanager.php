<?php

namespace Citfact\Tools;

class ElementManager
{
    public function formatPhone($phone)
    {
        if (preg_match('/^(\d{3})(\d{3})(\d{4})$/', $phone, $matches)) {
            $result = '+7 ('.$matches[1] . ') ' . $matches[2] . '-' . $matches[3];
            return $result;
        }

        return $phone;
    }

    /**
     * @param $iPropValues
     * @param $name
     * @param string $type
     */
    public static function setIpropValues($iPropValues, $name, $type = 'ELEMENT')
    {
        global $APPLICATION;
        if ($iPropValues[$type . '_PAGE_TITLE'] != '') {
            $APPLICATION->SetTitle($iPropValues[$type . '_PAGE_TITLE']);
        } elseif ($name) {
            $APPLICATION->SetTitle($name);
        }

        if ($iPropValues[$type . '_META_TITLE']) {
            $APPLICATION->SetPageProperty('title', $iPropValues[$type . '_META_TITLE']);
        } elseif ($name) {
            $APPLICATION->SetPageProperty('title', $name);
        }

        if ($iPropValues[$type . '_META_KEYWORDS']) {
            $APPLICATION->SetPageProperty('keywords', $iPropValues[$type . '_META_KEYWORDS']);
        }

        if ($iPropValues[$type . '_META_DESCRIPTION']) {
            $APPLICATION->SetPageProperty('description', $iPropValues[$type . '_META_DESCRIPTION']);
        }
    }
}