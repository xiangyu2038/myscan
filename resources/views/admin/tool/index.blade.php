@extends('admin.layouts.desk-master')
@section('title', '标题文件啊')



@section('content')
    <form  action="{{route('admin.tool.index.index')}}"  role="form" method="post" enctype="multipart/form-data">
        {{ csrf_field() }}
        <div class="box-body">
            <div class="form-group">
                <label for="exampleInputFile">File input</label>
                <input type="file" name="file" id="exampleInputFile">

                <p class="help-block">Example block-level help text here.</p>
            </div>
        </div>
        <!-- /.box-body -->
        <div class="box-footer">
            <button type="submit" class="btn btn-primary">Submit</button>
        </div>
    </form>
@endsection

@section('script')
    <script>

    </script>
    @endsection




