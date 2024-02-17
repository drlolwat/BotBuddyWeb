<x-v1.layout page="Import Accounts">
    <div class="max-w-2xl px-4 mx-auto">
        <h2 class="mb-4 text-xl font-bold text-gray-900 dark:text-white">Import accounts</h2>
        <form method="post" action="{{ route('account.import.store') }}" enctype="multipart/form-data">
            @csrf
            <div class="grid gap-4 mb-4 sm:grid-cols-2 sm:gap-6 sm:mb-5">
                <div class="sm:col-span-2">
                    <label for="account_textarea" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Accounts</label>
                    <textarea name="account_textarea" id="account_textarea" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500"></textarea>
                </div>
                <div class="sm:col-span-2">
                    <details class="mb-2 text-gray-900 dark:text-white" open>
                        <summary>Accepted formats</summary>
                        <ul class="text-xs mb-2 w-full">
                            <li>user:pass</li>
                            <li>user:pass:2fa_pass</li>
                            <li>user:pass:proxy_host:proxy_port:proxy_user:proxy_pass</li>
                            <li>user:pass:2fa_pass:proxy_host:proxy_port:proxy_user:proxy_pass</li>
                        </ul>
                    </details>
                </div>
                <div class="sm:col-span-2">
                    <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white" for="account_input">Accounts from file</label>
                    <input type="file" name="account_file" class=" mb-2 w-full text-gray-900 dark:text-white" />
                </div>
                <div class="sm:col-span-2">
                    <label for="account_group_id" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Account Group</label>
                    <select name="account_group_id" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500">
                        <option value="0">None</option>
                        @foreach(auth()->user()->account_groups as $group)
                            <option value="{{ $group->id }}">{{ $group->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="sm:col-span-2">
                    <label for="agent_id" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Agent</label>
                    <select name="agent_id" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500">
                        <option value="0">None</option>
                        @foreach(auth()->user()->agents as $agent)
                            <option value="{{ $agent->id }}">{{ $agent->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="sm:col-span-2">
                    <label for="proxy_id" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Proxy</label>
                    <select name="proxy_id" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500">
                        <option value="0">None</option>
                        @foreach(auth()->user()->proxies as $proxy)
                            <option value="{{ $proxy->id }}">{{ $proxy->host }}:{{ $proxy->port }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
{{--            <div class="grid gap-4 mb-4 sm:grid-cols-2 sm:gap-6 sm:mb-5">--}}
{{--                <div class="sm:col-span-2">--}}
{{--                    <label for="name" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Name</label>--}}
{{--                    <input type="text" name="name" id="name" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500" placeholder="Type the group name">--}}
{{--                </div>--}}
{{--            </div>--}}
            <div class="flex items-center space-x-4">
                <button type="submit" class="text-white bg-primary-700 hover:bg-primary-800 focus:ring-4 focus:outline-none focus:ring-primary-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-primary-600 dark:hover:bg-primary-700 dark:focus:ring-primary-800">
                    Import
                </button>
            </div>
        </form>
    </div>
</x-v1.layout>
