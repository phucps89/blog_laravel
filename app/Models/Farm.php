<?php

namespace App\Models;

use Illuminate\Support\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property string $name
 * @property string $code
 * @property int $type
 * @property int $farm_breed_crop_id
 * @property string $address
 * @property string $desc
 * @property int $created_by
 * @property int $updated_by
 * @property Carbon $created_at
 * @property Carbon $updated_at
 */
class Farm extends Model
{
    /**
     * @var array
     */
    protected $fillable = ['name', 'code', 'type', 'farm_breed_crop_id', 'address', 'desc', 'created_by', 'updated_by', 'created_at', 'updated_at'];

    public static function rules($update = false, $id = null)
    {
        $commun = [
            'name' => "required|max:255|unique:farms,name,$id",
            'code' => "max:255|unique:farms,code,$id",
            'desc' => 'nullable',
            'address' => 'nullable',
            'type' => 'integer:between:1,2',
            'breed_crop_id' => 'integer',
        ];

        if ($update) {
            return $commun;
        }

        return array_merge($commun, [
        ]);
    }

    public function getFarmTypeName(){
        return config('app.variable.farm_type')[$this->type];
    }

    public function farm_breed_crop(){
        return $this->belongsTo(FarmBreedCrop::class, 'farm_breed_crop_id');
    }

    public function farm_breed_crop_detail(){
        return $this->hasMany(FarmBreedCropDetail::class, 'farm_id');
    }
}
