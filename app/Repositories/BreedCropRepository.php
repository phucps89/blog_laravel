<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 7/21/2018
 * Time: 11:09 AM
 */

namespace App\Repositories;


use App\Models\BreedCrop;
use Illuminate\Support\Str;

class BreedCropRepository extends BaseRepository
{

    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model()
    {
        // TODO: Implement model() method.
        return BreedCrop::class;
    }

    public function getPrefixCode(){
        return $prefix = config('variables.prefix_code')['breed_crop'];;
    }

    public function autoGenerateCode(){
        $prefix = $this->getPrefixCode();
        return $prefix . uniqid();
    }

    public function mapPrefixCode($code){
        $prefix = $this->getPrefixCode();
        return $prefix . $code;
    }

    public function removePrefixCode($code){
        $prefix = $this->getPrefixCode();
        return Str::replaceFirst($prefix, '', $code);
    }
}
