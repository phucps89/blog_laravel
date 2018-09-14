<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property int $id_category
 * @property string $name
 * @property string $slug
 * @property string $short_desc
 * @property string $full_desc
 * @property string $image_url
 * @property string $image_path
 * @property mixed $tags
 * @property string $created_at
 * @property string $updated_at
 */
class Article extends Model
{
    /**
     * @var array
     */
    protected $fillable = ['id_category', 'name', 'slug', 'short_desc', 'full_desc', 'image_url', 'image_path', 'tags', 'created_at', 'updated_at'];

    protected $casts = [
        'tags' => 'array'
    ];

    public static function rules($update = false, $id = null)
    {
        $commun = [
            'name' => "required|max:255|unique:articles,name,$id",
            'slug' => "max:255|unique:articles,slug,$id",
            //            'image' => 'file|required',
            'id_category' => 'integer|exists:categories,id',
        ];

        if ($update) {
            return $commun;
        }

        return array_merge($commun, [
        ]);
    }
}
