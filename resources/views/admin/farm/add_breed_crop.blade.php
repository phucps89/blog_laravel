@extends('admin.default')

@section('page-header')
    Quản lý chuồng trại <small>Nhập chuồng</small>
@stop

@section('content')
    {!! Form::open([
            'action' => ['FarmController@postAddBreedCrop', $farm->id],
        ])
    !!}

    <h4>Các gia súc hoặc cây trồng có thể nhập chuồng</h4>

    <div class="bgc-white bd bdrs-3 p-20 mB-20">
        <table id="import-breedcrop-table" data-ajax-complete="fnDrawCallback" data-column-defs="{{\App\Libraries\Helpers::formatMappingDatatable($mappingKey)}}" data-url="{{route(ADMIN . '.farm.add.breed-crop', ['id' => $farm->id, 'ajax'=>1])}}" class="ajax-dataTable table table-striped table-bordered" cellspacing="0" width="100%">
            <thead>
            <tr>
                <th>ID</th>
                <th>Mã</th>
                <th>Xuất xứ</th>
                <th>Mô tả</th>
            </tr>
            </thead>

            <tfoot>
            <tr>
                <th>ID</th>
                <th>Mã</th>
                <th>Xuất xứ</th>
                <th>Mô tả</th>
            </tr>
            </tfoot>

        </table>
    </div>

    <div class="container-fluid" style="margin-bottom: 1rem">
        <div class="row" id="tag-box">

        </div>
    </div>

    <div style="display: none; width: 0; height: 0" id="template-box">
        <div class="col col-md-2 tag">
            <label>asdasdasda</label>
            <i class="fa fa-remove fa-lg float-right" style="margin-top: 5px; cursor: pointer" title="Xóa"></i>
        </div>
    </div>

    {!! Form::myInput('hidden', 'breed_crop_ids') !!}

    <button type="submit" class="btn btn-primary">{{ trans('app.add_button') }}</button>

    {!! Form::close() !!}

@stop

@section('css')
    <style>
        #import-breedcrop-table tbody tr{
            cursor: pointer;
        }
        #import-breedcrop-table tbody tr:hover{
            background-color: #c8cad0;
            color: white;
        }
        #import-breedcrop-table tbody tr.selected{
            border: dashed red 2px;
            color: red;
        }
        .tag{
            border: solid 1px brown;
            padding-top: 6px;
            padding-bottom: 3px;
            margin-left: .5rem;
            margin-right: .5rem;
            margin-bottom: .5rem;
        }
    </style>
@endsection

@section('js')
    <script type="text/javascript">
        Array.prototype.remove = function() {
            var what, a = arguments, L = a.length, ax;
            while (L && this.length) {
                what = a[--L];
                while ((ax = this.indexOf(what)) !== -1) {
                    this.splice(ax, 1);
                }
            }
            return this;
        };

        var inputBrredCropId = $('input[name=breed_crop_ids]');
        inputBrredCropId.val('[]');

        var templateBox = $('#template-box .tag:eq(0)');
        var tagBox = $('#tag-box');

        var table = $('#import-breedcrop-table');
        var rows = [];

        // applyTable();
        function fnDrawCallback(){
            var list = JSON.parse(inputBrredCropId.val());
            rows = [];
            table.find('tbody tr').each(function(){
                var val = $(this).find('td:eq(0)').text();
                rows[val] = $(this);
                if(list.indexOf(val) !== -1){
                    $(this).addClass('selected')
                }
            })
        };

        $(document).on('click', '#import-breedcrop-table tbody tr', function(){
            $(this).toggleClass('selected');
            var val = $(this).find('td:eq(0)').text();
            var list = JSON.parse(inputBrredCropId.val());
            if($(this).hasClass('selected')){
                if(list.indexOf(val) === -1) {
                    list.push(val);
                    var tag = templateBox.clone();
                    tag.attr('id', 'tag-box-'+val);
                    tag.find('label').text($(this).find('td:eq(1)').text());
                    tag.find('i.fa-remove').on('click', function(){
                        tag.remove();
                        var listCurrent = JSON.parse(inputBrredCropId.val());
                        listCurrent.remove(val);
                        if(rows[val] != null){
                            rows[val].removeClass('selected');
                        }
                        inputBrredCropId.val(JSON.stringify(listCurrent));
                    });
                    tagBox.append(tag);
                }
            }
            else{
                list.remove(val);
                tagBox.find('#tag-box-'+val).remove();
            }
            inputBrredCropId.val(JSON.stringify(list));
        })
    </script>
@endsection
