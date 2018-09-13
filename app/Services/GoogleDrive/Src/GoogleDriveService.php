<?php

namespace App\Services\GoogleDrive\Src;

use App\Libraries\Helpers;
use App\Services\GoogleDrive\Src\GoogleDriveObject;
use Illuminate\Filesystem\FilesystemAdapter;
use Illuminate\Http\File;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use League\Flysystem\FileNotFoundException;

/**
 * Created by PhpStorm.
 * Account: phuctran
 * Date: 19/01/2017
 * Time: 15:42
 * @property FilesystemAdapter _storage
 */
class GoogleDriveService
{

    public function __construct()
    {
        $this->_storage = Helpers::getStorage();
    }

    /**
     * @param string $path
     * @param File|UploadedFile $srcFile
     * @param string $nameFile
     * @param array $options
     *
     * @return false|GoogleDriveObject
     * @throws \League\Flysystem\FileNotFoundException
     * @throws \Exception
     */
    public function upload(string $path, $srcFile, $nameFile, $options = [])
    {
        $path = trim($path);
        $path = $this->convertToGoogleDrivePath($path, 'dir');
        $result = $this->_storage->putFileAs($path, $srcFile, $nameFile, $options);
        if ($result !== false)
            return $this->getObject($path, $nameFile);
        else return false;
    }

    /**
     * @param string $path
     * @param string $type
     * @return string
     * @throws FileNotFoundException
     */
    public function convertToGoogleDrivePath(string $path, $type = 'file')
    {
        $pathArray = explode('/', $path);
        $pathArray = array_filter($pathArray);
        $drivePath = '';
        $count = count($pathArray);
        foreach ($pathArray as $index => $item) {
            if ($index == $count - 1) {
                $object = $this->getObject($drivePath, $item, $type);
            }
            else {
                $object = $this->getObject($drivePath, $item, 'dir');
            }

            if ($object) {
                $drivePath = $object->path;
            }
            else {
                throw new FileNotFoundException($path);
            }
        }
        return $drivePath;
    }

    protected function makeDir()
    {

    }

    /**
     * @param $googleDrivePath
     * @param string $objectName
     * @param string $type
     * @return GoogleDriveObject
     */
    public function getObject($googleDrivePath, $objectName, $type = 'file')
    {
        $fileName = pathinfo($objectName, PATHINFO_FILENAME);

        $collection = $this->getListContents($googleDrivePath);
        $collection = $collection->where('type', '=', $type)
            ->where('filename', '=', $fileName);
        if ($type == 'file') {
            $ext = pathinfo($objectName, PATHINFO_EXTENSION);
            $collection = $collection->where('extension', '=', $ext);
        }
        return new GoogleDriveObject($collection->first());
    }

    /**
     * @param string $googleDrivePath
     * @return GoogleDriveObject
     * @throws \League\Flysystem\FileNotFoundException
     */
    public function getMetaData($googleDrivePath)
    {
        return new GoogleDriveObject($this->_storage->getMetadata($googleDrivePath));
    }

    public function getListContents($googleDrivePath, $recursive = false)
    {
        return collect(Helpers::getStorage()->listContents($googleDrivePath, $recursive));
    }

    /**
     * @param string $path
     * @param string $pathOnLocal
     * @param bool $convert
     * @param string $type
     * @throws FileNotFoundException
     * @throws \Illuminate\Contracts\Filesystem\FileNotFoundException
     */
    public function download(string $path, string $pathOnLocal, $convert = true, $type = 'file')
    {
        $path = trim($path);
        if ($convert) {
            $path = $this->convertToGoogleDrivePath($path, $type);
        }
        $content = $this->_storage->get($path);
        Storage::put($pathOnLocal, $content);
    }

    /**
     * @param string $path
     *
     * @param bool $convert
     * @param string $type
     * @return bool
     * @throws FileNotFoundException
     */
    public function exists(string $path, $convert = true, $type = 'file')
    {
        $path = trim($path);
        if ($convert) {
            $path = $this->convertToGoogleDrivePath($path, $type);
        }
        return $this->_storage->exists($path);
    }

    /**
     * @param string $path
     *
     * @param bool $convert
     * @param string $type
     * @return string
     * @throws FileNotFoundException
     */
    public function getUrl(string $path, $convert = true, $type = 'file')
    {
        $path = trim($path);
        if ($convert) {
            $path = $this->convertToGoogleDrivePath($path, $type);
        }
        return $this->_storage->url($path);
    }

    /**
     * @param string $path
     * @param bool $convert
     * @param string $type
     * @return bool
     * @throws FileNotFoundException
     */
    public function delete(string $path, $convert = true, $type = 'file')
    {
        $path = trim($path);
        if ($convert) {
            $path = $this->convertToGoogleDrivePath($path, $type);
        }
        if ($type == 'file') {
            return $this->_storage->delete($path);
        }
        else {
            return $this->_storage->deleteDir($path);
        }
    }
}
