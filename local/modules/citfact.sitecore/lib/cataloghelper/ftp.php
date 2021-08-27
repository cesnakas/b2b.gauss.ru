<?php

namespace Citfact\Sitecore\CatalogHelper;

use CFile;
use Citfact\SiteCore\Core;

class Ftp
{

    protected $login;
    protected $password;
    protected $host;
    protected $connectionId;
    protected $defaultDirFiles;

    protected static $instance;

    public $isAuthorized;



    protected function __construct()
    {
        $this->host         = Core::FTP_SERVER_HOST;
        $this->login        = Core::FTP_SERVER_LOGIN;
        $this->password     = Core::FTP_SERVER_PASSWORD;
        $this->connectionId = ftp_connect($this->host);
        $this->isAuthorized = ftp_login($this->connectionId, $this->login, $this->password);
        ///ftp_pasv($this->connectionId, true);
    }

    /**
     * @return Ftp
     */
    public static function getInstance() :self
    {
        if (null !== static::$instance) {
            return static::$instance;
        }

        static::$instance = new static;

        return static::$instance;
    }

    /**
     * @return bool
     */
    public function closeConnection() :bool
    {
        return ftp_close($this->connectionId);
    }

    /**
     * @param $url
     * @return string
     */
    public function getPathFromFtpUrl($url): string
    {
        if (false == stripos($url,Core::FTP_SERVER_HOST)) {
            $url = Core::FTP_SERVER_HOST . $url;
        }
        if (false == stripos($url,'ftp://')) {
            $url = 'ftp://' . $url;
        }

        return parse_url($url)['path'];
    }

    /**
     * @param string $fromRemoteUrl
     * @param string $toLocalDirPath
     * @param array $availableExtensions
     * @param bool $isForIBlock
     * @return bool
     */

    public function downloadFtpFile(string $fromRemoteUrl, string $toLocalDirPath, $availableExtensions = [], $isForIBlock = true)
    {

        $remotePathFile = $this->getPathFromFtpUrl($fromRemoteUrl);
        $remotePathFileExtension = pathinfo($remotePathFile)['extension'];

        if (!empty($filterExtensions)) {
            if (false === in_array($remotePathFileExtension, $availableExtensions)) {
                return false;
            }
        }

        if (true === $isForIBlock) {
            $localFileName = str_replace('/', '_', $remotePathFile);
        } else {
            $localFileName = basename($remotePathFile);
        }

        if (false === file_exists($_SERVER['DOCUMENT_ROOT'] . '/' .$toLocalDirPath)) {
            mkdir($_SERVER['DOCUMENT_ROOT'] . '/' .$toLocalDirPath, 0755, true);
        }

        $absPathFile = $_SERVER['DOCUMENT_ROOT'] . $toLocalDirPath . '/' . $localFileName;

        $isFileDownloaded = ftp_get(
            $this->connectionId,
            $absPathFile,
            $remotePathFile,
            FTP_BINARY);

        return true === $isFileDownloaded ? CFile::MakeFileArray(realpath($absPathFile)) : null;
    }

}