<div class="row mB-40">
    <div class="col-sm-8">
        <div class="bgc-white p-20 bd">
            {!! Form::myInput('text', 'name', 'Tên <span class="c-red-500">*</span>', ['required']) !!}
            {!! Form::myInput('text', 'code', 'Mã chuồng') !!}
            {!! Form::myTextArea('address', 'Địa điểm') !!}
            {!! Form::myTextArea('desc', 'Mô tả') !!}
            {!! Form::mySelect('type', 'Loại <span class="c-red-500">*</span>', config('variables.farm_type')) !!}
            {!! Form::mySelect('breed_crop_id', 'Chủng loại <span class="c-red-500">*</span>', [], null, ['required']) !!}
        </div>
    </div>
</div>

@section('js')
    <script type="text/javascript">
        $(document).ready(function(){
            loadBrredCrop($('#type').val());
            $('#type').change(function(){
                loadBrredCrop($(this).val())
            })
        });

        function loadBrredCrop(type) {
            $('#breed_crop_id').find('option')
                .remove()
                .end();
            $.ajax({
                url: "{{ route(ADMIN . '.farm_breed_crop.index') }}?type=" + type,
                dataType: 'JSON'
            }).done(function(list) {
                $.each(list, function(index, item){
                    $('#breed_crop_id').append($('<option/>', {
                        value:item.id,
                        text:item.name
                    }));
                });

            });
        }
    </script>
@endsection
