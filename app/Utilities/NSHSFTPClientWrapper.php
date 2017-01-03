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
        $this->sftpHost = env('SFTP_HOST');
        $this->sftpUsername = env('SFTP_USER');
        $this->sftpPwd = env('SFTP_PWD');

        $this->sftpClient = new SFTP($this->sftpHost);
        $this->isConnected = $this->sftpClient->login($this->sftpUsername, $this->sftpPwd);
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
     * @param string $destinationFilename
     * @param resource $sourceFile
     * @return void
     */
    public function uploadFile($destinationFilename, $sourceFile)
    {
        $this->sftpClient->put($destinationFilename, $sourceFile);
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
        return $this->fileExists($fileOrDirectoryPath);
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
