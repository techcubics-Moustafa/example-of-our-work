{{--<ul class="pagination pagination-primary">
    <li class="page-item disabled"><a class="page-link" href="javascript:void(0)" tabindex="-1">سابق</a></li>
    <li class="page-item"><a class="page-link" href="javascript:void(0)">1</a></li>
    <li class="page-item active"><a class="page-link" href="javascript:void(0)">2 <span class="sr-only">(الحالي)</span></a></li>
    <li class="page-item"><a class="page-link" href="javascript:void(0)">3</a></li>
    <li class="page-item"><a class="page-link" href="javascript:void(0)">التالي</a></li>
</ul>--}}

@if ($paginator->hasPages())
    <nav aria-label="..." class="m-t-30">
        <ul class="pagination pagination-primary">
            @if ($paginator->onFirstPage())
                <li class="page-item disabled">
                    <span class="page-link">{{ _trans('Previous') }}</span>
                </li>
            @else
                <li class="page-item">
                    <a href="{{ $paginator->previousPageUrl() }}" class="page-link">{{ _trans('Previous') }}</a>
                </li>
            @endif

            @foreach ($elements as $element)
                @if (is_string($element))
                    <li class="page-item disabled"><span class="page-link">{{ $element }}</span></li>
                @endif
                @if (is_array($element))
                    @foreach ($element as $page => $url)
                        @if ($page == $paginator->currentPage())
                            <li class="page-item active">
                                <a class="page-link">{{ $page }}</a>
                            </li>
                        @else
                            <li class="page-item">
                                <a href="{{ $url }}" class="page-link">{{ $page }}</a>
                            </li>
                        @endif
                    @endforeach
                @endif
            @endforeach

            @if ($paginator->hasMorePages())
                <li class="page-item next">
                    <a class="page-link" href="{{ $paginator->nextPageUrl() }}">{{ _trans('Next') }}</a>
                </li>
            @else
                <li class="page-item next disabled">
                    <a class="page-link">{{ _trans('Next') }}</a>
                </li>
            @endif
        </ul>
    </nav>
@endif



