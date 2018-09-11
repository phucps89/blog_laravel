@extends('admin.default')

@section('page-header')
    Quản lý loại gia súc & cây trồng <small>{{ trans('app.add_new_item') }}</small>
@stop

@section('content')
    {!! Form::open([
            'action' => ['FarmBreedCropController@store'],
        ])
    !!}

    @include('admin.farm_breed_crop.form')

    <button type="submit" class="btn btn-primary">{{ trans('app.add_button') }}</button>

    {!! Form::close() !!}

@stop
