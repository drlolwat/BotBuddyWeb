<x-v1.layout page="Accounts">
    {{-- todo --}}
    {{--<div class="flex items-center justify-between flex-column flex-wrap md:flex-row space-y-4 md:space-y-0 pb-4 bg-white dark:bg-gray-900">
        <div>
            <button id="dropdownActionButton" data-dropdown-toggle="dropdownAction" class="inline-flex items-center text-gray-500 bg-white border border-gray-300 focus:outline-none hover:bg-gray-100 focus:ring-4 focus:ring-gray-200 font-medium rounded-lg text-sm px-3 py-1.5 dark:bg-gray-800 dark:text-gray-400 dark:border-gray-600 dark:hover:bg-gray-700 dark:hover:border-gray-600 dark:focus:ring-gray-700" type="button">
                <span class="sr-only">Action button</span>
                Action
                <svg class="w-2.5 h-2.5 ms-2.5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 10 6">
                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 4 4 4-4"/>
                </svg>
            </button>
            <div id="dropdownAction" class="mt-2 z-10 hidden bg-white divide-y divide-gray-100 rounded-lg shadow w-44 dark:bg-gray-700 dark:divide-gray-600">
                <ul class="py-1 text-sm text-gray-700 dark:text-gray-200" aria-labelledby="dropdownActionButton">
                    <li><a href="#" class="block px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white">Start</a></li>
                    <li><a href="#" class="block px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white">Stop</a></li>
                    <li><a href="#" class="block px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white">Update proxy</a></li>
                    <li><a href="#" class="block px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white">Update agent</a></li>
                </ul>
                <div class="py-1">
                    <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 dark:hover:bg-gray-600 dark:text-gray-200 dark:hover:text-white">Delete</a>
                </div>
            </div>
        </div>
        <label for="table-search" class="sr-only">Search</label>
        <div class="relative">
            <div class="absolute inset-y-0 rtl:inset-r-0 start-0 flex items-center ps-3 pointer-events-none">
                <svg class="w-4 h-4 text-gray-500 dark:text-gray-400" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 20">
                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m19 19-4-4m0-7A7 7 0 1 1 1 8a7 7 0 0 1 14 0Z"/>
                </svg>
            </div>
            <input type="text" id="table-search-users" class="block p-2 ps-10 text-sm text-gray-900 border border-gray-300 rounded-lg w-80 bg-gray-50 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" placeholder="Search for accounts">
        </div>
    </div>--}}
    <div class="relative overflow-x-auto shadow-md sm:rounded-lg">
        <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
            <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
            <tr>
                {{-- todo --}}
                {{--<th scope="col" class="p-4">
                    <div class="flex items-center">
                        <input id="checkbox-all-search" type="checkbox" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 dark:focus:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                        <label for="checkbox-all-search" class="sr-only">checkbox</label>
                    </div>
                </th>--}}
                <th scope="col" class="px-6 py-3">Name</th>
                <th scope="col" class="px-6 py-3">Group</th>
                <th scope="col" class="px-6 py-3">Agent</th>
                <th scope="col" class="px-6 py-3">Status</th>
                <th scope="col" class="px-6 py-3">Action</th>
            </tr>
            </thead>
            <tbody>
            @foreach ($accounts->items() as $account)
            <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                {{-- todo --}}
                {{--<td class="w-4 p-4">
                    <div class="flex items-center">
                        <input id="checkbox-table-search-1" type="checkbox" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 dark:focus:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                        <label for="checkbox-table-search-1" class="sr-only">checkbox</label>
                    </div>
                </td>--}}
                <th scope="row" class="px-4 py-3 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                    <a href="{{ route('account.show', $account->id) }}">{{ $account->email }}</a>
                </th>
                <td class="px-4 py-3 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                    @if($account->account_group)
                        <a href="{{ route('account.group.show', $account->account_group_id) }}">{{ $account->account_group->name }}</a>
                    @else
                        <span>-</span>
                    @endif
                </td>
                <td class="px-4 py-3 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                    @if($account->agent)
                        <a href="{{ route('agent.show', $account->agent_id) }}">{{ $account->agent->name }}</a>
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
                    if (auth()->user()->subscription->name == 'Basic' && $account->perm_banned_at) {
                                        $status = 'Banned';
                                    }
                                    else if ($account->temp_banned_at) {
                                        $status = 'Banned (Temporary)';
                                    }
                                    else if ($account->perm_banned_at) {
                                        $status = 'Banned (Permanent)';
                                    }
                @endphp
                    <div class="flex items-center">{!! $icon !!} {{ $status }}</div>
                </td>
                <td class="px-4 py-3">
                    @if($account->status == 'Stopped' || $account->status == 'Stopping' || $account->status == 'Banned')
                        <form method="post" action="{{ route('account.start', $account->id) }}">
                            @csrf
                            <button type="submit" class="font-medium text-blue-600 dark:text-blue-500 hover:underline">Start</button>
                        </form>
                    @elseif($account->status == 'Running' || $account->status == 'Starting')
                        <form method="post" action="{{ route('account.stop', $account->id) }}">
                            @csrf
                            <button type="submit" class="font-medium text-blue-600 dark:text-blue-500 hover:underline">Stop</button>
                        </form>
                    @endif
                </td>
            </tr>
            @endforeach
            </tbody>
        </table>
    </div>

    <nav aria-label="Page navigation example" class="mt-4">
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
</x-v1.layout>
<script>
    const dropdownButton = document.getElementById('dropdownActionButton');

    if (dropdownButton) {
        const dropdownMenu = document.getElementById('dropdownAction');
        dropdownButton.addEventListener('click', function() {
            if (dropdownMenu.classList.contains('hidden')) {
                dropdownMenu.classList.remove('hidden');
                dropdownMenu.classList.add('absolute');
            } else {
                dropdownMenu.classList.add('hidden');
                dropdownMenu.classList.remove('absolute');
            }
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
