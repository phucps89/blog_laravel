@extends('admin.default')

@section('page-header')
    Quản lý loại gia súc & cây trồng
@endsection

@section('content')

    <div class="mB-20">
        <a href="{{ route(ADMIN . '.farm_breed_crop.create') }}" class="btn btn-info">
            {{ trans('app.add_button') }}
        </a>
    </div>


    <div class="bgc-white bd bdrs-3 p-20 mB-20">
        <table data-column-defs="{{\App\Libraries\Helpers::formatMappingDatatable($mappingKey)}}" data-url="{{route(ADMIN . '.farm_breed_crop.index', ['ajax'=>1])}}" class="ajax-dataTable table table-striped table-bordered" cellspacing="0" width="100%">
            <thead>
            <tr>
                <th>Tên</th>
                <th>Mô tả</th>
                <th>Loại</th>
                <th>Action</th>
            </tr>
            </thead>

            <tfoot>
            <tr>
                <th>Tên</th>
                <th>Mô tả</th>
                <th>Loại</th>
                <th>Action</th>
            </tr>
            </tfoot>

        </table>
    </div>

@endsection
