<ul class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-3 mb-6">
    @foreach($keywords as $k)
        <li>
            <a href="{{ route('keyword.show', $k) }}" class="inline-flex items-center justify-between w-full rounded-lg border border-slate-200 bg-white px-4 py-3 text-slate-800 shadow-sm hover:shadow transition hover:border-slate-300 dark:bg-slate-800 dark:border-slate-700 dark:text-slate-100">
                <span class="truncate"># {{ $k->keyword }}</span>
                <span class="ml-3 shrink-0 inline-flex items-center gap-1 rounded-full bg-slate-100 px-2 py-0.5 text-xs font-medium text-slate-700 dark:bg-slate-700 dark:text-slate-100">{{ $k->articles_count ?? $k->articles()->count() }} db</span>
            </a>
        </li>
    @endforeach
</ul>
