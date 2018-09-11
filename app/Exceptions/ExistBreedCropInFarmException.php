<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 8/11/2018
 * Time: 8:47 AM
 */

namespace App\Exceptions;


use Throwable;

class ExistBreedCropInFarmException extends \Exception
{

    /**
     * ExistBreedCropInFarmException constructor.
     * @param string|null $message
     * @param Throwable|null $previous
     */
    public function __construct($message = null, Throwable $previous = null)
    {
        $message = $message ?? 'Đã tồn tại gia súc hoặc cây trồng trong chuồng trại. Xin hãy kiểm tra lại!';
        parent::__construct($message, EXIST_BREED_CROP_IN_FARM_EXCEPTION_CODE, $previous);
    }
}
