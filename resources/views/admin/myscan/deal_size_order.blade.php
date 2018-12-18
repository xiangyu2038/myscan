@extends('admin.layouts.desk-master')
@section('title', '预售列表')

@section('content')
    <div class="row">
        <div class="col-xs-2">
            <button type="button" class="btn btn-info" data-toggle="modal" data-target="#modal-default">
                <i class="fa fa-plus"></i>为此预售新增一个批次
            </button>


        </div>
        {{--<div class="col-xs-2">--}}

        {{--<button class="btn btn-primary" id="btn-new" onclick="RE('{{route('admin.myscan.index.import_offline')}}')">--}}
        {{--<i class="fa fa-plus"></i> 新建(线下数据导入)--}}
        {{--</button>--}}

        {{--</div>--}}
    </div>
    <div class="row">
        <div class="col-md-6">
            <div class="box">
                <div class="box-header with-border">
                    <h3 class="box-title">预售(({{$size_order->name}}))的所有的发货批次</h3>
                </div>
                <!-- /.box-header -->
                <div class="box-body">
                    <table class="table table-bordered">
                        <tbody><tr>
                            <th style="width: 10px">#</th>
                            <th>批次编号</th>
                            <th>本批次要发货的产品</th>
                            <th>批次说明</th>
                            <th style="width: 40px">批次创建时间</th>
                            <th>操作</th>
                        </tr>
                      @foreach($size_order->batch as $key=> $v)
                        <tr>
                            <td>{{$key+1}}</td>
                            <td>{{$v->batch_sn}}</td>
                            <td>
                                @foreach($v->batchDetails as $vv)
                                    <p>{{$vv->fashion_code}}</p>
                                    @endforeach

                            </td>
                            <td>
                                {{$v->note}}
                            </td>
                            <td>{{$v->created_at}}</td>
                            <td> <button type="button"  class="btn btn-block btn-success btn-xs deal" onclick="RE('{{route('admin.myscan.index.deal_fa_huo',['id'=>$v['id'],'source'=>$v['source']])}}')">发货处理</button></td>
                        </tr>
                      @endforeach
                        </tbody></table>
                </div>

            </div>
        </div>
    </div>
    <div class="modal fade in" id="modal-default" style="display: none; padding-right: 17px;">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span></button>
                    <h4 class="modal-title">请选择商品编码</h4>
                </div>
                <div class="modal-body">
                    <form id="myform"   method="post">
                        <table class="table table-bordered table-hover">
                        <thead>
                        <tr class="success">
                            <th><input type="checkbox" id="checkAll" name="checkAll" />全选</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($size_order_all_fashion as $v)
                        <tr>
                            <td><input type="checkbox" name="checkItem" value="{{$v['code']}}" /></td>
                            <td>{{$v['show_name']}}------{{$v['code']}}----{{$v['old_code']}}</td>

                        </tr>
                       @endforeach
                        </tbody>
                    </table>
                        <div class="form-group">
                            <label>备注</label>
                            <textarea class="form-control" rows="3" id="note" name="note" placeholder="请输入备注"></textarea>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default pull-left" data-dismiss="modal">关闭</button>
                    <button type="button" class="btn btn-primary submits">保存</button>
                </div>
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>
@endsection

@section('script')
    <script>
        $(function(){
            function initTableCheckbox() {
                var $thr = $('table thead tr');
                //var $checkAllTh = $('<th><input type="checkbox" id="checkAll" name="checkAll" /></th>');
                /*将全选/反选复选框添加到表头最前，即增加一列*/
                //$thr.prepend($checkAllTh);

                /*“全选/反选”复选框*/
                var $checkAll = $thr.find('input');
                $checkAll.click(function(event){
                    /*将所有行的选中状态设成全选框的选中状态*/
                    $tbr.find('input').prop('checked',$(this).prop('checked'));
                    /*并调整所有选中行的CSS样式*/
                    if ($(this).prop('checked')) {
                        $tbr.find('input').parent().parent().addClass('warning');
                    } else{
                        $tbr.find('input').parent().parent().removeClass('warning');
                    }
                    /*阻止向上冒泡，以防再次触发点击操作*/
                    event.stopPropagation();
                });
                /*点击全选框所在单元格时也触发全选框的点击操作*/
                $('#checkAll').click(function(){
                    $(this).find('input').click();
                });
                var $tbr = $('table tbody tr');
                //var $checkItemTd = $('<td><input type="checkbox" name="checkItem" /></td>');
                /*每一行都在最前面插入一个选中复选框的单元格*/
                //$tbr.prepend($checkItemTd);
                /*点击每一行的选中复选框时*/
                $tbr.find('input').click(function(event){
                    /*调整选中行的CSS样式*/
                    $(this).parent().parent().toggleClass('warning');
                    /*如果已经被选中行的行数等于表格的数据行数，将全选框设为选中状态，否则设为未选中状态*/
                    $checkAll.prop('checked',$tbr.find('input:checked').length == $tbr.length ? true : false);
                    /*阻止向上冒泡，以防再次触发点击操作*/
                    event.stopPropagation();
                });
                /*点击每一行时也触发该行的选中操作*/
                $tbr.click(function(){
                    $(this).find('input').click();
                });
            }
            initTableCheckbox();
        });

        $('.submits').click(function () {

            var data={

                note:$('#note').val(),
                order_id:'{{$size_order['order_id']}}',
                fashion_code:[]
            };



            $('input[name="checkItem"]:checked').each(function(){
                data.fashion_code.push($(this).val());
            });

            $.ajax({
                url:'{{route('add_size_order_batch')}}',
                type: "post",
                data:data,
                success:function(res){
                  if(res.code==0){
                      RE('{{route('admin.myscan.index.index')}}');
                  }

                },
                error:function(){
                    alert('系统异常！');
                }
            })

            });
    </script>

@endsection