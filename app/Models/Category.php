<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property int $id_parent
 * @property string $name
 * @property string $slug
 * @property string $image_url
 * @property string $image_path
 * @property string $icon
 * @property string $created_at
 * @property string $updated_at
 */
class Category extends Model
{
    /**
     * @var array
     */
    protected $fillable = ['id_parent', 'name', 'slug', 'image_url', 'image_path', 'icon', 'created_at', 'updated_at'];

    public static function rules($update = false, $id = null)
    {
        $commun = [
            'name' => "required|max:255|unique:categories,name,$id",
            'slug' => "max:255|unique:categories,slug,$id",
//            'image' => 'file|required',
            'id_parent' => 'integer|exists:categories,id',
        ];

        if ($update) {
            return $commun;
        }

        return array_merge($commun, [
        ]);
    }

    public function childs(){
        return $this->hasMany(Category::class, 'id_parent');
    }

    public function parent(){
        return $this->belongsTo(Category::class, 'id_parent');
    }
}
