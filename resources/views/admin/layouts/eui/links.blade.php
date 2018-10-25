@if ($paginator->hasPages())
    <div class="eui-page pull-right" eui="text-left">
        {{-- Previous Page Link --}}

        @if ($paginator->onFirstPage())
            <a class="prev disabled" href="javascript:;">上一页</a>
        @else
            <a class="prev pages" data-url="{{ $paginator->previousPageUrl() }}">上一页</a>
        @endif
            <a class="first pages" data-url="{{$paginator->url($paginator->firstItem())}}">首页</a>


        {{-- Pagination Elements --}}
        @foreach ($elements as $element)
            {{-- "Three Dots" Separator --}}
            @if (is_string($element))
                <a class="next" href="javascript:;">{{ $element }}</a>
            @endif

            {{-- Array Of Links --}}
            @if (is_array($element))
                @foreach ($element as $page => $url)
                    @if ($page == $paginator->currentPage())
                        <a class="active" href="javascript:;">{{ $page }}</a>
                    @else
                        <a class="pages" data-url="{{ $url }}">{{$page}}</a>
                    @endif
                @endforeach
            @endif
        @endforeach

                                <a class="first pages" data-url="{{$paginator->url($paginator->lastPage())}}">尾页</a>
        {{-- Next Page Link --}}
        @if ($paginator->hasMorePages())
            <a class="next pages" data-url="{{ $paginator->nextPageUrl() }}">下一页</a>
        @else
            <a class="next disabled pages" href="javascript:;">下一页</a>
        @endif
    </div>
@endif
<script>

    $('.pages').click(function () {
        var key_word =  $(" input[ name='key_word' ] ").val();
        if(typeof key_word == "undefined" || key_word == null || key_word == ""){
            var url = $(this).attr('data-url');
        }else{
            var url = $(this).attr('data-url')+'&key_word='+key_word;
        }
        RE(url);
    });
</script>

