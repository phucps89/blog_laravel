<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 9/13/2018
 * Time: 8:03 AM
 */

namespace App\Services\GoogleDrive\Src;


use App\Libraries\Helpers;

class GoogleDriveObject
{
    public $path;
    public $dirname;
    public $basename;
    public $filename;
    public $type;
    public $extension;
    public $timestamp;
    public $mimetype;
    public $size;

    /**
     * GoogleDriveObject constructor.
     * @param $attributes
     */
    public function __construct($attributes)
    {
        $this->path = Helpers::get($attributes, 'path');
        $this->dirname = Helpers::get($attributes, 'dirname');
        $this->basename = Helpers::get($attributes, 'basename');
        $this->filename = Helpers::get($attributes, 'filename');
        $this->type = Helpers::get($attributes, 'type');
        $this->extension = Helpers::get($attributes, 'extension');
        $this->timestamp = Helpers::get($attributes, 'timestamp');
        $this->mimetype = Helpers::get($attributes, 'mimetype');
        $this->size = Helpers::get($attributes, 'size');
    }


    public function getFullFileName()
    {
        return $this->filename . '.' . $this->extension;
    }
}