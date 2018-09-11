@extends('admin.default')

@section('page-header')
    Quản lý chuồng trại <small>{{ trans('app.add_new_item') }}</small>
@stop

@section('content')
    {!! Form::open([
            'action' => ['FarmController@store'],
        ])
    !!}

    @include('admin.farm.form')

    <button type="submit" class="btn btn-primary">{{ trans('app.add_button') }}</button>

    {!! Form::close() !!}

@stop
