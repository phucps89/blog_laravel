<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 7/21/2018
 * Time: 11:03 AM
 */

namespace App\Http\Controllers;


use App\Repositories\StateRepository;

class CountryStateController extends Controller
{
    public function getStateFromCountry(StateRepository $stateRepository, $idCountry){
        return $stateRepository->makeModel()
            ->newQuery()
            ->where('country_id', $idCountry)
            ->get();
    }
}
