<?php

return [

    'boolean' => [
        '0' => 'No',
        '1' => 'Yes',
    ],

    'role' => [
        '0' => 'User',
        '10' => 'Admin',
    ],

    'farm_type' => [
        \App\Models\FarmBreedCrop::FARM_TYPE_BREED => 'Gia súc',
        \App\Models\FarmBreedCrop::FARM_TYPE_CROP => 'Cây trồng',
    ],

    'prefix_code' => [
        'breed_crop' => 'BC-',

    ],

    'status' => [
        '1' => 'Active',
        '0' => 'Inactive',
    ],

    'avatar' => [
        'public' => '/storage/avatar/',
        'folder' => 'avatar',

        'width'  => 400,
        'height' => 400,
    ],

    'breed_crop_status' => [
        \App\Models\BreedCrop::STATUS_NEW => 'status_new',
        \App\Models\BreedCrop::STATUS_IMPORTED_FARM => 'breed_crop_status_imported',
    ],
    'breed_crop_gender' => [
        \App\Models\BreedCrop::GENDER_UNDEFINED => 'Không xác định',
        \App\Models\BreedCrop::GENDER_MALE => 'Đực',
        \App\Models\BreedCrop::GENDER_FEMALE => 'Cái',
    ],
    /*
    |------------------------------------------------------------------------------------
    | ENV of APP
    |------------------------------------------------------------------------------------
    */
    'APP_ADMIN' => 'admin',
    'APP_TOKEN' => env('APP_TOKEN', 'admin123456'),
];
