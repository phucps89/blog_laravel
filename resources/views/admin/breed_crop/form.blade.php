<div class="row mB-40">
    <div class="col-sm-8">
        <div class="bgc-white p-20 bd">
            {!! Form::myInput('text', 'code', 'Mã <span class="c-red-500">*</span> ('.config('variables.prefix_code')['breed_crop'].')', []) !!}
            {!! Form::myCheckbox('auto_generate', 'Tự động sinh mã', 1, false) !!}
            {!! Form::mySelect('farm_breed_crop_id', 'Chủng loại', \App\Models\FarmBreedCrop::all()->pluck('name', 'id')) !!}
            {!! Form::mySelect('gender', 'Giới tính', config('variables.breed_crop_gender')) !!}
            {!! Form::myTextArea('desc', 'Mô tả') !!}
            {!! Form::myInput('text', 'birthday', 'Ngày sinh', []) !!}
            {!! Form::mySelect('country_id', 'Nhập từ quốc gia', \App\Models\Country::all()->pluck('name', 'id')) !!}
            {!! Form::mySelect('state_id', 'Nhập từ bang / tỉnh thành') !!}
            {!! Form::myCheckbox('ticked', 'Được đánh dấu', 1, false) !!}
        </div>
    </div>
</div>

@section('js')
    <script type="text/javascript">
        $(document).ready(function(){
            $("#birthday").datetimepicker();

            $('#form_update').validate({
                rules: {
                    code:{
                        required: function (element) {
                            if(isAutoGenerateChecked()){
                                return true;
                            }
                            else
                            {
                                return $('#code').val() == '';
                            }
                        }
                    }
                }
            });

            $('#auto_generate').change(function(){
                $('#code').prop('disabled', isAutoGenerateChecked());
                $('#form_update').valid();
            })
            $('#code').prop('disabled', isAutoGenerateChecked());

            $('#country_id').change(function(){
                loadState($(this).val())
            });
            loadState($('#country_id').val())
        });

        function isAutoGenerateChecked(){
            return $("input[id=auto_generate]:checked").length == 1;
        }

        var stateSelected = {{$item->state_id ?? -1}};
        function loadState(idCountry) {
            $('#state_id').find('option')
                .remove()
                .end();
            $.ajax({
                url: "{{ route('state.from.country', ['id' => '']) }}/" + idCountry,
                dataType: 'JSON'
            }).done(function(list) {
                $.each(list, function(index, item){
                    $('#state_id').append($('<option/>', {
                        value:item.id,
                        text:item.name,
                        selected: stateSelected == item.id
                    }));
                });
                //$('#state_id').val(stateSelected);
            });
        }
    </script>
@endsection
