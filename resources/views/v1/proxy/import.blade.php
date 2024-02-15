<x-v1.layout page="Import Proxies">
    <div class="max-w-2xl px-4 mx-auto">
        <h2 class="mb-4 text-xl font-bold text-gray-900 dark:text-white">Import proxies</h2>
        <form method="post" action="{{ route('proxy.import.store') }}" enctype="multipart/form-data">
            @csrf
            <div class="grid gap-4 mb-4 sm:grid-cols-2 sm:gap-6 sm:mb-5">
                <div class="sm:col-span-2">
                    <label for="proxy_textarea" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Proxies</label>
                    <textarea name="proxy_textarea" id="proxy_textarea" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500"></textarea>
                </div>
                <div class="sm:col-span-2">
                    <details class="mb-2 text-gray-900 dark:text-white" open>
                        <summary>Accepted formats</summary>
                        <ul class="text-xs mb-2 w-full">
                            <li>host:port</li>
                            <li>host:port,user,pass</li>
                        </ul>
                    </details>
                </div>
                <div class="sm:col-span-2">
                    <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white" for="proxy_input">Proxies from file</label>
                    <input type="file" name="account_file" class=" mb-2 w-full text-gray-900 dark:text-white" />
                </div>
                <div class="sm:col-span-2">
                    <label for="proxy_group_id" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Proxy Group</label>
                    <select name="proxy_group_id" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500">
                        <option value="0">None</option>
                        @foreach(auth()->user()->proxy_groups as $group)
                            <option value="{{ $group->id }}">{{ $group->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="flex items-center space-x-4">
                <button type="submit" class="text-white bg-primary-700 hover:bg-primary-800 focus:ring-4 focus:outline-none focus:ring-primary-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-primary-600 dark:hover:bg-primary-700 dark:focus:ring-primary-800">
                    Import
                </button>
            </div>
        </form>
    </div>
</x-v1.layout>
