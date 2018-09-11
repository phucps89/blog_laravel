<?php

namespace App\Http\Controllers;

use App\Repositories\FarmBreedCropRepository;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        return view('admin.dashboard.index');
    }
}
