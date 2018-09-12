@extends('admin.default')

@section('page-header')
    Category <small>{{ trans('app.add_new_item') }}</small>
@stop

@section('content')
    {!! Form::open([
            'action' => ['CategoryController@store'],
            'enctype' => 'multipart/form-data'
        ])
    !!}

    @include('admin.category.form')

    <button type="submit" class="btn btn-primary">{{ trans('app.add_button') }}</button>

    {!! Form::close() !!}

@stop
