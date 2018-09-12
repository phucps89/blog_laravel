<div class="row mB-40">
    <div class="col-sm-8">
        <div class="bgc-white p-20 bd">
            {!! Form::myInput('text', 'name', 'Name <span class="c-red-500">*</span>', ['required']) !!}
            {!! Form::myInput('text', 'slug', 'Slug') !!}
            {!! Form::mySelect('id_parent', 'Parent <span class="c-red-500">*</span>', \App\Repositories\CategoryRepository::getInstance()->getRootCategories(@$item->id)->pluck('name', 'id'), @$item->id_parent) !!}
            <div style="margin-top: -5px; margin-bottom: 1rem"><a href="javascript:resetParent()">Not selected</a></div>
            {!! Form::myFile('image', 'Image') !!}
            {!! Form::myInput('text', 'icon', 'Icon') !!}
        </div>
    </div>
</div>

@section('js')
    <script type="text/javascript">
        $(document).ready(function(){
            @if(empty(@$item->id_parent))
                resetParent();
            @endif
        });

        function resetParent(){
            $('#id_parent').val(null);
        }
    </script>
@endsection
