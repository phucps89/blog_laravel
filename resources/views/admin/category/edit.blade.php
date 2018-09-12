@extends('admin.default')

@section('page-header')
    Category <small>{{ trans('app.update_item') }}</small>
@stop

@section('content')
	{!! Form::model($item, [
			'action' => ['CategoryController@update', $item->id],
			'method' => 'put',
		])
	!!}

		@include('admin.category.form')

		<button type="submit" class="btn btn-primary">{{ trans('app.edit_button') }}</button>

	{!! Form::close() !!}

@stop
