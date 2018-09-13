<?php

namespace App\Http\Controllers;

use App\Repositories\CategoryRepository;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
//        foreach (CategoryRepository::getInstance()->getRootCategories() as $rootCategory) {
//            echo $rootCategory->name;
//        }
//        exit;
        return view('front.home');
    }
}
