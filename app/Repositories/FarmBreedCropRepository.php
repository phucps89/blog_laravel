<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 7/15/2018
 * Time: 5:22 PM
 */

namespace App\Repositories;


use App\Models\FarmBreedCrop;

class FarmBreedCropRepository extends BaseRepository
{

    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model()
    {
        // TODO: Implement model() method.
        return FarmBreedCrop::class;
    }
}
