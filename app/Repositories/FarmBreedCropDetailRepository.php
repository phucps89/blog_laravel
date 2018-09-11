<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 8/11/2018
 * Time: 8:27 AM
 */

namespace App\Repositories;


use App\Models\FarmBreedCropDetail;

class FarmBreedCropDetailRepository extends BaseRepository
{

    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model()
    {
        // TODO: Implement model() method.
        return FarmBreedCropDetail::class;
    }
}
