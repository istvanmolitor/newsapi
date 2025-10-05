<footer class="shadow-sm bg-blue-900">
    <div class="w-full mx-auto p-4 md:py-8">
        <div class="sm:flex sm:items-center sm:justify-between">
            <ul class="flex flex-wrap items-center mb-6 text-sm font-medium text-gray-500 sm:mb-0 dark:text-gray-400">
                <li>
                    <a href="{{ route('article.index') }}" class="hover:underline me-4 md:me-6 text-white">Cikkek</a>
                </li>
                <li>
                    <a href="{{ route('keyword.index') }}" class="hover:underline me-4 md:me-6 text-white">Kulcsszavak</a>
                </li>
                <li>
                    <a href="{{ route('calendar.index') }}" class="hover:underline me-4 md:me-6 text-white">Naptár</a>
                </li>
                <li>
                    <a href="{{ route('portal.index') }}" class="hover:underline me-4 md:me-6 text-white">Portálok</a>
                </li>
            </ul>
        </div>
        <hr class="my-6 border-gray-200 sm:mx-auto dark:border-white lg:my-8" />
        <span class="block text-sm  text-white sm:text-center">© 2023 <a href="https://flowbite.com/" class="hover:underline">Flowbite™</a>. All Rights Reserved.</span>
    </div>
</footer>
