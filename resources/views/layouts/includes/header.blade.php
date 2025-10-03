<header class="bg-white">
    <nav aria-label="Global" class="mx-auto flex items-center justify-between p-6 lg:px-8">
        <div class="flex lg:flex-1">
            <a href="{{ route('article.index') }}" class="-m-1.5 p-1.5">
                <span class="sr-only">Főoldal</span>
                <img src="https://tailwindcss.com/plus-assets/img/logos/mark.svg?color=indigo&shade=600" alt="" class="h-8 w-auto" />
            </a>
        </div>
        <div class="hidden lg:flex lg:items-center lg:gap-x-12">
            <a href="{{ route('article.index') }}" class="text-sm/6 font-semibold text-gray-900">Cikkek</a>
            <a href="{{ route('keyword.index') }}" class="text-sm/6 font-semibold text-gray-900">Kulcsszavak</a>
            <a href="{{ route('calendar.index') }}" class="text-sm/6 font-semibold text-gray-900">Naptár</a>
            <a href="{{ route('portal.index') }}" class="text-sm/6 font-semibold text-gray-900">Portálok</a>
            <a href="{{ route('similarity.index') }}" class="text-sm/6 font-semibold text-gray-900">Hasonlóságok</a>
            <a href="{{ route('collection.index') }}" class="text-sm/6 font-semibold text-gray-900">Gyűjtemények</a>
        </div>
        <div class="hidden lg:flex lg:flex-1 lg:justify-end lg:items-center lg:gap-4">
            <form action="{{ route('article.search') }}" method="get" class="relative w-72">
                <input type="text" name="q" value="{{ request('q') }}" placeholder="Keresés..." class="w-full border rounded-md pl-3 pr-10 py-2" />
                <button type="submit" class="absolute right-1 top-1/2 -translate-y-1/2 px-2 py-1 text-sm text-white bg-blue-600 rounded">OK</button>
            </form>
        </div>
        <div class="flex lg:hidden">
            <button type="button" command="show-modal" commandfor="mobile-menu" class="-m-2.5 inline-flex items-center justify-center rounded-md p-2.5 text-gray-700">
                <span class="sr-only">Open main menu</span>
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" data-slot="icon" aria-hidden="true" class="size-6">
                    <path d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5" stroke-linecap="round" stroke-linejoin="round" />
                </svg>
            </button>
        </div>
    </nav>
    <el-dialog>
        <dialog id="mobile-menu" class="backdrop:bg-transparent lg:hidden">
            <div tabindex="0" class="fixed inset-0 focus:outline-none">
                <el-dialog-panel class="fixed inset-y-0 right-0 z-50 w-full overflow-y-auto bg-white p-6 sm:max-w-sm sm:ring-1 sm:ring-gray-900/10">
                    <div class="flex items-center justify-between">
                        <a href="{{ route('article.index') }}" class="-m-1.5 p-1.5">
                            <span class="sr-only">Főoldal</span>
                            <img src="https://tailwindcss.com/plus-assets/img/logos/mark.svg?color=indigo&shade=600" alt="" class="h-8 w-auto" />
                        </a>
                        <button type="button" command="close" commandfor="mobile-menu" class="-m-2.5 rounded-md p-2.5 text-gray-700">
                            <span class="sr-only">Close menu</span>
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" data-slot="icon" aria-hidden="true" class="size-6">
                                <path d="M6 18 18 6M6 6l12 12" stroke-linecap="round" stroke-linejoin="round" />
                            </svg>
                        </button>
                    </div>
                    <div class="mt-6 flow-root">
                        <div class="-my-6 divide-y divide-gray-500/10">
                            <div class="space-y-2 py-6">
                                <a href="{{ route('article.index') }}" class="-mx-3 block rounded-lg px-3 py-2 text-base/7 font-semibold text-gray-900 hover:bg-gray-50">Cikkek</a>
                                <a href="{{ route('keyword.index') }}" class="-mx-3 block rounded-lg px-3 py-2 text-base/7 font-semibold text-gray-900 hover:bg-gray-50">Kulcsszavak</a>
                                <a href="{{ route('calendar.index') }}" class="-mx-3 block rounded-lg px-3 py-2 text-base/7 font-semibold text-gray-900 hover:bg-gray-50">Naptár</a>
                                <a href="{{ route('portal.index') }}" class="-mx-3 block rounded-lg px-3 py-2 text-base/7 font-semibold text-gray-900 hover:bg-gray-50">Portálok</a>
                                <a href="{{ route('similarity.index') }}" class="-mx-3 block rounded-lg px-3 py-2 text-base/7 font-semibold text-gray-900 hover:bg-gray-50">Hasonlóságok</a>
                                <a href="{{ route('collection.index') }}" class="-mx-3 block rounded-lg px-3 py-2 text-base/7 font-semibold text-gray-900 hover:bg-gray-50">Gyűjtemények</a>
                            </div>
                            <div class="py-6">
                                <form action="{{ route('article.search') }}" method="get" class="flex gap-2">
                                    <input type="text" name="q" value="{{ request('q') }}" placeholder="Keresés..." class="w-full border rounded-md px-3 py-2" />
                                    <button type="submit" class="px-3 py-2 bg-blue-600 text-white rounded-md">Keresés</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </el-dialog-panel>
            </div>
        </dialog>
    </el-dialog>
</header>
