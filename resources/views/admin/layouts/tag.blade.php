<div class="row">
    <div class="col-md-12">
        <!-- Custom Tabs -->
        <div class="nav-tabs-custom">
            <ul class="nav nav-tabs">
               @foreach($sub_menus as $sub_menu)
                <li class="s-tab" data-url="{{route($sub_menu['route_name'])}}" ><a href=""  data-toggle="tab" aria-expanded="true">{{$sub_menu['name']}}</a></li>
                @endforeach

                {{-- <li class="pull-right"><a href="#" class="text-muted"><i class="fa fa-gear">你好</i></a></li>--}}
                <li class="pull-right"> <div class="fc-left pull-right">
                        <div class="fc-button-group">
                            <button type="button" class="fc-prev-button fc-button fc-state-default fc-corner-left" id="back" ><span
                                        class="fc-icon fc-icon-left-single-arrow"></span></button>
                            <button type="button" class="fc-next-button fc-button fc-state-default fc-corner-right" id="forward" ><span
                                        class="fc-icon fc-icon-right-single-arrow" ></span></button>
                        </div>

                    </div></li>

            </ul>


            <!-- /.tab-content -->
        </div>
        <!-- nav-tabs-custom -->
    </div>
    <!-- /.col -->
    <!-- /.col -->
</div>

<script>
    $('.s-tab').click(function () {

            $('#myModal').modal('show');
            //alert($(this).attr('data-url'));
            window.location.href=$(this).attr('data-url');
        });

        $('#back').click(function () {

        console.log(history.go(-1));

        });

        $('#forward').click(function () {
            console.log(history.go(1));
        });


</script>
