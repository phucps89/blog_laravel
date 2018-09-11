@extends('admin.default')

@section('page-header')
    Quản lý gia súc & cây trồng
@endsection

@section('content')

    <div class="mB-20">
        <a href="{{ route(ADMIN . '.breed_crop.create') }}" class="btn btn-info">
            {{ trans('app.add_button') }}
        </a>
    </div>


    <div class="bgc-white bd bdrs-3 p-20 mB-20">
        <table data-column-defs="{{\App\Libraries\Helpers::formatMappingDatatable($mappingKey)}}" data-url="{{route(ADMIN . '.breed_crop.index', ['ajax'=>1])}}" class="ajax-dataTable table table-striped table-bordered" cellspacing="0" width="100%">
            <thead>
            <tr>
                <th>ID</th>
                <th>Mã</th>
                <th>Xuất xứ</th>
                <th>Mô tả</th>
                <th>Chủng loại</th>
                <th>Giới tính</th>
                <th>Trạng thái</th>
                <th>Đánh dấu</th>
                <th>Action</th>
            </tr>
            </thead>

            <tfoot>
            <tr>
                <th>ID</th>
                <th>Mã</th>
                <th>Xuất xứ</th>
                <th>Mô tả</th>
                <th>Chủng loại</th>
                <th>Giới tính</th>
                <th>Trạng thái</th>
                <th>Đánh dấu</th>
                <th>Action</th>
            </tr>
            </tfoot>

        </table>
    </div>

@endsection
