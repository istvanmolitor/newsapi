<footer class="bg-white rounded-lg shadow-sm dark:bg-gray-900 m-4">
    <div class="w-full max-w-screen-xl mx-auto p-4 md:py-8">
        <div class="sm:flex sm:items-center sm:justify-between">
            <ul class="flex flex-wrap items-center mb-6 text-sm font-medium text-gray-500 sm:mb-0 dark:text-gray-400">
                <li>
                    <a href="{{ route('article.index') }}" class="hover:underline me-4 md:me-6">Cikkek</a>
                </li>
                <li>
                    <a href="{{ route('keyword.index') }}" class="hover:underline me-4 md:me-6">Kulcsszavak</a>
                </li>
                <li>
                    <a href="{{ route('calendar.index') }}" class="hover:underline">Naptár</a>
                </li>
            </ul>
        </div>
        <hr class="my-6 border-gray-200 sm:mx-auto dark:border-gray-700 lg:my-8" />
        <span class="block text-sm text-gray-500 sm:text-center dark:text-gray-400">© 2023 <a href="https://flowbite.com/" class="hover:underline">Flowbite™</a>. All Rights Reserved.</span>
    </div>
</footer>
