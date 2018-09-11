@extends('admin.default')

@section('page-header')
    Quản lý gia súc & cây trồng <small>{{ trans('app.add_new_item') }}</small>
@stop

@section('content')
    {!! Form::open([
            'action' => ['BreedCropController@store'],
            'id'     => 'form_update'
        ])
    !!}

    @include('admin.breed_crop.form')

    <button type="submit" class="btn btn-primary">{{ trans('app.add_button') }}</button>

    {!! Form::close() !!}

@stop
