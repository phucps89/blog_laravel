<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 9/12/2018
 * Time: 1:58 PM
 */

namespace App\Repositories;


use App\Models\Article;

class ArticleRepository extends BaseRepository
{

    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model()
    {
        // TODO: Implement model() method.
        return Article::class;
    }
}
