<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 7/21/2018
 * Time: 11:09 AM
 */

namespace App\Http\Controllers;


use App\Libraries\Helpers;
use App\Models\BreedCrop;
use App\Repositories\BreedCropRepository;
use App\Repositories\FarmBreedCropRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class BreedCropController extends Controller
{
    /**
     * @var BreedCropRepository
     */
    private $_breedCropRepository;

    /**
     * @param BreedCropRepository $breedCropRepository
     */
    public function __construct(BreedCropRepository $breedCropRepository)
    {
        $this->_breedCropRepository = $breedCropRepository;
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
            $items = $this->_breedCropRepository->makeModel()->newQuery()
                ->leftJoin('farm_breed_crop', 'farm_breed_crop.id', '=', 'breed_crop.farm_breed_crop_id')
                ->leftJoin('countries', 'countries.id', '=', 'breed_crop.country_id')
                ->leftJoin('states', 'states.id', '=', 'breed_crop.state_id')
                ->select([
                    'breed_crop.*',
                    'countries.name as from_country',
                    'states.name as from_state',
                    'farm_breed_crop.name as breed_crop_name',
                ])
                ->latest('id')->paginate($request->get('length'));
            foreach ($items->items() as $item) {
                $item->desc = nl2br($item->desc);
                $item->gender_name = $item->getGenderName();
                $item->from_location = $item->from_country . ' ' . $item->from_state;
                $item->status_name = trans('app.'.config('variables.breed_crop_status')[$item->status]);
                $item->ticked_signal = view('admin.icon_yes_no', ['result' => $item->ticked == 1])->render();
                $item->action = view('admin.action', [
                    'routeEdit' => route(ADMIN . '.breed_crop.edit', $item->id),
                    'routeDelete' => route(ADMIN . '.breed_crop.destroy', $item->id),
                    'routeQrCode' => route(ADMIN . '.generate.qrcode', $item->code),
                ])->render();
            }
            return Helpers::formatPaginationDataTable($items);
        }


        return view('admin.breed_crop.index', [
            'mappingKey' => [
                'id', 'code', 'from_location', 'desc', 'breed_crop_name', 'gender_name', 'status_name', 'ticked_signal', 'action'
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
        return view('admin.breed_crop.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $autoGenerateCode = $request->get('auto_generate', 0);
        if($autoGenerateCode == 1){
            $request->merge([
                'code' => $this->_breedCropRepository->autoGenerateCode()
            ]);
        }
        else{
            $request->merge([
                'code' => $this->_breedCropRepository->mapPrefixCode($request->get('code'))
            ]);
        }
        $this->validate($request, BreedCrop::rules());

        $user = Helpers::getAuth();

        $this->_breedCropRepository->create(array_merge(
            $request->all(),
            [
                'created_by' => $user->id,
                'updated_by' => $user->id,
                'status' => BreedCrop::STATUS_NEW,
                'ticked' => $request->get('ticked', 0),
            ]
        ));

        return redirect()->route('admin.breed_crop.index')->withSuccess(trans('app.success_store'));
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
        $item = $this->_breedCropRepository->find($id);
        $item->code = $this->_breedCropRepository->removePrefixCode($item->code);
        return view('admin.breed_crop.edit', compact('item'));
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
        $autoGenerateCode = $request->get('auto_generate', 0);
        if($autoGenerateCode == 1){
            $request->merge([
                'code' => $this->_breedCropRepository->autoGenerateCode()
            ]);
        }
        else{
            $request->merge([
                'code' => $this->_breedCropRepository->mapPrefixCode($request->get('code'))
            ]);
        }
        $this->validate($request, BreedCrop::rules(true, $id));

        $item = $this->_breedCropRepository->find($id);

        $user = Helpers::getAuth();
        $item->update(array_merge($request->all(), [
            'updated_by'         => $user->id,
        ]));
        return redirect()->route(ADMIN . '.breed_crop.index')->withSuccess(trans('app.success_update'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $this->_breedCropRepository->delete($id);

        return back()->withSuccess(trans('app.success_destroy'));
    }
}
