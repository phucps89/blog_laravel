<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 7/16/2018
 * Time: 8:37 PM
 */

namespace App\Http\Controllers;


use App\Libraries\Helpers;
use App\Models\BreedCrop;
use App\Models\Category;
use App\Models\Farm;
use App\Repositories\ArticleRepository;
use App\Repositories\BreedCropRepository;
use App\Repositories\CategoryRepository;
use App\Repositories\FarmBreedCropDetailRepository;
use App\Repositories\FarmRepository;
use Illuminate\Database\Query\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class ArticleController extends Controller
{
    /**
     * @var ArticleRepository
     */
    private $_articleRepository;

    /**
     * @param ArticleRepository $articleRepository
     */
    public function __construct(ArticleRepository $articleRepository)
    {
        $this->_articleRepository = $articleRepository;
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
        return view('admin.category.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $data = $request->all();

        if (empty($data['slug'])) {
            $data['slug'] = Str::slug($data['name']);
        }

        $request->merge([
            'slug' => $data['slug']
        ]);

        $this->validate($request, Category::rules());

        $fileImage = $request->file('image');
        $fileName = uniqid() . '_' . $fileImage->getClientOriginalName();
        Helpers::getStorage()->putFileAs(null, $fileImage, $fileName);
        $data['image'] = Helpers::getStorage()->url($fileName);

        $this->_categoryRepository->create(array_merge(
            $data
        ));

        return redirect()->route('admin.category.index')->withSuccess(trans('app.success_store'));
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
        $item = $this->_categoryRepository->find($id);

        return view('admin.category.edit', compact('item'));
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
        $data = $request->all();

        if (empty($data['slug'])) {
            $data['slug'] = Str::slug($data['name']);
        }

        if (empty($data['id_parent'])) {
            $data['id_parent'] = null;
        }

        $request->merge([
            'slug' => $data['slug']
        ]);

        $this->validate($request, Category::rules(true, $id));

        $fileImage = $request->file('image');
        $fileName = uniqid() . '_' . $fileImage->getClientOriginalName();
        Helpers::getStorage()->putFileAs(null, $fileImage, $fileName);
        $data['image'] = Helpers::getStorage()->url($fileName);

        $item = $this->_categoryRepository->find($id);

        $item->update(array_merge($data, [
        ]));

        return redirect()->route(ADMIN . '.category.index')->withSuccess(trans('app.success_update'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $this->_categoryRepository->delete($id);

        return back()->withSuccess(trans('app.success_destroy'));
    }
}
