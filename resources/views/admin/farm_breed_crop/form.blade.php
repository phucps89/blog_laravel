<div class="row mB-40">
    <div class="col-sm-8">
        <div class="bgc-white p-20 bd">
            {!! Form::myInput('text', 'name', 'Tên', ['required']) !!}

            {!! Form::myTextArea('desc', 'Mô tả') !!}

            {!! Form::mySelect('type', 'Loại', config('variables.farm_type')) !!}
        </div>
    </div>
</div>
