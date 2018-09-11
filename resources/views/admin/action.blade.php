<ul class="list-inline">
    @if(!empty($routeEdit))
    <li class="list-inline-item">
        <a href="{{ $routeEdit }}" title="{{trans('app.edit_title')}}" class="btn btn-primary btn-sm"><span class="ti-pencil"></span></a></li>
    @endif
    @if(!empty($routeDelete))
    <li class="list-inline-item">
        {!! Form::open([
            'class'=>'delete',
            'url'  => $routeDelete,
            'method' => 'DELETE',
            ])
        !!}

        <button class="btn btn-danger btn-sm" title="{{trans('app.delete_title')}}"><i class="ti-trash"></i></button>

        {!! Form::close() !!}
    </li>
    @endif
    @if(!empty($routeQrCode))
            <li class="list-inline-item">
                <a target="new" href="{{ $routeQrCode }}" title="{{trans('app.generate_qr_code')}}" class="btn btn-info btn-sm"><span class="fa fa-qrcode"></span></a></li>
    @endif
    @if(!empty($includes))
        @foreach($includes as $include)
            @include($include['view'], $include['params'])
        @endforeach
    @endif
</ul>
