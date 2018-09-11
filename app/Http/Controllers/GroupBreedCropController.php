<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 7/28/2018
 * Time: 8:08 AM
 */

namespace App\Http\Controllers;


use App\Libraries\Helpers;
use App\Models\BreedCrop;
use App\Models\GroupBreedCrop;
use App\Repositories\BreedCropRepository;
use App\Repositories\GroupBreedCropRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class GroupBreedCropController extends Controller
{
    /**
     * @var GroupBreedCropRepository
     */
    private $_groupBreedCropRepository;

    /**
     * @param GroupBreedCropRepository $groupBreedCropRepository
     */
    public function __construct(GroupBreedCropRepository $groupBreedCropRepository)
    {
        $this->_groupBreedCropRepository = $groupBreedCropRepository;
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
        if ($isAjax == 1) {
            $items = $this->_groupBreedCropRepository->makeModel()->newQuery()
                ->leftJoin('farm_breed_crop', 'farm_breed_crop.id', '=', 'group_breed_crop.farm_breed_crop_id')
                ->select([
                    'group_breed_crop.*',
                    'farm_breed_crop.name as breed_crop_name',
                ])
                ->latest('updated_at')->paginate($request->get('length'));
            foreach ($items->items() as $item) {
                $item->count_breed_crop = $item->countBreedCrop();
                $item->action = view('admin.action', [
                    'routeEdit'   => route(ADMIN . '.group_breed_crop.edit', $item->id),
                    'routeDelete' => route(ADMIN . '.group_breed_crop.destroy', $item->id),
                    'includes'    => [
                        [
                            'view'   => 'admin.group_breed_crop.action_generate',
                            'params' => [
                                'item' => $item
                            ]
                        ]
                    ]
                ])->render();
            }
            return Helpers::formatPaginationDataTable($items);
        }


        return view('admin.group_breed_crop.index', [
            'mappingKey' => [
                'id', 'name', 'desc', 'breed_crop_name', 'count_breed_crop', 'action'
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
        return view('admin.group_breed_crop.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, GroupBreedCrop::rules());

        $user = Helpers::getAuth();

        $this->_groupBreedCropRepository->create(array_merge(
            $request->all(),
            [
                'created_by' => $user->id,
                'updated_by' => $user->id,
            ]
        ));

        return redirect()->route('admin.group_breed_crop.index')->withSuccess(trans('app.success_store'));
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $item = $this->_groupBreedCropRepository->find($id);
        return view('admin.group_breed_crop.edit', compact('item'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $this->validate($request, GroupBreedCrop::rules(true, $id));

        $item = $this->_groupBreedCropRepository->find($id);
        $user = Helpers::getAuth();
        $item->update(array_merge($request->all(), [
            'updated_by'         => $user->id,
        ]));
        return redirect()->route(ADMIN . '.group_breed_crop.index')->withSuccess(trans('app.success_update'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $this->_breedCropRepository->delete($id);

        return back()->withSuccess(trans('app.success_destroy'));
    }

    public function autoGenerateBreedCrop(Request $request, BreedCropRepository $breedCropRepository)
    {
        $groupId = $request->get('group_id');
        $group = $this->_groupBreedCropRepository->find($groupId);
        if (!$group) {
            return redirect()->route(ADMIN . '.group_breed_crop.index')->withErrors([
                'msg' => 'Group not found'
            ]);
        }
        $number = $request->get('number');

        $user = Helpers::getAuth();

        try {
            DB::beginTransaction();

            $start = 0;
            while ($start < $number) {
                $data = [
                    'code'               => $breedCropRepository->autoGenerateCode(),
                    'status'             => BreedCrop::STATUS_NEW,
                    'ticked'             => 0,
                    'farm_breed_crop_id' => $group->farm_breed_crop_id,
                    'created_by'         => $user->id,
                    'updated_by'         => $user->id,
                ];
                $breedCrop = $breedCropRepository->create($data);
                $breedCrop->groupDetails()->create([
                    'group_breed_crop_id' => $groupId,
                    'created_by'          => $user->id,
                    'updated_by'          => $user->id,
                ]);
                $start++;
            }

            DB::commit();
            return redirect()->route(ADMIN . '.group_breed_crop.index')->withSuccess(trans('app.success_update'));
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route(ADMIN . '.group_breed_crop.index')->withErrors([
                'msg' => $e->getMessage()
            ]);
        }
    }
}
