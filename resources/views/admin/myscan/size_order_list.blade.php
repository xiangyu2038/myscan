@extends('admin.layouts.desk-master')
@section('title', '预售列表')

@section('content')
    <div class="row">
        <div class="col-xs-12">
            <div class="box">
                <div class="box-header">
                    <h3 class="box-title">所有的预售列表</h3>
                    <div class="box-tools">
                        <div class="input-group input-group-sm" style="width: 150px;">
                            <input name="key_word" class="form-control pull-right" value="{{$search_con['key_word']}}" placeholder="搜索预售编号或者预售名称" type="text">

                            <div class="input-group-btn">
                                <button type="submit" class="btn btn-default submit"><i class="fa fa-search"></i></button>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- /.box-header -->
                <div class="box-body table-responsive no-padding">
                    <table class="table table-hover">
                        <tbody><tr>
                            <th>id</th>
                            <th>预售名称</th>
                            <th>预售编码</th>
                            <th>操作</th>
                        </tr>
                       @foreach($size_order as $key=> $v)
                        <tr>
                            <td>{{$key+1}}</td>
                            <td>{{$v->code}}</td>
                            <td>{{$v->name}}</td>
                            <td><button type="button"  class="btn btn-block btn-success btn-xs deal"  onclick="RE('{{route('deal_size_order',['order_id'=>$v->order_id])}}')">处理</button></td>
                        </tr>
                         @endforeach
                        </tbody></table>
                </div>
                <!-- /.box-body -->
            </div>
            <!-- /.box -->
        </div>
        {{$links}}
    </div>
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