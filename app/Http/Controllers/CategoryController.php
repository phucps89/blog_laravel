<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 7/16/2018
 * Time: 8:37 PM
 */

namespace App\Http\Controllers;


use App\Libraries\Helpers;
use App\Models\Category;
use App\Repositories\CategoryRepository;
use App\Services\GoogleDrive\GoogleDriveFacade;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CategoryController extends Controller
{
    /**
     * @var CategoryRepository
     */
    private $_categoryRepository;

    /**
     * @param CategoryRepository $categoryRepository
     */
    public function __construct(CategoryRepository $categoryRepository)
    {
        $this->_categoryRepository = $categoryRepository;
    }


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $request = Helpers::getRequestInstance();

        $rootCategories = $this->_categoryRepository
            ->getRootCategories();

        return view('admin.category.index', [
            'rootCategories' => $rootCategories,
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

        if (!empty($data['image'])) {
            $fileImage = $request->file('image');
            $fileName = uniqid() . '_' . $fileImage->getClientOriginalName();
            $gdObj = GoogleDriveFacade::upload('category', $fileImage, $fileName);
            $data['image_url'] = GoogleDriveFacade::getUrl($gdObj->path, false);
            $data['image_path'] = 'category/' . $fileName;
        }

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

        if (!empty($data['image'])) {
            $fileImage = $request->file('image');
            $fileName = uniqid() . '_' . $fileImage->getClientOriginalName();
            $gdObj = GoogleDriveFacade::upload('category', $fileImage, $fileName);
            $data['image_url'] = GoogleDriveFacade::getUrl($gdObj->path, false);
            $data['image_path'] = 'category/' . $fileName;
        }

        $item = $this->_categoryRepository->find($id);

        if($item->image_path){
            GoogleDriveFacade::delete($item->image_path);
        }

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
