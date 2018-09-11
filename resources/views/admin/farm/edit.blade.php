@extends('admin.default')

@section('page-header')
    Quản lý chuồng trại <small>{{ trans('app.update_item') }}</small>
@stop

@section('content')
	{!! Form::model($item, [
			'action' => ['FarmController@update', $item->id],
			'method' => 'put',
		])
	!!}

		@include('admin.farm.form')

		<button type="submit" class="btn btn-primary">{{ trans('app.edit_button') }}</button>

	{!! Form::close() !!}

@stop
