<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 7/28/2018
 * Time: 8:07 AM
 */

namespace App\Repositories;


use App\Models\GroupBreedCropDetail;

class GroupBreedCropDetailRepository extends BaseRepository
{

    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model()
    {
        // TODO: Implement model() method.
        return GroupBreedCropDetail::class;
    }
}
