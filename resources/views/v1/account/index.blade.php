<x-v1.layout page="Accounts">
    <section>
        <div class="mb-4 text-xl font-bold text-gray-900 dark:text-white">Account Management</div>
    </section>
    <section class="bg-gray-50 dark:bg-gray-900">
        <div class="mx-auto">
            <div class="bg-white dark:bg-gray-800 relative shadow-md sm:rounded-lg">
                <div class="flex flex-col md:flex-row items-center justify-between space-y-3 md:space-y-0 md:space-x-4 p-4">
                    <div class="w-full md:w-1/2">
                        <div class="font-bold text-gray-900 dark:text-white">Accounts</div>
                    </div>
                    <div class="w-full md:w-auto flex flex-col md:flex-row space-y-2 md:space-y-0 items-stretch md:items-center justify-end md:space-x-3 flex-shrink-0">
                        <a href="{{ route('account.create') }}" class="flex items-center justify-center text-white bg-primary-700 hover:bg-primary-800 focus:ring-4 focus:ring-primary-300 font-medium rounded-lg text-sm px-4 py-2 dark:bg-primary-600 dark:hover:bg-primary-700 focus:outline-none dark:focus:ring-primary-800">
                            <svg class="h-3.5 w-3.5 mr-2" fill="currentColor" viewbox="0 0 20 20" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                                <path clip-rule="evenodd" fill-rule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" />
                            </svg>
                            Add accounts
                        </a>
                    </div>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
                        <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                        <tr>
{{--                            <th scope="col" class="p-4">--}}
{{--                                <div class="flex items-center">--}}
{{--                                    <input id="checkbox-all-search" type="checkbox" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 dark:focus:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">--}}
{{--                                    <label for="checkbox-all-search" class="sr-only">checkbox</label>--}}
{{--                                </div>--}}
{{--                            </th>--}}
                            <th scope="col" class="px-4 py-3">Name</th>
                            <th scope="col" class="px-4 py-3">Group</th>
                            <th scope="col" class="px-4 py-3">Agent</th>
                            <th scope="col" class="px-4 py-3">Script</th>
                            <th scope="col" class="px-4 py-3">Status</th>
                            <th scope="col" class="px-4 py-3">
                                <span class="sr-only">Actions</span>
                            </th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($accounts as $account)
                            <tr class="border-b dark:border-gray-700">
{{--                                <td class="w-4 p-4">--}}
{{--                                    <div class="flex items-center">--}}
{{--                                        <input id="checkbox-table-search-1" type="checkbox" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 dark:focus:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">--}}
{{--                                        <label for="checkbox-table-search-1" class="sr-only">checkbox</label>--}}
{{--                                    </div>--}}
{{--                                </td>--}}
                                <th scope="row" class="px-4 py-3 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                    <a href="{{ route('account.show', $account->id) }}">{{ $account->email }}</a>
                                </th>
                                <td class="px-4 py-3 text-gray-900 whitespace-nowrap dark:text-white">
                                    @if($account->account_group)
                                        <a class="font-medium" href="{{ route('account.group.show', $account->account_group_id) }}">{{ $account->account_group->name }}</a>
                                    @else
                                        <span>-</span>
                                    @endif
                                </td>
                                <td class="px-4 py-3 text-gray-900 whitespace-nowrap dark:text-white">
                                    @if($account->agent)
                                        <a class="font-medium" href="{{ route('agent.show', $account->agent_id) }}">{{ $account->agent->name }}</a>
                                    @else
                                        <span>-</span>
                                    @endif
                                </td>
                                <td class="px-4 py-3 text-gray-900 whitespace-nowrap dark:text-white">
                                    @if($account->script)
                                        <a class="font-medium" href="{{ route('script.show', $account->script_id) }}">{{ $account->script->name }}</a>
                                    @else
                                        <span>-</span>
                                    @endif
                                </td>
                                <td class="px-4 py-3">
                                    @php
                                        $icon = '<div class="h-2.5 w-2.5 rounded-full bg-red-500 me-2"></div>';
                                        if ($account->status == 'Starting' || $account->status == 'Stopping') {
                                            $icon = '<div class="h-2.5 w-2.5 rounded-full bg-yellow-500 me-2"></div>';
                                        }
                                        if ($account->status == 'Running') {
                                            $icon = '<div class="h-2.5 w-2.5 rounded-full bg-green-500 me-2"></div>';
                                        }
                                        $status = $account->status;
                                        if ($account->temp_banned_at) {
                                            $status = 'Banned (Temporary)';
                                        }
                                        if ($account->perm_banned_at) {
                                            $status = 'Banned (Permanent)';
                                        }
                                    @endphp
                                    <div class="flex items-center">{!! $icon !!} {{ $status }}</div>
                                </td>
                                <td class="px-4 py-3 flex items-center justify-end">
                                    <button id="account-{{ $account->id }}-dropdown-button" data-dropdown-toggle="account-{{ $account->id }}-dropdown" class="inline-flex items-center p-0.5 text-sm font-medium text-center text-gray-500 hover:text-gray-800 rounded-lg focus:outline-none dark:text-gray-400 dark:hover:text-gray-100" type="button">
                                        <svg class="w-5 h-5" aria-hidden="true" fill="currentColor" viewbox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                            <path d="M6 10a2 2 0 11-4 0 2 2 0 014 0zM12 10a2 2 0 11-4 0 2 2 0 014 0zM16 12a2 2 0 100-4 2 2 0 000 4z" />
                                        </svg>
                                    </button>
                                    <div id="account-{{ $account->id }}-dropdown" class="mt-[10.25rem] mr-[-1rem] hidden z-10 w-44 bg-white rounded divide-y divide-gray-100 shadow dark:bg-gray-700 dark:divide-gray-600">
                                        <ul class="py-1 text-sm text-gray-700 dark:text-gray-200" aria-labelledby="account-{{ $account->id }}-dropdown-button">
                                            @if($account->status == 'Running' || $account->status == 'Starting')
                                                <li>
                                                    <form method="post" action="{{ route('account.stop', $account->id) }}">
                                                        @csrf
                                                        <button href="#" class="w-full text-left block py-2 px-4 text-sm text-gray-700 hover:bg-gray-100 dark:hover:bg-gray-600 dark:text-gray-200 dark:hover:text-white">Stop</button>
                                                    </form>
                                                </li>
                                            @elseif($account->status == 'Stopped' || $account->status == 'Stopping' || $account->status == 'Banned')
                                                <li>
                                                    <form method="post" action="{{ route('account.start', $account->id) }}">
                                                        @csrf
                                                        <button href="#" class="w-full text-left block py-2 px-4 text-sm text-gray-700 hover:bg-gray-100 dark:hover:bg-gray-600 dark:text-gray-200 dark:hover:text-white">Start</button>
                                                    </form>
                                                </li>
                                            @endif
                                        </ul>
                                        <div class="py-1">
                                            <div class="text-gray-700 dark:text-gray-200">
                                                <a href="{{ route('account.show', $account->id) }}" class="block py-2 px-4 hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white">Edit</a>
                                            </div>
                                            <form method="post" action="{{ route('account.destroy', $account->id) }}">
                                                @csrf
                                                <input type="hidden" name="_method" value="DELETE">
                                                <button href="#" class="w-full text-left block py-2 px-4 text-sm text-gray-700 hover:bg-gray-100 dark:hover:bg-gray-600 dark:text-gray-200 dark:hover:text-white">Delete</button>
                                            </form>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="flex p-3">
                    <div class="flex-grow">
                        <span class="text-sm font-normal text-gray-500 dark:text-gray-400">
                            Showing
                            <span class="font-semibold text-gray-900 dark:text-white">
                                {{ ($accounts->currentPage() - 1) * $accounts->perPage() + 1 }}-{{ min($accounts->total(), $accounts->currentPage() * $accounts->perPage()) }}
                            </span>
                            of
                            <span class="font-semibold text-gray-900 dark:text-white">{{ $accounts->total() }}</span>
                        </span>
                    </div>
                    <nav>
                        <ul class="inline-flex -space-x-px text-sm">
                            @php
                                $currentPage = $accounts->currentPage();
                                $lastPage = $accounts->lastPage();
                                $pages = [];

                                for($i = $currentPage - 3; $i <= $currentPage + 3; $i++) {
                                    if ($i == $lastPage) {
                                        $pages[] = $i;
                                        continue;
                                    }
                                    if ($i == $currentPage) {
                                        $pages[] = $i;
                                        continue;
                                    }
                                    if ($i < 1 || $i >= $lastPage) {
                                        continue;
                                    }
                                    $pages[] = $i;
                                }
                            @endphp

                            @if(!in_array(1, $pages))
                                <li><a href="?page=1" class="flex items-center justify-center px-3 h-8 leading-tight text-gray-500 bg-white border border-gray-300 hover:bg-gray-100 hover:text-gray-700 dark:bg-gray-800 dark:border-gray-700 dark:text-gray-400 dark:hover:bg-gray-700 dark:hover:text-white">1</a></li>
                                @if($pages[0] != 2)
                                    <li><span class="flex items-center justify-center px-3 h-8 leading-tight text-gray-500 bg-white border border-gray-300 dark:bg-gray-800 dark:border-gray-700 dark:text-gray-400">...</span></li>
                                @endif
                            @endif

                            @foreach ($pages as $page)
                                @if ($page == $currentPage)
                                    <li><a href="#" aria-current="page" class="flex items-center justify-center px-3 h-8 text-blue-600 border border-gray-300 bg-blue-50 hover:bg-blue-100 hover:text-blue-700 dark:border-gray-700 dark:bg-gray-700 dark:text-white">{{ $page }}</a></li>
                                @else
                                    <li><a href="?page={{ $page }}" class="flex items-center justify-center px-3 h-8 leading-tight text-gray-500 bg-white border border-gray-300 hover:bg-gray-100 hover:text-gray-700 dark:bg-gray-800 dark:border-gray-700 dark:text-gray-400 dark:hover:bg-gray-700 dark:hover:text-white">{{ $page }}</a></li>
                                @endif
                            @endforeach

                            @if(!in_array($lastPage, $pages))

                                @if($pages[count($pages)-1] != $lastPage-1)
                                    <li><span class="flex items-center justify-center px-3 h-8 leading-tight text-gray-500 bg-white border border-gray-300 dark:bg-gray-800 dark:border-gray-700 dark:text-gray-400">...</span></li>
                                @endif
                                <li><a href="?page={{ $lastPage }}" class="flex items-center justify-center px-3 h-8 leading-tight text-gray-500 bg-white border border-gray-300 hover:bg-gray-100 hover:text-gray-700 dark:bg-gray-800 dark:border-gray-700 dark:text-gray-400 dark:hover:bg-gray-700 dark:hover:text-white">{{ $lastPage }}</a></li>
                            @endif
                        </ul>
                    </nav>
                </div>
            </div>
        </div>
    </section>
    <script>
        const dropdownToggles = document.querySelectorAll('[data-dropdown-toggle]');
        if (dropdownToggles) {
            dropdownToggles.forEach(function(toggle) {
                toggle.addEventListener('click', function() {
                    const dropdownMenuId = toggle.getAttribute('data-dropdown-toggle');
                    const dropdownMenu = document.getElementById(dropdownMenuId);
                    if (dropdownMenu) {
                        if (dropdownMenu.classList.contains('hidden')) {
                            dropdownMenu.classList.remove('hidden');
                            dropdownMenu.classList.add('absolute');
                        } else {
                            dropdownMenu.classList.add('hidden');
                            dropdownMenu.classList.remove('absolute');
                        }
                    }
                });
            });
        }

        const masterCheckbox = document.getElementById('checkbox-all-search');
        if (masterCheckbox) {
            const rowCheckboxes = document.querySelectorAll('input[type="checkbox"]');
            masterCheckbox.addEventListener('change', function() {
                rowCheckboxes.forEach(function(checkbox) {
                    if (checkbox !== masterCheckbox) {
                        checkbox.checked = masterCheckbox.checked;
                    }
                });
            });
        }
    </script>
</x-v1.layout>



{{--<x-v1.layout>--}}
{{--    <div class="mb-2 text-xl font-bold">Proxy Management</div>--}}
{{--    @if($errors->isNotEmpty())--}}
{{--        <div>{{ $errors }}</div>--}}
{{--    @endif--}}
{{--    @if (session('status'))--}}
{{--        <div>{{ session('status') }}</div>--}}
{{--    @endif--}}

{{--    <div class="grid grid-cols-[1fr,auto] gap-2 py-2">--}}
{{--        <div class="py-2 font-bold">Proxy Groups</div>--}}
{{--        <div class="py-2"><a href="{{ route('proxy.group.create') }}" class="rounded-md bg-blue-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-blue-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-blue-600">Create</a></div>--}}
{{--    </div>--}}

{{--    <div class="relative overflow-x-auto">--}}
{{--        <table class="w-full text-sm text-left text-gray-500">--}}
{{--            <thead class="text-xs text-white bg-gray-500">--}}
{{--            <tr>--}}
{{--                <th class="px-6 py-3">Name</th>--}}
{{--                <th class="px-6 py-3"></th>--}}
{{--            </tr>--}}
{{--            </thead>--}}
{{--            <tbody>--}}
{{--            @foreach(auth()->user()->proxy_groups as $group)--}}
{{--                <tr class="bg-white border hover:bg-gray-50">--}}
{{--                    <td class="px-6 py-4">{{ $group->name }}</td>--}}
{{--                    <td class="px-6 py-4 text-right">--}}
{{--                        <a href="{{ route('proxy.group.show', $group->id) }}" class="font-medium text-blue-600 hover:underline">Edit</a>--}}
{{--                    </td>--}}
{{--                </tr>--}}
{{--            @endforeach--}}
{{--            </tbody>--}}
{{--        </table>--}}
{{--    </div>--}}

{{--    <div class="grid grid-cols-[1fr,auto] gap-2 py-2">--}}
{{--        <div class="py-2 font-bold">Proxies</div>--}}
{{--        <div class="py-2"><a href="{{ route('proxy.create') }}" class="rounded-md bg-blue-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-blue-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-blue-600">Add</a></div>--}}
{{--    </div>--}}

{{--    <div class="relative overflow-x-auto">--}}
{{--        <table class="w-full text-sm text-left text-gray-500">--}}
{{--            <thead class="text-xs text-white bg-gray-500">--}}
{{--            <tr>--}}
{{--                <th class="px-6 py-3">Host</th>--}}
{{--                <th class="px-6 py-3">Port</th>--}}
{{--                <th class="px-6 py-3">Auth</th>--}}
{{--                <th class="px-6 py-3">Group</th>--}}
{{--                <th class="px-6 py-3"></th>--}}
{{--            </tr>--}}
{{--            </thead>--}}
{{--            <tbody>--}}
{{--            @foreach($accounts as $account)--}}
{{--                <tr class="bg-white border hover:bg-gray-50">--}}
{{--                    <td class="px-6 py-4">{{ $account->host }}</td>--}}
{{--                    <td class="px-6 py-4">{{ $account->port }}</td>--}}
{{--                    <td class="px-6 py-4">{{ $account->username && $account->password ? $account->username.":".$account->password : "N/A" }}</td>--}}
{{--                    <td class="px-6 py-4">--}}
{{--                        @if($account->proxy_group)--}}
{{--                            <a href="{{ route('proxy.group.show', $account->proxy_group_id) }}" class="text-blue-600 hover:text-blue-500">{{ $account->proxy_group->name }}</a>--}}
{{--                        @endif--}}
{{--                    </td>--}}
{{--                    <td class="px-6 py-4 text-right">--}}
{{--                        <a href="{{ route('proxy.show', $account->id) }}" class="font-medium text-blue-600 hover:underline">Edit</a>--}}
{{--                    </td>--}}
{{--                </tr>--}}
{{--            @endforeach--}}
{{--            </tbody>--}}
{{--        </table>--}}
{{--    </div>--}}

{{--</x-v1.layout>--}}
