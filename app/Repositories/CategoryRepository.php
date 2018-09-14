<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 9/11/2018
 * Time: 9:06 PM
 */

namespace App\Repositories;


use App\Models\Category;

class CategoryRepository extends BaseRepository
{

    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model()
    {
        // TODO: Implement model() method.
        return Category::class;
    }

    public function getRootCategories($ignoreId = []){
        $query = $this->makeModel()
            ->newQuery()
            ->whereNull('id_parent');

        if(!is_array($ignoreId)){
            $ignoreId = [$ignoreId];
            $ignoreId = array_filter($ignoreId);
            if($ignoreId != []){
                $query->whereNotIn('id', $ignoreId);
            }
        }

        return $query->get();
    }

    public function getChildCategory(){
        $query = $this->makeModel()
            ->newQuery()
            ->whereNotNull('id_parent');
        return $query->get();
    }
}