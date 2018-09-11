<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property int $group_breed_crop_id
 * @property int $breed_crop_id
 * @property int $created_by
 * @property int $updated_by
 * @property string $created_at
 * @property string $updated_at
 */
class GroupBreedCropDetail extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'group_breed_crop_detail';

    /**
     * @var array
     */
    protected $fillable = ['group_breed_crop_id', 'breed_crop_id', 'created_by', 'updated_by', 'created_at', 'updated_at'];

}
