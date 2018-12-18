@extends('admin.layouts.desk-master')
@section('title', '批次来源选择界面')

@section('content')

    <div class="col-md-4">

    </div>
    <div class="col-md-4">
        <h3>请在下方选择对应的数据来源</h3>
        <div class="info-box bg-yellow" style="cursor: pointer" onclick="RE('{{route('size_order_list')}}')">


            <div class="info-box-content">
                <h1>微信预售数据(线上)</h1>
            </div>
            <!-- /.info-box-content -->
        </div>
        <div class="info-box bg-green" style="cursor: pointer" onclick="RE('{{route('admin.myscan.index.add_fa_huo')}}')" >

            <div class="info-box-content">
                <h1>惠灵顿零售数据(线上)</h1>
            </div>

            <!-- /.info-box-content -->
        </div>
        <div class="info-box bg-red" style="cursor: pointer" onclick="RE('{{route('admin.myscan.index.import_offline')}}')">

            <div class="info-box-content">
                <h1>线下数据导入(线下)</h1>
            </div>

        </div>
    </div>
@endsection