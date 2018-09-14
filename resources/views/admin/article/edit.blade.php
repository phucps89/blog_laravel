@extends('admin.default')

@section('page-header')
    Category <small>{{ trans('app.update_item') }}</small>
@stop

@section('content')
	{!! Form::model($item, [
			'action' => ['ArticleController@update', $item->id],
			'method' => 'put',
			'enctype' => 'multipart/form-data',
		])
	!!}

		@include('admin.article.form')

		<button type="submit" class="btn btn-primary">{{ trans('app.edit_button') }}</button>

	{!! Form::close() !!}

@stop
