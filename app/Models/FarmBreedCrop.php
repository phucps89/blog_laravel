<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 7/15/2018
 * Time: 5:18 PM
 */

namespace App\Models;


use Illuminate\Support\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property string $name
 * @property string $desc
 * @property int $type
 * @property Carbon $created_at
 * @property Carbon $updated_at
 */
class FarmBreedCrop extends Model
{
    const FARM_TYPE_BREED = 1;
    const FARM_TYPE_CROP = 2;

    protected $table = 'farm_breed_crop';

    protected $fillable = ['name', 'desc', 'type', 'created_at', 'updated_at'];

    public static function rules($update = false, $id = null)
    {
        $commun = [
            'name' => "required|max:255|unique:farm_breed_crop,name,$id",
            'desc' => 'nullable',
            'type' => 'integer:between:1,2',
        ];

        if ($update) {
            return $commun;
        }

        return array_merge($commun, [
        ]);
    }
}
