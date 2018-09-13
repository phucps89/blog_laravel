<?php

namespace App\Services\S3;

use App\Services\GoogleDrive\Src\GoogleDriveObject;
use App\Services\S3\Src\GoogleDriveService;
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
 * @method static false|GoogleDriveObject upload(string $path, File|UploadedFile $srcFile, $options = [])
 * @method static void download(string $path, string $pathOnLocal)
 * @method static bool exists(string $path)
 * @method static string getUrl(string $path)
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