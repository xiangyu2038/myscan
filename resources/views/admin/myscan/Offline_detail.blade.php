@extends('admin.layouts.desk-master')
@section('title', '学生列表名单')

@section('content')
    {{--搜索按钮--}}
    <div class="row">
        <div class="col-xs-12">
            <div class="box">
                <div class="box-header">
                    <h3 class="box-title">学生列表名单</h3>

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
                            <th>订单编号</th>
                            <th>姓名</th>
                            <th>收件人</th>
                            <th>性别</th>
                            <th>学校</th>
                            <th>年级</th>
                            <th>班级</th>
                            <th>产品</th>
                        </tr>
                        @foreach($data['data'] as $key=> $v)
                            <tr>
                                <td>{{$key+1}}</td>
                                <td>{{$v->order_sn}}</td>
                                <td>{{$v->name}}</td>
                                <td>{{$v->shou_huo_ren}}</td>
                                <td>{{$v->sex}}</td>
                                <td>{{$v->school}}</td>
                                <td>{{$v->grade}}</td>
                                <td>{{$v->class}}</td>
                                <td>
                                   <table  class="table table-hover">
                                       @foreach($v->offlineCollect as $key=> $vv)
                                       <tr>
                                         <td>{{$vv->fashion_name}} &nbsp;&nbsp; {{$vv->fashion_code}}  &nbsp;&nbsp;{{$vv->fashion_size}}  X {{$vv->fashion_num}} </td>
                                       </tr>
                                       @endforeach
                                   </table>

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
    {{$data['links']}}
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