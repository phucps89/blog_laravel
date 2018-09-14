<div class="row mB-40">
    <div class="col-sm-8">
        <div class="bgc-white p-20 bd">
            {!! Form::myInput('text', 'name', 'Name <span class="c-red-500">*</span>', ['required']) !!}
            {!! Form::myInput('text', 'slug', 'Slug') !!}
            {!! Form::mySelect('id_category', 'Category <span class="c-red-500">*</span>', \App\Repositories\CategoryRepository::getInstance()->getChildCategory(@$item->id)->pluck('name', 'id'), @$item->id_parent) !!}
            <div style="margin-top: -5px; margin-bottom: 1rem"><a href="javascript:resetParent()">Not selected</a></div>
            {!! Form::myFile('image', 'Image') !!}
            @if(@$item->image_url)
                <div class="row">
                    <div class="col-xs-12 col-sm-6 col-md-3">
                        <img src="{{$item->image_url}}" class="img-thumbnail" style="margin-bottom: 1rem" />
                    </div>
                </div>
            @endif
            {!! Form::myTextArea('short_desc', 'Short desc') !!}
            {!! Form::myTextArea('full_desc', 'Full desc', [
                'filebrowserbrowseurl' => route('elfinder.ckeditor')
            ]) !!}
            {!! Form::myInput('text', 'tag', 'Tag') !!}
        </div>
    </div>
</div>

@section('js')
    <script src="{{asset('ckeditor/ckeditor.js')}}"></script>
    <script src="{{asset('ckeditor/sample.js')}}"></script>
    <script>
        initEditor('full_desc');
        {{--CKEDITOR.replace( 'full_desc', {--}}
            {{--filebrowserBrowseUrl : '{{route('elfinder.ckeditor')}}', // eg. 'includes/elFinder/elfinder-cke.html'--}}
        {{--} );--}}
    </script>
    <script type="text/javascript">
        $(document).ready(function(){

            $("#tag").tagit();
        });

    </script>
@endsection
