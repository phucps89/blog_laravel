<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 7/16/2018
 * Time: 8:37 PM
 */

namespace App\Http\Controllers;


use App\Libraries\Helpers;
use App\Models\Article;
use App\Models\Category;
use App\Repositories\ArticleRepository;
use App\Services\GoogleDrive\GoogleDriveFacade;
use Illuminate\Http\Request;
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
            $items = $this->_articleRepository->makeModel()->newQuery()
                ->leftJoin('categories', 'categories.id', '=', 'articles.id_category')
                ->select([
                    'articles.*',
                    'categories.name as category_name'
                ])
                ->latest('updated_at')->paginate($request->get('length'));
            foreach ($items->items() as $item) {
                $item->action = view('admin.action', [
                    'routeEdit' => route(ADMIN . '.article.edit', $item->id),
                    'routeDelete' => route(ADMIN . '.article.destroy', $item->id),
                ])->render();
            }
            return Helpers::formatPaginationDataTable($items);
        }


        return view('admin.article.index', [
            'mappingKey' => [
                'id', 'name', 'slug', 'action'
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
        return view('admin.article.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     * @throws \Prettus\Validator\Exceptions\ValidatorException
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

        $this->validate($request, Article::rules());

        if (!empty($data['image'])) {
            $fileImage = $request->file('image');
            $fileName = uniqid() . '_' . $fileImage->getClientOriginalName();
            $gdObj = GoogleDriveFacade::upload('article', $fileImage, $fileName);
            $data['image_url'] = GoogleDriveFacade::getUrl($gdObj->path, false);
            $data['image_path'] = 'article/' . $fileName;
        }

        $this->_articleRepository->create(array_merge(
            $data
        ));

        return redirect()->route('admin.article.index')->withSuccess(trans('app.success_store'));
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
        $item = $this->_articleRepository->find($id);

        return view('admin.article.edit', compact('item'));
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

        if (!empty($data['image'])) {
            $fileImage = $request->file('image');
            $fileName = uniqid() . '_' . $fileImage->getClientOriginalName();
            $gdObj = GoogleDriveFacade::upload('category', $fileImage, $fileName);
            $data['image_url'] = GoogleDriveFacade::getUrl($gdObj->path, false);
            $data['image_path'] = 'category/' . $fileName;
        }

        $item = $this->_articleRepository->find($id);

        if($item->image_path && !empty($data['image_url'])){
            GoogleDriveFacade::delete($item->image_path);
        }

        $item->update(array_merge($data, [
        ]));

        return redirect()->route(ADMIN . '.article.index')->withSuccess(trans('app.success_update'));
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
