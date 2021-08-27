<?

namespace Citfact\SiteCore\OrderImport;


use Bitrix\Sale\Exchange\OneC\OrderDocument;

class OrderDocumentCustom extends OrderDocument
{
    /**
     * @param array $value
     * @return null
     */
    public static function getAllTraits(array $value)
    {
        $traits = [];
        $message = self::getMessage();

        if (is_array($value["#"][$message["CC_BSC1_REK_VALUES"]][0]["#"][$message["CC_BSC1_REK_VALUE"]])
            && !empty($value["#"][$message["CC_BSC1_REK_VALUES"]][0]["#"][$message["CC_BSC1_REK_VALUE"]])) {
            $traits = [];
            foreach ($value["#"][$message["CC_BSC1_REK_VALUES"]][0]["#"][$message["CC_BSC1_REK_VALUE"]] as $val) {
                $traitName = $val["#"][$message["CC_BSC1_NAME"]][0]["#"];
                $traitValue = $val["#"][$message["CC_BSC1_VALUE"]][0]["#"];
                if (strlen($traitValue) > 0)
                    $traits[$traitName] = $traitValue;
            }
        }
        return $traits;
    }
}
