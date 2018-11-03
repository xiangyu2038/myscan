@extends('admin.layouts.desk-master')
@section('title', '线下表格列表')

@section('content')
    {{--搜索按钮--}}
    <div class="row">
        <div class="col-xs-12">
            <div class="box">
                <div class="box-header">
                    <h3 class="box-title">线下表格列表</h3>

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
                            <th>导入表格名称</th>
                            <th>备注</th>
                            <th>导入时间</th>

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
                                        <button type="button"  class="btn btn-block btn-success btn-xs deal" onclick="RE('{{route('admin.myscan.index.deal_fa_huo',['id'=>$data['batch_id']])}}')">查看详情</button>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                        </tbody></table>
                </div>
                <!-- /.box-body -->
            </div>
            <!-- /.box -->
        </div>
    </div>
    {{--搜索按钮--}}
    {{$links}}
@endsection

@section('script')
    <script>
        $('.submit').click(function () {
            var key_word = $(" input[ name='key_word' ] ").val();
            var url = '{{URL::current()}}'+'?key_word='+key_word;
            RE(url);
        });
    </script>
@endsection
