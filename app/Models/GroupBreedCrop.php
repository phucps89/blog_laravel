<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property string $name
 * @property string $desc
 * @property int $farm_breed_crop_id
 * @property int $created_by
 * @property int $updated_by
 * @property string $created_at
 * @property string $updated_at
 */
class GroupBreedCrop extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'group_breed_crop';

    /**
     * @var array
     */
    protected $fillable = ['name', 'desc', 'farm_breed_crop_id', 'created_by', 'updated_by', 'created_at', 'updated_at'];

    public static function rules($update = false, $id = null)
    {
        $commun = [
            'name'               => "required|max:255|unique:group_breed_crop,name,$id",
            'desc'               => 'nullable',
        ];

        if ($update) {
            return $commun;
        }

        return array_merge($commun, [
        ]);
    }

    public function breedCropDetails(){
        return $this->hasMany(GroupBreedCropDetail::class, 'group_breed_crop_id');
    }

    public function countBreedCrop(){
        return $this->breedCropDetails()->count();
    }
}
