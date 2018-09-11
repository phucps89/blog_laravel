<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 7/16/2018
 * Time: 8:37 PM
 */

namespace App\Http\Controllers;


use App\Exceptions\ExistBreedCropInFarmException;
use App\Libraries\Helpers;
use App\Models\BreedCrop;
use App\Models\Farm;
use App\Repositories\BreedCropRepository;
use App\Repositories\FarmBreedCropDetailRepository;
use App\Repositories\FarmRepository;
use Illuminate\Database\Query\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Prettus\Repository\Exceptions\RepositoryException;

class FarmController extends Controller
{
    /**
     * @var FarmRepository
     */
    private $_farmRepository;

    /**
     * @param FarmRepository $farmRepository
     */
    public function __construct(FarmRepository $farmRepository)
    {
        $this->_farmRepository = $farmRepository;
    }


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $request = Helpers::getRequestInstance();
        $isAjax = $request->get('ajax', 0);
        if($isAjax == 1){
            $items = $this->_farmRepository->makeModel()->newQuery()
                ->leftJoin('farm_breed_crop', 'farm_breed_crop.id', '=', 'farms.farm_breed_crop_id')
                ->select([
                    'farms.*',
                    'farm_breed_crop.name as breed_crop_name'
                ])
                ->latest('updated_at')->paginate($request->get('length'));
            foreach ($items->items() as $item) {
                $item->type_name = config('variables.farm_type')[$item->type];
                $item->count_breed_crop = $item->farm_breed_crop_detail()->count();
                $item->action = view('admin.action', [
                    'routeEdit' => route(ADMIN . '.farm.edit', $item->id),
                    'routeDelete' => route(ADMIN . '.farm.destroy', $item->id),
                    'includes'    => [
                        [
                            'view'   => 'admin.farm.action_add_breed_crop',
                            'params' => [
                                'routeAddBreedCrop' => route(ADMIN . '.farm.add.breed-crop', ['id' => $item->id])
                            ]
                        ]
                    ]
                ])->render();
            }
            return Helpers::formatPaginationDataTable($items);
        }


        return view('admin.farm.index', [
            'mappingKey' => [
                'name', 'code', 'address', 'desc', 'type_name', 'breed_crop_name', 'count_breed_crop', 'action'
            ]
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.farm.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, Farm::rules());

        $user = Helpers::getAuth();

        $this->_farmRepository->create(array_merge(
            $request->all(),
            [
                'created_by' => $user->id,
                'updated_by' => $user->id,
            ]
        ));

        return redirect()->route('admin.farm.index')->withSuccess(trans('app.success_store'));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $item = $this->_farmRepository->find($id);

        return view('admin.farm.edit', compact('item'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $this->validate($request, Farm::rules(true, $id));

        $item = $this->_farmRepository->find($id);

        $user = Helpers::getAuth();
        $item->update(array_merge($request->all(), [
            'updated_by'         => $user->id,
        ]));

        return redirect()->route(ADMIN . '.farm.index')->withSuccess(trans('app.success_update'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $this->_farmRepository->delete($id);

        return back()->withSuccess(trans('app.success_destroy'));
    }

    public function addBreedCrop(BreedCropRepository $breedCropRepository, $id){
        $farm = $this->_farmRepository->find($id);
        $request = Helpers::getRequestInstance();
        $isAjax = $request->get('ajax', 0);
        if($isAjax == 1){
            $farmBreedCropDetailRepo = FarmBreedCropDetailRepository::getInstance();
//            $idBrCrExist = $farmBreedCropDetailRepo->makeModel()
//                ->newQuery()
//                ->select('breed_crop_id')
//                ->get()
//                ->pluck('breed_crop_id')
//                ->toArray();
            $items = $breedCropRepository->makeModel()
                ->newQuery()
                ->where('farm_breed_crop_id', $farm->farm_breed_crop_id)
                ->where('status', BreedCrop::STATUS_NEW)
                ->whereNotExists(function(Builder $query) {
                    $query->select(DB::raw(1))
                        ->from('farm_breed_crop_detail')
                        ->whereRaw('farm_breed_crop_detail.breed_crop_id = breed_crop.id');
                })
                ->latest('id')
                ->paginate($request->get('length'));
            foreach ($items->items() as $item) {
                $item->desc = nl2br($item->desc);
                $item->from_location = $item->from_country . ' ' . $item->from_state;
                $item->page = $request->get('page');
            }
            return Helpers::formatPaginationDataTable($items);
        }
        $mappingKey = [
            'id', 'code', 'from_location', 'desc'
        ];
        return view('admin.farm.add_breed_crop', compact('farm', 'mappingKey'));
    }

    public function postAddBreedCrop(BreedCropRepository $breedCropRepository, $id){
        $request = Helpers::getRequestInstance();
        $breedCropIds = $request->get('breed_crop_ids');
        $breedCropIds = json_decode($breedCropIds);
        if(empty(($breedCropIds))){
            return redirect()->back()->withErrors([
                'msg' => 'Hãy chọn ít nhất một gia súc hoặc cây trồng'
            ]);
        }
        try {
            $this->_farmRepository->addBreedCrop($id, $breedCropIds);
            return redirect()->route('admin.farm.index')->withSuccess(trans('app.success_store'));
        } catch (\Exception $e) {
            return redirect()->back()->withErrors([
                'msg' => $e->getMessage()
            ]);
        }

    }
}
