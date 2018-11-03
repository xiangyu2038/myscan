@extends('admin.layouts.desk-master')
@section('title', '处理零售发货')

@section('content')
    <h1>
        导入线下数据
        <small>导入</small>
    </h1>
<div class="row">
    <!-- left column -->
    <div class="col-md-2">
        <!-- general form elements -->
        <div class="box box-primary">
            <div class="box-header with-border">
                <h3 class="box-title">快速导入</h3>
            </div>
            <!-- /.box-header -->
            <!-- form start -->
            <form role="form" action="{{route('admin.myscan.index.import_offline')}}" method="post" enctype="multipart/form-data">
                <div class="box-body">
                    <div class="form-group">
                        <label for="exampleInputEmail1">备注说明</label>
                        <input class="form-control"  placeholder="请输入备注" name="note">
                    </div>
                    <div class="form-group">
                        <label for="exampleInputFile">文件浏览</label>
                        <input id="exampleInputFile" type="file" name="file">

                        <p class="help-block">请导入文件</p>
                    </div>

                </div>
                <!-- /.box-body -->

                <div class="box-footer">
                    <button type="submit" class="btn btn-primary">提交</button>
                </div>
            </form>
        </div>
        <!-- /.box -->


        </div>
        <!-- /.box -->

    <div class="col-xs-9">
        <div class="box">
            <div class="box-header">
                <h3 class="box-title">导入列表</h3>

                <div class="box-tools">
                    <div class="input-group input-group-sm" style="width: 150px;">
                        <input name="key_word" value="{{$search_con['key_word']}}"  class="form-control pull-right" placeholder="Search" type="text">

                        <div class="input-group-btn">
                            <button type="button" class="btn btn-default submit"><i class="fa fa-search"></i></button>
                        </div>
                    </div>
                </div>
            </div>
            <!-- /.box-header -->
            <div class="box-body table-responsive no-padding">
                <table class="table table-hover">
                    <tbody>
                    <tr>
                        <th>序号</th>
                        <th>导入编号</th>
                        <th >导入表格名称</th>
                        <th>备注</th>
                        <th>导入时间</th>
                        <th>操作</th>
                    </tr>
                    @foreach($data as $key=> $v)
                        <tr>
                            <td>{{$key+1}}</td>
                            <td>{{$v['uid']}}</td>
                            <td>{{$v['excel_name']}}</td>
                            <td>{{$v['note']}}</td>
                            <td>{{$v['created_at']}}</td>
                            <td>
                                <div  class="col-md-6">
                                    <button type="button"  class="btn btn-block btn-success btn-xs deal" onclick="RE('{{route('admin.myscan.index.Offline_detail',['uid'=>$v['uid']])}}')">查看详情</button>
                                </div>
                                <div  class="col-md-6">
                                    <button type="button"  class="btn btn-block btn-success btn-xs deal" onclick="RE('{{route('admin.myscan.index.convert_offline_data',['uid'=>$v['uid'],'note'=>$v['excel_name'],'fa_huo_time'=>$v['created_at'],'source'=>'线下导入'])}}')">转化到发货列表</button>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                    </tbody></table>
            </div>
            <!-- /.box-body -->
            {{$links}}
        </div>
        <!-- /.box -->
    </div>
    </div>


@endsection

@section('script')
    <script>
        $(':submit').click(function () {
            $('#myModal').modal('show');
            $("#form").submit();
        })
        $('.submit').click(function () {
            var key_word = $(" input[ name='key_word' ] ").val();
            var url = '{{URL::current()}}'+'?key_word='+key_word;
            RE(url);
        });
    </script>
@endsection