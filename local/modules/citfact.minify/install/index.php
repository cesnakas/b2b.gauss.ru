<?php

use Bitrix\Main\Localization\Loc;

Loc::loadMessages(__FILE__);

class citfact_minify extends CModule
{
    /**
     * @var string
     */
    public $MODULE_ID = 'citfact.minify';

    /**
     * @var string
     */
    public $MODULE_VERSION;

    /**
     * @var string
     */
    public $MODULE_VERSION_DATE;

    /**
     * @var string
     */
    public $MODULE_NAME;

    /**
     * @var string
     */
    public $MODULE_DESCRIPTION;

    /**
     * @var string
     */
    public $PARTNER_NAME;

    /**
     * @var string
     */
    public $PARTNER_URI;

    /**
     * Construct object
     */
    public function __construct()
    {
        $this->MODULE_NAME = Loc::getMessage('CITFACT_MINIFY_IMPORT_MODULE_NAME');
        $this->MODULE_DESCRIPTION = Loc::getMessage('CITFACT_MINIFY_IMPORT_MODULE_DESCRIPTION');
        $this->PARTNER_NAME = Loc::getMessage('CITFACT_MINIFY_PARTNER_NAME');
        $this->PARTNER_URI = Loc::getMessage('CITFACT_MINIFY_PARTNER_URI');
        $this->MODULE_PATH = $this->getModulePath();

        $arModuleVersion = array();
        include $this->MODULE_PATH . '/install/version.php';

        $this->MODULE_VERSION = $arModuleVersion['VERSION'];
        $this->MODULE_VERSION_DATE = $arModuleVersion['VERSION_DATE'];
    }

    /**
     * Return path module
     *
     * @return string
     */
    protected function getModulePath()
    {
        $modulePath = explode('/', __FILE__);
        $modulePath = array_slice($modulePath, 0, array_search($this->MODULE_ID, $modulePath) + 1);

        return join('/', $modulePath);
    }

    /**
     * Return components path for install
     *
     * @param bool $absolute
     * @return string
     */
    protected function getComponentsPath($absolute = true)
    {
        $documentRoot = getenv('DOCUMENT_ROOT');
        if (strpos($this->MODULE_PATH, 'local/modules') !== false) {
            $componentsPath = '/local/components';
        } else {
            $componentsPath = '/bitrix/components';
        }

        if ($absolute) {
            $componentsPath = sprintf('%s%s', $documentRoot, $componentsPath);
        }

        return $componentsPath;
    }

    /**
     * Install module
     *
     * @return void
     */
    public function doInstall()
    {
        RegisterModule($this->MODULE_ID);

        $this->installFiles();
        $this->installDB();
        $this->installEvents();
    }

    /**
     * Remove module
     *
     * @return void
     */
    public function doUninstall()
    {
        $this->unInstallDB();
        $this->unInstallFiles();
        $this->unInstallEvents();

        UnRegisterModule($this->MODULE_ID);
    }

    /**
     * Add tables to the database
     *
     * @return bool
     */
    public function installDB()
    {
        return true;
    }

    /**
     * Remove tables from the database
     *
     * @return bool
     */
    public function unInstallDB()
    {
        return true;
    }

    /**
     * Add post events
     *
     * @return bool
     */
    public function installEvents()
    {
        \RegisterModuleDependences('main', "OnAfterEpilog", $this->MODULE_ID, "\Citfact\Minify\MinifyCss", "OnAfterEpilog");
        return true;
    }


    /**
     * Delete post events
     *
     * @return bool
     */
    public function unInstallEvents()
    {
        \UnRegisterModuleDependences("main", "OnAfterEpilog", $this->MODULE_ID, "\Citfact\Minify\MinifyCss", "OnAfterEpilog");
        return true;
    }

    /**
     * Copy files module
     *
     * @return bool
     */
    public function installFiles()
    {
        return true;
    }

    /**
     * Remove files module
     *
     * @return bool
     */
    public function unInstallFiles()
    {
        return true;
    }
}