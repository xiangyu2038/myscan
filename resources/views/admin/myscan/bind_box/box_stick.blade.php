<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
</head>
<body>
<div class="wrapper">
    <h2>Packing List</h2>
    <div class="head">
        <div class="item">
                <span>
                    <p>订单编号</p>
                    <p>Order Number</p>
                </span>
            <input type="text" value="{{$items['title']['order_xiao_shou_sn']}}">
        </div>
        <div class="item">
                <span>
                    <p>配送编号</p>
                    <p>Delivery Number</p>
                </span>
            <input type="text" value="{{$items['title']['pei_song_sn']}}">
        </div>
        <div class="item">
                <span>
                    <p>客户名称</p>
                    <p>Customer Name</p>
                </span>
            <input type="text" value="{{$items['title']['custom_name']}}">
        </div>
        <div class="item">
                <span>
                    <p>装箱编号</p>
                    <p>Box Numbre</p>
                </span>
            <input type="text" value="{{$items['title']['zhuang_xiang_sn']}}">
        </div>
        <div class="item">
                <span>
                    <p>学部名称</p>
                    <p>Department Name</p>
                </span>
            <input type="text" value="{{$items['title']['xue_bu']}}">
        </div>
        <div class="item">
                <span>
                    <p>装箱日期</p>
                    <p>Packing Date</p>
                </span>
            <input type="text" value="{{$items['title']['zhuang_xiang_time']}}">
        </div>
    </div>
    <div class="main goods">
        <h3>商品明细</h3>
        <table>
            <thead>
            <tr>
                <td>商品编号<p>Product Code</p></td>
                <td>商品名称<p>Description</p></td>
                <td>款式类别<p>Style Names</p></td>
                <td>尺码<p>Size</p></td>
                <td>数量<p>Quantity</p></td>
                <td>备注信息<p>Remarks</p></td>
            </tr>
            </thead>
            <tbody>
            <?php foreach($items['fashion_detail']['fashions'] as  $dv): ; ?>
            <tr>
                <td>{{$dv['fashion_code']}}</td>
                <td>{{$dv['fashion_name']}}</td>
                <td></td>
                <td></td>
                <td>{{$dv['fashion_num']}}</td>
                <td></td>
            </tr>
            <?php endforeach ; ?>
            </tbody>
            <tfoot>
            <tr>
                <td colspan="4">Total小计:</td>
                <td colspan="2">{{$items['fashion_detail']['total']}}</td>
            </tr>
            </tfoot>
        </table>
    </div>
    <div class="main">
        <h3>学生明细</h3>
        <div class="detail">
            <div class="head">
                <span>年级</span>
                <span>班级</span>
                <span>姓名</span>
                <span>性别</span>
                <span>备注</span>
                <span>年级</span>
                <span>班级</span>
                <span>姓名</span>
                <span>性别</span>
                <span>备注</span>
            </div>
            <div class="main">
                <?php foreach($items['student_detail']['student'] as $sv): ; ?>

                <span>{{$sv['grade_name']}}</span>
                <span>{{$sv['class_name']}}</span>
                <span>{{$sv['name']}}</span>
                <span>{{$sv['sex']}}</span>
                <span></span>
                <?php endforeach ; ?>
            </div>
            <div class="foot">
                <span>合计人数:</span>
                <span class="note">{{$items['student_detail']['static']['total']}}</span>
                <span>其中男生</span>
                <span class="note">{{$items['student_detail']['static']['boy_num']}}</span>
                <span>其中女生</span>
                <span class="note">{{$items['student_detail']['static']['girl_num']}}</span>
            </div>
        </div>
    </div>
</div>
</body>
</html>
<style>
    *{
        margin: 0;
        padding: 0;
        font-family: '微软雅黑';
        box-sizing: border-box;
        vertical-align: top;
        max-height: 9999999px;
    }
    .wrapper{
        width: 800px;
        margin: 0 auto;
    }
    h3{
        font-weight: bold;
        line-height: 40px;
    }
    input{
        border: none;
        border-bottom: 1px solid #000;
        height: 40px;
        line-height: 40px;
        font-size: 18px;
        padding: 0 10px;
        width: 65%;
    }
    h2{
        font-weight: bold;
        text-align: center;
        border-bottom: 2px solid #000;
        line-height: 50px;
        font-size: 30px;
        margin-bottom: 20px;
    }
    .head{
        font-size: 0;
    }
    .head .item{
        display: inline-block;
        width: 50%;
        font-size: 0;
        margin-bottom: 20px;
    }
    .head .item span{
        display: inline-block;
        text-align: right;
        width: 35%;
        font-size: 14px;
    }
    .wrapper > .main{
        margin-bottom: 20px;
        padding: 0 50px;
    }
    .main table {
        width: 700px;
        border:solid 1px #000;
        border-collapse: collapse;
    }
    td,th{
        border:solid 1px #000;
        line-height: 30px;
        padding: 0 10px;
    }
    tfoot{
        margin-top: 10px;
    }
    .goods td{
        font-size: 12px;
        line-height: 24px;
    }
    .detail{
        font-size: 0;
        border-top: 1px solid #000;
        border-left: 1px solid #000;
    }
    .detail span{
        display: inline-block;
        padding: 0 5px;
        width: 69.9px;
        font-size: 12px;
        border-right: 1px solid #000;
        border-bottom: 1px solid #000;
        height: 24px;
        line-height: 24px;
    }
    .detail .main,.detail .head{
        margin: 0;
    }
    .detail .foot{
        border-bottom: 1px solid #000;
        border-top: 1px solid #000;
    }
    .detail .foot span{
        width: 116.4px;
    }
    .detail .note{
        overflow: hidden;
        width: 99.4px;
        border-right: 1px solid #000;
    }
    .detail .sex{
        width: 40px;
    }
    .detail .classroom{
        width: 100px;
    }
</style>