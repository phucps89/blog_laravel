<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 9/12/2018
 * Time: 8:41 PM
 */

namespace App\Http\Controllers;


class ElfinderController extends \Barryvdh\Elfinder\ElfinderController
{
    public function showTinyMCE4()
    {
        return $this->app['view']
            ->make('tinymce4')
            ->with($this->getViewVars());
    }

    public function showCKeditor4()
    {
        return $this->app['view']
            ->make('ckeditor4')
            ->with($this->getViewVars());
    }
}