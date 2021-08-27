<?

namespace Citfact\Tools\Component;

use Bitrix\Main\Localization\Loc;
use Bitrix\Main\UI\PageNavigation;

Loc::loadMessages(__FILE__);

class BaseComponent extends \CBitrixComponent
{
    /**
     * @param $pageElementCount
     * @param $id
     * @return PageNavigation $nav
     */
    protected function initPageNavigation($pageElementCount, $id = 'page')
    {
        $nav = new PageNavigation($id);
        $nav->allowAllRecords(true)
            ->setPageSize($pageElementCount)
            ->initFromUri();
        return $nav;
    }

    /**
     * @param PageNavigation $nav
     * @param $totalCount
     * @param string $key
     */
    protected function setPageNavigationTotalCount($nav, $totalCount, $key = 'NAV_OBJECT')
    {
        $nav->setRecordCount($totalCount);
        $this->arResult[$key] = $nav;
    }

    public function arrangeByKey($data, $key)
    {
        if (!$data || !$key) {
            return $data;
        }
        $result = [];
        foreach ($data as $item) {
            if (!$item[$key]) {
                continue;
            }
            $result[$item[$key]] = $item;
        }
        return $result;
    }
}

