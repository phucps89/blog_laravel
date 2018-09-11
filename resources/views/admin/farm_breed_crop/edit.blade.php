@extends('admin.default')

@section('page-header')
    Quản lý loại gia súc & cây trồng <small>{{ trans('app.update_item') }}</small>
@stop

@section('content')
	{!! Form::model($item, [
			'action' => ['FarmBreedCropController@update', $item->id],
			'method' => 'put',
		])
	!!}

		@include('admin.farm_breed_crop.form')

		<button type="submit" class="btn btn-primary">{{ trans('app.edit_button') }}</button>

	{!! Form::close() !!}

@stop
