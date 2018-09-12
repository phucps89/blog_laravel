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
 * @property string $image
 * @property mixed $tags
 * @property string $created_at
 * @property string $updated_at
 */
class Article extends Model
{
    /**
     * @var array
     */
    protected $fillable = ['id_category', 'name', 'slug', 'short_desc', 'full_desc', 'image', 'tags', 'created_at', 'updated_at'];

}
