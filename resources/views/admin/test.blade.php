@extends('admin.layouts.master')
@section('title', '测试标题')

@section('content')
    <div class="row">

        <div class="col-md-12">
            <!-- Custom Tabs -->
            <div class="nav-tabs-custom">
                <ul class="nav nav-tabs">
                    <li class="s-tab active" ><a href="#tab_1" data-toggle="tab" aria-expanded="true">daf的烦烦烦</a></li>

                    <li class="pull-right"> <div class="fc-left pull-right">
                            <div class="fc-button-group">
                                <button type="button" class="fc-prev-button fc-button fc-state-default fc-corner-left" onclick="back();"><span
                                            class="fc-icon fc-icon-left-single-arrow"></span></button>
                                <button type="button" class="fc-next-button fc-button fc-state-default fc-corner-right" onclick="forward();"><span
                                            class="fc-icon fc-icon-right-single-arrow" ></span></button>
                            </div>

                        </div></li>

                </ul>
                <div class="tab-content">
                    <div class=" tab-pane active" id="tab_1">
                        <section class="content-header">
                            <h1>
                                General Form Elements
                                <small>Preview</small>
                            </h1>

                        </section>
                        <section class="content">
                            <div class="row">
                                <!-- left column -->
                                <div class="col-md-6">
                                    <!-- general form elements -->
                                    <div class="box box-primary">
                                        <div class="box-header with-border">
                                            <h3 class="box-title">Quick Example</h3>
                                        </div>
                                        <!-- /.box-header -->
                                        <!-- form start -->
                                        <form role="form">
                                            <div class="box-body">
                                                <div class="form-group">
                                                    <label for="exampleInputEmail1">Email address</label>
                                                    <input type="email" class="form-control" id="exampleInputEmail1" placeholder="Enter email">
                                                </div>
                                                <div class="form-group">
                                                    <label for="exampleInputPassword1">Password</label>
                                                    <input type="password" class="form-control" id="exampleInputPassword1" placeholder="Password">
                                                </div>
                                                <div class="form-group">
                                                    <label for="exampleInputFile">File input</label>
                                                    <input type="file" id="exampleInputFile">

                                                    <p class="help-block">Example block-level help text here.</p>
                                                </div>
                                                <div class="checkbox">
                                                    <label>
                                                        <input type="checkbox"> Check me out
                                                    </label>
                                                </div>
                                            </div>
                                            <!-- /.box-body -->

                                            <div class="box-footer">
                                                <button type="submit" class="btn btn-primary">Submit</button>
                                            </div>
                                        </form>
                                    </div>
                                    <!-- /.box -->

                                    <!-- Form Element sizes -->
                                    <div class="box box-success">
                                        <div class="box-header with-border">
                                            <h3 class="box-title">Different Height</h3>
                                        </div>
                                        <div class="box-body">
                                            <input class="form-control input-lg" type="text" placeholder=".input-lg">
                                            <br>
                                            <input class="form-control" type="text" placeholder="Default input">
                                            <br>
                                            <input class="form-control input-sm" type="text" placeholder=".input-sm">
                                        </div>
                                        <!-- /.box-body -->
                                    </div>
                                    <!-- /.box -->

                                    <div class="box box-danger">
                                        <div class="box-header with-border">
                                            <h3 class="box-title">Different Width</h3>
                                        </div>
                                        <div class="box-body">
                                            <div class="row">
                                                <div class="col-xs-3">
                                                    <input type="text" class="form-control" placeholder=".col-xs-3">
                                                </div>
                                                <div class="col-xs-4">
                                                    <input type="text" class="form-control" placeholder=".col-xs-4">
                                                </div>
                                                <div class="col-xs-5">
                                                    <input type="text" class="form-control" placeholder=".col-xs-5">
                                                </div>
                                            </div>
                                        </div>
                                        <!-- /.box-body -->
                                    </div>
                                    <!-- /.box -->

                                    <!-- Input addon -->
                                    <div class="box box-info">
                                        <div class="box-header with-border">
                                            <h3 class="box-title">Input Addon</h3>
                                        </div>
                                        <div class="box-body">
                                            <div class="input-group">
                                                <span class="input-group-addon">@</span>
                                                <input type="text" class="form-control" placeholder="Username">
                                            </div>
                                            <br>

                                            <div class="input-group">
                                                <input type="text" class="form-control">
                                                <span class="input-group-addon">.00</span>
                                            </div>
                                            <br>

                                            <div class="input-group">
                                                <span class="input-group-addon">$</span>
                                                <input type="text" class="form-control">
                                                <span class="input-group-addon">.00</span>
                                            </div>

                                            <h4>With icons</h4>

                                            <div class="input-group">
                                                <span class="input-group-addon"><i class="fa fa-envelope"></i></span>
                                                <input type="email" class="form-control" placeholder="Email">
                                            </div>
                                            <br>

                                            <div class="input-group">
                                                <input type="text" class="form-control">
                                                <span class="input-group-addon"><i class="fa fa-check"></i></span>
                                            </div>
                                            <br>

                                            <div class="input-group">
                                                <span class="input-group-addon"><i class="fa fa-dollar"></i></span>
                                                <input type="text" class="form-control">
                                                <span class="input-group-addon"><i class="fa fa-ambulance"></i></span>
                                            </div>

                                            <h4>With checkbox and radio inputs</h4>

                                            <div class="row">
                                                <div class="col-lg-6">
                                                    <div class="input-group">
                        <span class="input-group-addon">
                          <input type="checkbox">
                        </span>
                                                        <input type="text" class="form-control">
                                                    </div>
                                                    <!-- /input-group -->
                                                </div>
                                                <!-- /.col-lg-6 -->
                                                <div class="col-lg-6">
                                                    <div class="input-group">
                        <span class="input-group-addon">
                          <input type="radio">
                        </span>
                                                        <input type="text" class="form-control">
                                                    </div>
                                                    <!-- /input-group -->
                                                </div>
                                                <!-- /.col-lg-6 -->
                                            </div>
                                            <!-- /.row -->

                                            <h4>With buttons</h4>

                                            <p class="margin">Large: <code>.input-group.input-group-lg</code></p>

                                            <div class="input-group input-group-lg">
                                                <div class="input-group-btn">
                                                    <button type="button" class="btn btn-warning dropdown-toggle" data-toggle="dropdown">Action
                                                        <span class="fa fa-caret-down"></span></button>
                                                    <ul class="dropdown-menu">
                                                        <li><a href="#">Action</a></li>
                                                        <li><a href="#">Another action</a></li>
                                                        <li><a href="#">Something else here</a></li>
                                                        <li class="divider"></li>
                                                        <li><a href="#">Separated link</a></li>
                                                    </ul>
                                                </div>
                                                <!-- /btn-group -->
                                                <input type="text" class="form-control">
                                            </div>
                                            <!-- /input-group -->
                                            <p class="margin">Normal</p>

                                            <div class="input-group">
                                                <div class="input-group-btn">
                                                    <button type="button" class="btn btn-danger">Action</button>
                                                </div>
                                                <!-- /btn-group -->
                                                <input type="text" class="form-control">
                                            </div>
                                            <!-- /input-group -->
                                            <p class="margin">Small <code>.input-group.input-group-sm</code></p>

                                            <div class="input-group input-group-sm">
                                                <input type="text" class="form-control">
                                                <span class="input-group-btn">
                      <button type="button" class="btn btn-info btn-flat">Go!</button>
                    </span>
                                            </div>
                                            <!-- /input-group -->
                                        </div>
                                        <!-- /.box-body -->
                                    </div>
                                    <!-- /.box -->

                                </div>
                                <!--/.col (left) -->
                                <!-- right column -->
                                <div class="col-md-6">
                                    <!-- Horizontal Form -->
                                    <div class="box box-info">
                                        <div class="box-header with-border">
                                            <h3 class="box-title">Horizontal Form</h3>
                                        </div>
                                        <!-- /.box-header -->
                                        <!-- form start -->
                                        <form class="form-horizontal">
                                            <div class="box-body">
                                                <div class="form-group">
                                                    <label for="inputEmail3" class="col-sm-2 control-label">Email</label>

                                                    <div class="col-sm-10">
                                                        <input type="email" class="form-control" id="inputEmail3" placeholder="Email">
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label for="inputPassword3" class="col-sm-2 control-label">Password</label>

                                                    <div class="col-sm-10">
                                                        <input type="password" class="form-control" id="inputPassword3" placeholder="Password">
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <div class="col-sm-offset-2 col-sm-10">
                                                        <div class="checkbox">
                                                            <label>
                                                                <input type="checkbox"> Remember me
                                                            </label>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <!-- /.box-body -->
                                            <div class="box-footer">
                                                <button type="submit" class="btn btn-default">Cancel</button>
                                                <button type="submit" class="btn btn-info pull-right">Sign in</button>
                                            </div>
                                            <!-- /.box-footer -->
                                        </form>
                                    </div>
                                    <!-- /.box -->
                                    <!-- general form elements disabled -->
                                    <div class="box box-warning">
                                        <div class="box-header with-border">
                                            <h3 class="box-title">General Elements</h3>
                                        </div>
                                        <!-- /.box-header -->
                                        <div class="box-body">
                                            <form role="form">
                                                <!-- text input -->
                                                <div class="form-group">
                                                    <label>Text</label>
                                                    <input type="text" class="form-control" placeholder="Enter ...">
                                                </div>
                                                <div class="form-group">
                                                    <label>Text Disabled</label>
                                                    <input type="text" class="form-control" placeholder="Enter ..." disabled>
                                                </div>

                                                <!-- textarea -->
                                                <div class="form-group">
                                                    <label>Textarea</label>
                                                    <textarea class="form-control" rows="3" placeholder="Enter ..."></textarea>
                                                </div>
                                                <div class="form-group">
                                                    <label>Textarea Disabled</label>
                                                    <textarea class="form-control" rows="3" placeholder="Enter ..." disabled></textarea>
                                                </div>

                                                <!-- input states -->
                                                <div class="form-group has-success">
                                                    <label class="control-label" for="inputSuccess"><i class="fa fa-check"></i> Input with success</label>
                                                    <input type="text" class="form-control" id="inputSuccess" placeholder="Enter ...">
                                                    <span class="help-block">Help block with success</span>
                                                </div>
                                                <div class="form-group has-warning">
                                                    <label class="control-label" for="inputWarning"><i class="fa fa-bell-o"></i> Input with
                                                        warning</label>
                                                    <input type="text" class="form-control" id="inputWarning" placeholder="Enter ...">
                                                    <span class="help-block">Help block with warning</span>
                                                </div>
                                                <div class="form-group has-error">
                                                    <label class="control-label" for="inputError"><i class="fa fa-times-circle-o"></i> Input with
                                                        error</label>
                                                    <input type="text" class="form-control" id="inputError" placeholder="Enter ...">
                                                    <span class="help-block">Help block with error</span>
                                                </div>

                                                <!-- checkbox -->
                                                <div class="form-group">
                                                    <div class="checkbox">
                                                        <label>
                                                            <input type="checkbox">
                                                            Checkbox 1
                                                        </label>
                                                    </div>

                                                    <div class="checkbox">
                                                        <label>
                                                            <input type="checkbox">
                                                            Checkbox 2
                                                        </label>
                                                    </div>

                                                    <div class="checkbox">
                                                        <label>
                                                            <input type="checkbox" disabled>
                                                            Checkbox disabled
                                                        </label>
                                                    </div>
                                                </div>

                                                <!-- radio -->
                                                <div class="form-group">
                                                    <div class="radio">
                                                        <label>
                                                            <input type="radio" name="optionsRadios" id="optionsRadios1" value="option1" checked>
                                                            Option one is this and that&mdash;be sure to include why it's great
                                                        </label>
                                                    </div>
                                                    <div class="radio">
                                                        <label>
                                                            <input type="radio" name="optionsRadios" id="optionsRadios2" value="option2">
                                                            Option two can be something else and selecting it will deselect option one
                                                        </label>
                                                    </div>
                                                    <div class="radio">
                                                        <label>
                                                            <input type="radio" name="optionsRadios" id="optionsRadios3" value="option3" disabled>
                                                            Option three is disabled
                                                        </label>
                                                    </div>
                                                </div>

                                                <!-- select -->
                                                <div class="form-group">
                                                    <label>Select</label>
                                                    <select class="form-control">
                                                        <option>option 1</option>
                                                        <option>option 2</option>
                                                        <option>option 3</option>
                                                        <option>option 4</option>
                                                        <option>option 5</option>
                                                    </select>
                                                </div>
                                                <div class="form-group">
                                                    <label>Select Disabled</label>
                                                    <select class="form-control" disabled>
                                                        <option>option 1</option>
                                                        <option>option 2</option>
                                                        <option>option 3</option>
                                                        <option>option 4</option>
                                                        <option>option 5</option>
                                                    </select>
                                                </div>

                                                <!-- Select multiple-->
                                                <div class="form-group">
                                                    <label>Select Multiple</label>
                                                    <select multiple class="form-control">
                                                        <option>option 1</option>
                                                        <option>option 2</option>
                                                        <option>option 3</option>
                                                        <option>option 4</option>
                                                        <option>option 5</option>
                                                    </select>
                                                </div>
                                                <div class="form-group">
                                                    <label>Select Multiple Disabled</label>
                                                    <select multiple class="form-control" disabled>
                                                        <option>option 1</option>
                                                        <option>option 2</option>
                                                        <option>option 3</option>
                                                        <option>option 4</option>
                                                        <option>option 5</option>
                                                    </select>
                                                </div>

                                            </form>
                                        </div>
                                        <!-- /.box-body -->
                                    </div>
                                    <!-- /.box -->
                                </div>
                                <!--/.col (right) -->
                            </div>
                            <!-- /.row -->
                        </section>
                    </div>
                    <!-- /.tab-pane -->
                {{-- <div class="tab-pane" id="tab_2">
                     The European languages are members of the same family. Their separate existence is a myth.
                     For science, music, sport, etc, Europe uses the same vocabulary. The languages only differ
                     in their grammar, their pronunciation and their most common words. Everyone realizes why a
                     new common language would be desirable: one could refuse to pay expensive translators. To
                     achieve this, it would be necessary to have uniform grammar, pronunciation and more common
                     words. If several languages coalesce, the grammar of the resulting language is more simple
                     and regular than that of the individual languages.
                 </div>--}}
                <!-- /.tab-pane -->
                {{-- <div class="tab-pane" id="tab_3">
                     Lorem Ipsum is simply dummy text of the printing and typesetting industry.
                     Lorem Ipsum has been the industry's standard dummy text ever since the 1500s,
                     when an unknown printer took a galley of type and scrambled it to make a type specimen book.
                     It has survived not only five centuries, but also the leap into electronic typesetting,
                     remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset
                     sheets containing Lorem Ipsum passages, and more recently with desktop publishing software
                     like Aldus PageMaker including versions of Lorem Ipsum.
                 </div>--}}
                <!-- /.tab-pane -->
                </div>
                <!-- /.tab-content -->
            </div>
            <!-- nav-tabs-custom -->
        </div>
        <!-- /.col -->


        <!-- /.col -->
    </div>
    <div id="myModal" class="modal fade" data-keyboard="false"
         data-backdrop="static" data-role="dialog"
         aria-labelledby="myModalLabel" aria-hidden="true">
        <div  id="loading" class="loading">
            <div class="spinner">
                <div class="spinner-container container1">
                    <div class="circle1"></div>
                    <div class="circle2"></div>
                    <div class="circle3"></div>
                    <div class="circle4"></div>
                </div>
                <div class="spinner-container container2">
                    <div class="circle1"></div>
                    <div class="circle2"></div>
                    <div class="circle3"></div>
                    <div class="circle4"></div>
                </div>
                <div class="spinner-container container3">
                    <div class="circle1"></div>
                    <div class="circle2"></div>
                    <div class="circle3"></div>
                    <div class="circle4"></div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('script')
    <script>
        $('.s-tab').click(function () {
            $('#myModal').modal('show');
            window.location.href=$(this).attr('data-url');
        });

        function back()
        {
            window.history.go(-1);
        }
        function forward()
        {

            window.history.go(1);
        }
        function refresh()
        {
            history.go(-0) //刷新
        }
    </script>
@endsection