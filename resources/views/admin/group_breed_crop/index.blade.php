@extends('admin.default')

@section('page-header')
    Quản lý nhóm gia súc & cây trồng
@endsection

@section('content')

    <div class="mB-20">
        <a href="{{ route(ADMIN . '.group_breed_crop.create') }}" class="btn btn-info">
            {{ trans('app.add_button') }}
        </a>
    </div>


    <div class="bgc-white bd bdrs-3 p-20 mB-20">
        <table data-column-defs="{{\App\Libraries\Helpers::formatMappingDatatable($mappingKey)}}" data-url="{{route(ADMIN . '.group_breed_crop.index', ['ajax'=>1])}}" class="ajax-dataTable table table-striped table-bordered" cellspacing="0" width="100%">
            <thead>
            <tr>
                <th>ID</th>
                <th>Tên</th>
                <th>Mô tả</th>
                <th>Chủng loại</th>
                <th>Số lượng</th>
                <th>Action</th>
            </tr>
            </thead>

            <tfoot>
            <tr>
                <th>ID</th>
                <th>Tên</th>
                <th>Mô tả</th>
                <th>Chủng loại</th>
                <th>Số lượng</th>
                <th>Action</th>
            </tr>
            </tfoot>

        </table>
    </div>

    <div class="modal fade" id="autoGenerateModal" tabindex="-1" role="dialog"
         aria-hidden="true" style="display: none;">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                {!! Form::open([
                            'action' => ['GroupBreedCropController@autoGenerateBreedCrop'],
                            'id' => 'form-generate'
                        ])
                    !!}
                <div class="modal-header"><h5 class="modal-title" id="exampleModalLabel">Tự động sinh gia súc hoặc cây trồng</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                            aria-hidden="true">×</span></button>
                </div>
                <div class="modal-body">

                    {!! Form::myInput('number', 'number', 'Số lượng', ['required']) !!}
                    {!! Form::myInput('hidden', 'group_id', '', [
                            'id' => 'group_id'
                        ]) !!}

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Đóng</button>
                    <button type="submit" class="btn btn-primary">Xác nhận</button>
                </div>
                {!! Form::close() !!}
            </div>
        </div>
    </div>

@endsection

@section('js')
    <script type="text/javascript">
        $(document).ready(function(){
            $(document).on('click', '.group-auto-generate', function(){
                $('#form-generate #group_id').val($(this).data('groupid'));
                $('#autoGenerateModal').modal('show');
            })
        })
    </script>
@endsection
