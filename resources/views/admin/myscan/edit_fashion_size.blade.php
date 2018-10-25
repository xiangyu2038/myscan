@extends('admin.layouts.eui.desk-master-no-flag')
@section('title', '处理零售发货')

@section('content')
<form class="eui-form" enctype="multipart/form-data"  >
    <input type="hidden" class="eui-ipt" name="batch_id" value="">
    <div class="eui-form-main">
        <label class="eui-form-label">产品编码</label>
        <div class="eui-form-item">
            <input type="text" eui-rule="required" name="fashion_code" class="eui-ipt" placeholder="请输入产品编码">
        </div>
    </div>
    <div class="eui-form-main">
        <label class="eui-form-label">尺码</label>
        <div class="eui-formj-item">
            <input type="text" eui-rule="required" name="size" class="eui-ipt"  placeholder="请输入尺码">
        </div>
    </div>
    <div class="eui-form-main">
        <div class="eui-form-item">
            <button type="button" class="eui-btn z-submit-btn" eui="primary">修改</button>
            <button type="button" class="eui-btn eui-layer-shut">取消</button>
        </div>
    </div>
</form>
@endsection

@section('script')
<script>
    var fashion_d=$('.eui-layer').attr('id').split(',');
    $('[name="batch_id"]').val(fashion_d[0]);
    $('[name="fashion_code"]').val(fashion_d[1]);
    $('[name="size"]').val(fashion_d[2]);
    submit({
        url:"{{route('admin.myscan.index.edit_fashion_size')}}",
        popover:{
            type:'confirm',
            style:'danger',
            info:'确定要修改？'
        }
    });
</script>
    @endsection