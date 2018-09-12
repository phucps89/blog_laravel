@extends('admin.default')

@section('page-header')
    Article <small>{{ trans('app.add_new_item') }}</small>
@stop

@section('content')
    {!! Form::open([
            'action' => ['CategoryController@store'],
            'enctype' => 'multipart/form-data',
        ])
    !!}

    @include('admin.article.form')

    <button type="submit" class="btn btn-primary">{{ trans('app.add_button') }}</button>

    {!! Form::close() !!}

@stop
