@extends('admin.layouts.desk-master')
@section('title', '错误')

@section('content')
    <section class="content-header">
        <h1>
            {{$code}}页面错误
        </h1>

    </section>
    <section class="content">
        <div class="error-page">
            <h2 class="headline text-yellow"> {{$code}}</h2>

            <div class="error-content">
                <h3><i class="fa fa-warning text-yellow"></i> {{$message}}</h3>

                <p>
                    这个页面出错啦
                </p>


            </div>
            <!-- /.error-content -->
        </div>
        <!-- /.error-page -->
    </section>
@endsection