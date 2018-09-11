<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 7/15/2018
 * Time: 5:13 PM
 */

namespace App\Repositories;


use App\Exceptions\ExistBreedCropInFarmException;
use App\Libraries\Helpers;
use App\Models\BreedCrop;
use App\Models\Farm;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\DB;

class FarmRepository extends BaseRepository
{

    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model()
    {
        // TODO: Implement model() method.
        return Farm::class;
    }

    /**
     * @param $idFarm
     * @param $arrayBreedCropId
     * @return bool
     * @throws ExistBreedCropInFarmException
     * @throws \Exception
     * @throws \Prettus\Repository\Exceptions\RepositoryException
     */
    public function addBreedCrop($idFarm, $arrayBreedCropId){
        $farmBreedCropDetailRepo = FarmBreedCropDetailRepository::getInstance();
        $breedCropRepo = BreedCropRepository::getInstance();

        if(!$this->find($idFarm, ['id'])){
            throw new ModelNotFoundException();
        }

        $isExist = $farmBreedCropDetailRepo->makeModel()
            ->newQuery()
            ->whereIn('breed_crop_id', $arrayBreedCropId)
            ->count() > 0;
        if($isExist){
            throw new ExistBreedCropInFarmException();
        }

        $user = Helpers::getAuth();
        try{
            DB::beginTransaction();
            $dataInsert = [];
            foreach ($arrayBreedCropId as $breedCropId) {
                if(!$breedCropRepo->find($breedCropId, ['id'])) {
                    throw new ModelNotFoundException();
                }
                $dataInsert[] = [
                    'farm_id' => $idFarm,
                    'breed_crop_id' => $breedCropId,
                    'created_by' => $user->id ?? null,
                    'updated_by' => $user->id ?? null,
                ];
            }
            $farmBreedCropDetailRepo->makeModel()
                ->newQuery()
                ->insert($dataInsert);
            $breedCropRepo->makeModel()
                ->newQuery()
                ->whereIn('id', $arrayBreedCropId)
                ->update([
                    'status' => BreedCrop::STATUS_IMPORTED_FARM
                ]);
            DB::commit();
            return true;
        }
        catch (\Exception $e){
            DB::rollBack();
            throw $e;
        }

    }
}
