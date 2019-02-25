@extends('admin.layouts.desk-master')
@section('title', '标题文件啊')

@section('content')
    <form id="form"  action="{{route('stock_import')}}"  role="form" method="post" enctype="multipart/form-data" >
        {{ csrf_field() }}
        <div class="box-body">
            <div class="form-group">
                <label for="exampleInputFile">请上传文件</label>
                <input type="file" name="file" id="exampleInputFile">

            </div>
        </div>
        <!-- /.box-body -->
        <div class="box-footer">
            <button type="submit"  class="btn btn-primary">提交</button>
        </div>
    </form>
@endsection

@section('script')
    <script>
        $(':submit').click(function () {
            $('#myModal').modal('show');
            $("#form").submit();
        })

    </script>
@endsection




