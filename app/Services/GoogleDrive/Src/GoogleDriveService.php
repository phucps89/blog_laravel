<?php

namespace App\Services\S3\Src;

use App\Libraries\Helpers;
use App\Services\GoogleDrive\Src\GoogleDriveObject;
use Illuminate\Filesystem\FilesystemAdapter;
use Illuminate\Http\File;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

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
     */
    public function upload(string $path, $srcFile, $nameFile, $options = []) {
        $path = trim($path);
        $result = $this->_storage->putFileAs($path, $srcFile, $nameFile, $options);
        if($result !== false)
            return new GoogleDriveObject($this->_storage->getMetadata($nameFile));
        else return false;
    }

    protected function convertToGoogleDrivePath(string $path){
        $pathArray = explode('/', $path);
        $pathArray = array_filter($pathArray);
        $fullPath = '';
        foreach ($pathArray as $item) {

        }
    }

    protected function makeDir(){

    }

    protected function getListContents($googleDrivePath, $recursive = false){
        return collect(Storage::cloud()->listContents($googleDrivePath, $recursive));
    }

    public function download(string $path, string $pathOnLocal){
        $path = trim($path);
        $content = $this->_storage->get($path);
        Storage::put($pathOnLocal, $content);
    }

    /**
     * @param string $path
     *
     * @return bool
     */
    public function exists(string $path){
        $path = trim($path);
        return $this->_storage->exists($path);
    }

    /**
     * @param string $path
     *
     * @return string
     */
    public function getUrl(string $path) : string {
        $path = trim($path);
        return $this->_storage->url($path);
    }
}