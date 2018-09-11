<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 8/11/2018
 * Time: 8:27 AM
 */

namespace App\Repositories;


abstract class BaseRepository extends \Prettus\Repository\Eloquent\BaseRepository
{
    /**
     * @return static
     */
    public static function getInstance(){
        return app(static::class);
    }
}
