@if ($paginator->hasPages())
    <nav role="navigation" aria-label="{{ __('Pagination Navigation') }}" class="flex items-center justify-between">
        <div class="flex justify-between flex-1 sm:hidden">
            @if ($paginator->onFirstPage())
                <span class="relative inline-flex items-center px-4 py-2 text-xs font-black uppercase text-slate-500 bg-slate-900 rounded-xl cursor-default">
                    {!! __('pagination.previous') !!}
                </span>
            @else
                <a href="{{ $paginator->previousPageUrl() }}" class="relative inline-flex items-center px-4 py-2 text-xs font-black uppercase text-white bg-slate-900 rounded-xl hover:bg-slate-800 transition-all">
                    {!! __('pagination.previous') !!}
                </a>
            @endif

            @if ($paginator->hasMorePages())
                <a href="{{ $paginator->nextPageUrl() }}" class="relative inline-flex items-center px-4 py-2 ml-3 text-xs font-black uppercase text-white bg-slate-900 rounded-xl hover:bg-slate-800 transition-all">
                    {!! __('pagination.next') !!}
                </a>
            @else
                <span class="relative inline-flex items-center px-4 py-2 ml-3 text-xs font-black uppercase text-slate-500 bg-slate-900 rounded-xl cursor-default">
                    {!! __('pagination.next') !!}
                </span>
            @endif
        </div>

        <div class="hidden sm:flex-1 sm:flex sm:items-center sm:justify-between">
            <div>
                <p class="text-xs font-medium text-slate-400">
                    Mostrando <span class="text-slate-900 font-bold">{{ $paginator->firstItem() }}</span> - <span class="text-slate-900 font-bold">{{ $paginator->lastItem() }}</span> de <span class="text-slate-900 font-bold">{{ $paginator->total() }}</span>
                </p>
            </div>

            <div class="flex items-center bg-slate-900 rounded-xl overflow-hidden border border-slate-800 shadow-xl shadow-black/20 divide-x divide-slate-800">
                {{-- Previous Page Link --}}
                @if ($paginator->onFirstPage())
                    <span class="px-4 py-1.5 text-slate-600 cursor-default">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" /></svg>
                    </span>
                @else
                    <a href="{{ $paginator->previousPageUrl() }}" class="px-4 py-1.5 text-slate-400 hover:text-white hover:bg-slate-800 transition-colors">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" /></svg>
                    </a>
                @endif

                {{-- Pagination Elements --}}
                @foreach ($elements as $element)
                    @if (is_string($element))
                        <span class="px-4 py-1.5 text-slate-400 font-bold">{{ $element }}</span>
                    @endif

                    @if (is_array($element))
                        @foreach ($element as $page => $url)
                            @if ($page == $paginator->currentPage())
                                <span class="px-5 py-1.5 text-sm font-black text-primary bg-slate-950 shadow-inner z-10">{{ $page }}</span>

                            @else
                                <a href="{{ $url }}" class="px-5 py-1.5 text-sm font-bold text-white hover:bg-slate-800 transition-colors">{{ $page }}</a>
                            @endif
                        @endforeach
                    @endif
                @endforeach

                {{-- Next Page Link --}}
                @if ($paginator->hasMorePages())
                    <a href="{{ $paginator->nextPageUrl() }}" class="px-4 py-1.5 text-slate-400 hover:text-white hover:bg-slate-800 transition-colors">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" /></svg>
                    </a>
                @else
                    <span class="px-4 py-1.5 text-slate-600 cursor-default">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" /></svg>
                    </span>
                @endif
            </div>
        </div>
    </nav>
@endif
