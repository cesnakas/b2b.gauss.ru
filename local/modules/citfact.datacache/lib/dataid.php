<?php
namespace Citfact\DataCache;

abstract class DataID
{
    /**
     * @var array
     */
    protected $cache;
    protected $cache_id;
    protected $cache_path;
    protected $codeCache = '';
    protected $byCode;
    protected $cache_time = 86400;
    protected static $instance = [];

    /**
     * DataID constructor.
     */
    public function __construct () {
        $this->cache = new \CPHPCache();
        $this->cache_id = $this->codeCache.'Data';
        $this->cache_path = '/'.$this->codeCache.'Data/';
    }

    /**
     * set $this->byCode = array('CODE' => 'ID')
     */
    abstract protected function setData ();

    /**
     * @return $this
     * @throws \ReflectionException
     */
    public static function getInstance() {
        $name = (new \ReflectionClass(static::class))->getShortName();

        if (!isset(self::$instance[ $name ])) {
            self::$instance[ $name ] = new static();
        }

        return self::$instance[ $name ];
    }

    /**
     * return iblock ID by CODE
     * @param $code
     * @return bool
     * @throws \Exception
     */
    public function getByCode($code)
    {
        if (empty($this->byCode)) {
            $this->getData();
        }

        if (isset($this->byCode[$code])) {
            return $this->byCode[$code];
        }

        return false;
    }

    /**
     * @return mixed
     * @throws \Exception
     */
    public function getAllData()
    {
        if (empty($this->byCode)) {
            $this->getData();
        }
        return $this->byCode;
    }

    /**
     * Fill $this->byCode variable by data
     */
    protected function getData()
    {
        if (empty($this->codeCache)) {
            throw new \Exception('empty codeCache');
        }

        $dataByCode = array();
        $useCache = false;

        $dataRes = $this->getCacheData();
        if (is_array($dataRes[$this->cache_id]) && (count($dataRes[$this->cache_id]) > 0)){
            $dataByCode = $dataRes[$this->cache_id];
            $useCache = true;
        }

        if (!$useCache) {
            $dataByCode = $this->setData();
            $this->setTagCache($this->codeCache);
            $this->setCache($dataByCode);
        }

        $this->byCode = $dataByCode;
    }

    /**
     * @param $dataByCode
     */
    protected function setCache ($dataByCode) {
        if ($this->cache_time > 0) {
            $this->cache->StartDataCache($this->cache_time, $this->cache_id, $this->cache_path);
            $this->cache->EndDataCache(array($this->cache_id => $dataByCode));
        }
    }

    /**
     * @return bool
     */
    protected function getCacheData () {
        $dataRes = false;
        if ($this->cache_time > 0 && $this->cache->InitCache($this->cache_time, $this->cache_id, $this->cache_path)) {
            $dataRes = $this->cache->GetVars();
        }
        return $dataRes;
    }

    /**
     * @param string $tagName
     */
    protected function setTagCache($tagName = '') {
        if (empty($tagName))
            return;

        global $CACHE_MANAGER;
        $CACHE_MANAGER->StartTagCache($this->cache_path);
        $CACHE_MANAGER->RegisterTag($tagName);
        $CACHE_MANAGER->EndTagCache();
    }

    /**
     * @param string $tagName
     */
    public function clearCache($tagName=''){
        if (!$tagName) {
            return;
        }

        global $CACHE_MANAGER;
        $CACHE_MANAGER->ClearByTag($tagName);
    }
}
