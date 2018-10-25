@if ($paginator->hasPages())
    <ul class="pagination pull-right">
        {{-- Previous Page Link --}}
        @if ($paginator->onFirstPage())
            <li class="page-item disabled"><span class="page-link">&laquo;</span></li>
        @else
            <li class="page-item pages" data-url="{{ $paginator->previousPageUrl() }}"><a class="page-link" href="javascript:;"  rel="prev">&laquo;</a></li>
        @endif

        {{-- Pagination Elements --}}
        @foreach ($elements as $element)
            {{-- "Three Dots" Separator --}}
            @if (is_string($element))
                <li class="page-item disabled"><span class="page-link">{{ $element }}</span></li>
            @endif

            {{-- Array Of Links --}}
            @if (is_array($element))
                @foreach ($element as $page => $url)
                    @if ($page == $paginator->currentPage())
                        <li class="page-item active"><span class="page-link">{{ $page }}</span></li>
                    @else
                        <li class="page-item pages"  data-url="{{ $url }}" ><a href="javascript:;" class="page-link">{{ $page }}</a></li>
                    @endif
                @endforeach
            @endif
        @endforeach

        {{-- Next Page Link --}}
        @if ($paginator->hasMorePages())
            <li class="page-item pages"  data-url="{{ $paginator->nextPageUrl() }}" ><a class="page-link" href="javascript:;"  rel="next">&raquo;</a></li>
        @else
            <li class="page-item disabled"><span class="page-link">&raquo;</span></li>
        @endif
    </ul>
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