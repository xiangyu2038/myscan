@extends('admin.layouts.desk-master')
@section('title', '销售发货单列表')

@section('content')
    <div class="row">
        <div class="col-xs-2">
            <button class="btn btn-primary" id="btn-new" onclick="RE('{{route('admin.myscan.index.add_fa_huo')}}')">
                <i class="fa fa-plus"></i> 新建
            </button>

        </div>
    </div>
     {{--搜索按钮--}}
    <div class="row">
        <div class="col-xs-12">
            <div class="box">
                <div class="box-header">
                    <h3 class="box-title">销售发货单列表</h3>

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
                            <th>批次编号</th>
                            <th>备注</th>
                            <th>出库状态</th>
                            <th>批次建立时间</th>
                            <th>操作</th>
                        </tr>
                        @foreach($datas['data'] as $key=> $data)
                        <tr>
                            <td>{{$key+1}}</td>
                            <td>{{$data['batch_sn']}}</td>
                            <td>{{$data['note']}}</td>
                            <td>{{$data['out_status']}}</td>
                            <td>{{$data['created_at']}}</td>
                            <td><button type="button"  class="btn btn-block btn-success btn-xs deal" onclick="RE('{{route('admin.myscan.index.deal_fa_huo',['id'=>$data['batch_id']])}}')">发货处理</button></td>
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
{{$datas['links']}}
@endsection

@section('script')
    <script>
$('.submit').click(function () {
   var key_word = $(" input[ name='key_word' ] ").val();
    var url = '{{URL::current()}}'+'?key_word='+key_word;
    RE(url);
})
    </script>
@endsection
