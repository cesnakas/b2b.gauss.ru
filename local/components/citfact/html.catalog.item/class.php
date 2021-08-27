<?
if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) {
    die();
}

use Citfact\Sitecore\CatalogHelper\ElementRepository;
use Citfact\SiteCore\Core;

class HtmlComponent extends \CBitrixComponent
{
    /**
     * {@inheritdoc}
     */
    public function executeComponent()
    {
        if (!$this->arParams['ITEM']) {
            return;
        }
        $elementRepository = new ElementRepository();
        $this->arParams['ITEM'] = $elementRepository->getAdditionalInfo($this->arParams['ITEM'], $this->arParams['SECTION']);

        if (!$this->arParams['PRODUCT_IMAGE_SIZE'] || !$this->arParams['PRODUCT_IMAGE_SIZE'][0] || !$this->arParams['PRODUCT_IMAGE_SIZE'][1]) {
            $this->arParams['PRODUCT_IMAGE_SIZE'] = array(244, 172);
        }

        //$this->arParams['ITEM'] = $this->setAndFormatItemPrice($this->arParams['ITEM']);
        $this->arParams['ITEM']['NAME'] = htmlspecialchars_decode($this->arParams['ITEM']['NAME']);
        if ($this->arParams['PRODUCT_IMAGE_NOT_RESIZE'] != 'Y') {
            $this->resizeImage();
        }

        $this->IncludeComponentTemplate();
    }

    private function resizeImage()
    {
        if ($this->arParams['ITEM']['PREVIEW_PICTURE']['ID']) {
            $picture = \Citfact\SiteCore\Pictures\ResizeManager::resizeImageGet(
                $this->arParams['ITEM']['PREVIEW_PICTURE']['ID'],
                $this->arParams['PRODUCT_IMAGE_SIZE'][0],
                $this->arParams['PRODUCT_IMAGE_SIZE'][1]
            );

            $this->arParams['ITEM']['IMG'] = $picture;
        } else {
            $this->arParams['ITEM']['IMG'] = Core::NO_PHOTO_SRC;
        }
    }
}

