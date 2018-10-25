@extends('admin.layouts.eui.desk-master')
@section('title', '处理零售发货')

@section('content')

<form class="eui-form" enctype="multipart/form-data"  >
    <input type="hidden" class="eui-ipt" name="address_id" value="{{$address_info['id']}}">

    <div class="eui-form-main">
        <label class="eui-form-label">收件人</label>
        <div class="eui-form-item">
            <input type="text" eui-rule="required" name="name" class="eui-ipt" value="{{$address_info['name']}}" placeholder="请输入收件人">
        </div>
    </div>
    <div class="eui-form-main">
        <label class="eui-form-label">联系电话</label>
        <div class="eui-form-item">
            <input type="text" eui-rule="required" name="tel"  class="eui-ipt" value="{{$address_info['phone']}}" placeholder="请输入联系电话">
        </div>
    </div>
    <div class="eui-form-main">
        <label class="eui-form-label">收货地址</label>
        <div class="eui-form-item">
            <select name="province" class="eui-linkage vipDisplayEdit1" style="width:31.5%" ></select>
            <select name="city" class="eui-linkage vipDisplayEdit2" style="width:32%"></select>
            <select name="area" class="eui-linkage vipDisplayEdit3" style="width:32%"></select>
        </div>
    </div>
    <div class="eui-form-main">
        <label class="eui-form-label">详细地址</label>
        <div class="eui-form-item">
            <input type="text" eui-rule="required" name="detail" class="eui-ipt"  value="{{$address_info['detail']}}" placeholder="请输入详细街道室号">
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
<script src="{{ URL::asset('admin/eui/js/eui-area.js') }}"></script>
<script>
    ////TODO 修改地址信息  student_id 学生id  re_name 收件人 province 省 city 市 area 区  detail  详细地址
    var student_id=$('.eui-layer').attr('id');
    $('[name="student_id"]').val(student_id);
    eui.linkage({
        el:'.vipDisplayEdit1,.vipDisplayEdit2,.vipDisplayEdit3',
        data:eui.area,
        init:["{{$address_info['province']}}","{{$address_info['city']}}","{{$address_info['area']}}"]
    });
    submit({
        url:"{{route('admin.myscan.index.edit_address')}}"
    });
</script>
    @endsection