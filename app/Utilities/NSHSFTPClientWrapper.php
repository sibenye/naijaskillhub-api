<?php
/**
 * @package App\Utilities
 */
namespace App\Utilities;

use phpseclib\Net\SFTP;

/**
 * SFTP Client Wrapper
 *
 * @author silver.ibenye
 *
 */
class NSHSFTPClientWrapper
{
    private $sftpClient;
    private $sftpUsername;
    private $sftpPwd;
    private $sftpHost;
    public $isConnected;

    public function __construct()
    {
        if (env('SFTP_ENABLED')) {
            $this->sftpHost = env('SFTP_HOST');
            $this->sftpUsername = env('SFTP_USER');
            $this->sftpPwd = env('SFTP_PWD');
            $this->sftpClient = new SFTP($this->sftpHost);
            $this->isConnected = $this->sftpClient->login($this->sftpUsername, $this->sftpPwd);
        }
    }

    /**
     * Switch to this directory.
     *
     * @param string $directoryName
     * @return void
     */
    public function changeDirectory($directoryName)
    {
        $this->sftpClient->chdir($directoryName);
    }

    /**
     * Upload file to FTP.
     *
     * @param string $fileName
     * @param resource $fileToUpload
     * @return void
     */
    public function uploadFile($fileName, $fileToUpload)
    {
        $this->sftpClient->put($fileName, $fileToUpload, SFTP::SOURCE_LOCAL_FILE);
    }

    /**
     * Create a directory.
     *
     * @param string $directoryName
     * @return void
     */
    public function makeDirectory($directoryName)
    {
        $this->sftpClient->mkdir($directoryName, -1, true);
    }

    /**
     * Check if a file or directory exists.
     *
     * @param string $fileOrDirectoryPath
     * @return boolean
     */
    public function fileExists($fileOrDirectoryPath)
    {
        return $this->sftpClient->file_exists($fileOrDirectoryPath);
    }

    /**
     * Delete a file.
     *
     * @param string $filePath
     * @return void
     */
    public function deleteFile($filePath)
    {
        $this->sftpClient->delete($filePath);
    }
}
