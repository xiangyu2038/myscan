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
<script>
    $('#myModal').click(function () {
        $(this).modal('hide');
    })
</script>