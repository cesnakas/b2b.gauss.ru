<?php



namespace Citfact\Tools\Module;

interface ModuleInterface
{
    /**
     * Return store path components
     *
     * @param  bool   $absolute
     * @return string
     */
    public function getComponentsPath($absolute = true);

    /**
     * Check install module
     *
     * @return bool
     */
    public function isInstalled();

    /**
     * Return activate date module
     *
     * @return string|null
     */
    public function getInstallDate();

    /**
     * Get name space module.
     *
     * @return string
     */
    public function getNamespace();

    /**
     * Return path, where install module
     *
     * @return string|null
     */
    public function getModulePath();

    /**
     * Return module name
     *
     * @return string
     */
    public function getName();
}
