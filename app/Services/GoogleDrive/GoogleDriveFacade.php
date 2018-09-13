<?php

namespace App\Services\GoogleDrive;

use App\Services\GoogleDrive\Src\GoogleDriveObject;
use App\Services\GoogleDrive\Src\GoogleDriveService;
use Illuminate\Filesystem\FilesystemAdapter;
use Illuminate\Http\File;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Facade;

/**
 * Created by PhpStorm.
 * Account: phuctran
 * Date: 19/01/2017
 * Time: 15:20
 */

/**
 * Class S3Facade
 *
 * @method static false|GoogleDriveObject upload(string $path, File|UploadedFile $srcFile, string $nameFile, $options = [])
 * @method static string convertToGoogleDrivePath(string $path, $type = 'file')
 * @method static GoogleDriveObject getMetaData(string $googleDrivePath)
 * @method static void download(string $path, string $pathOnLocal, $convert = true, $type = 'file')
 * @method static bool exists(string $path, $convert = true, $type = 'file')
 * @method static string getUrl(string $path, $convert = true, $type = 'file')
 * @method static bool delete(string $path, $convert = true, $type = 'file')
 *
 * @package App\Services\S3
 */
class GoogleDriveFacade extends Facade
{
    protected static function getFacadeAccessor()
    {
        return GoogleDriveService::class;
    }
}
