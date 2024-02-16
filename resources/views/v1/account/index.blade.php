<x-v1.layout page="Accounts">
    <section>
        <div class="mb-4 text-xl font-bold text-gray-900 dark:text-white">Account Management</div>
    </section>
    <div class="flex items-center justify-between flex-column flex-wrap md:flex-row space-y-4 md:space-y-0 pb-4 bg-white dark:bg-gray-900">
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
                    <li><form id="bulk_start" method="post" action="{{ route('account.bulkAction') }}">@csrf<input type="hidden" name="action" value="start" /><button class="w-full text-left block px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white">Start</button></form></li>
                    <li><form id="bulk_stop" method="post" action="{{ route('account.bulkAction') }}">@csrf<input type="hidden" name="action" value="stop" /><button class="w-full text-left block px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white">Stop</button></form></li>
                    <li><button data-modal-target="default-modal" data-modal-toggle="default-modal" class="w-full text-left block px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white">Queue start</button></li>
{{--                    <li><a href="#" class="block px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white">Update proxy</a></li>--}}
{{--                    <li><a href="#" class="block px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white">Update agent</a></li>--}}
                </ul>
{{--                <div class="py-1">--}}
{{--                    <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 dark:hover:bg-gray-600 dark:text-gray-200 dark:hover:text-white">Delete</a>--}}
{{--                </div>--}}
            </div>
        </div>
{{--        <label for="table-search" class="sr-only">Search</label>--}}
{{--        <div class="relative">--}}
{{--            <div class="absolute inset-y-0 rtl:inset-r-0 start-0 flex items-center ps-3 pointer-events-none">--}}
{{--                <svg class="w-4 h-4 text-gray-500 dark:text-gray-400" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 20">--}}
{{--                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m19 19-4-4m0-7A7 7 0 1 1 1 8a7 7 0 0 1 14 0Z"/>--}}
{{--                </svg>--}}
{{--            </div>--}}
{{--            <input type="text" id="table-search-users" class="block p-2 ps-10 text-sm text-gray-900 border border-gray-300 rounded-lg w-80 bg-gray-50 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" placeholder="Search for accounts">--}}
{{--        </div>--}}
    </div>
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
                            <th scope="col" class="p-4">
                                <div class="flex items-center">
                                    <input id="checkbox-all-search" type="checkbox" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 dark:focus:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                                    <label for="checkbox-all-search" class="sr-only">checkbox</label>
                                </div>
                            </th>
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
                                <td class="w-4 p-4">
                                    <div class="flex items-center">
                                        <input form="bulk" name="accounts[{{ $account->id }}]" id="checkbox-table-search-{{ $account->id }}" type="checkbox" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 dark:focus:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                                        <label for="checkbox-table-search-{{ $account->id }}" class="sr-only">checkbox</label>
                                    </div>
                                </td>
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
                                        if ($account->status == 'Starting' || $account->status == 'Stopping' || $account->status == 'Queued') {
                                            $icon = '<div class="h-2.5 w-2.5 rounded-full bg-yellow-500 me-2"></div>';
                                        }
                                        if ($account->status == 'Running') {
                                            $icon = '<div class="h-2.5 w-2.5 rounded-full bg-green-500 me-2"></div>';
                                        }
                                        $status = $account->status;
                                        if (auth()->user()->subscription->name == 'Basic') {
                                            $status = 'Banned';
                                        }
                                        else if ($account->temp_banned_at) {
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
                                            @if($account->status == 'Running' || $account->status == 'Starting' || $account->status == 'Completed')
                                                <li>
                                                    <form method="post" action="{{ route('account.stop', $account->id) }}">
                                                        @csrf
                                                        <button href="#" class="w-full text-left block py-2 px-4 text-sm text-gray-700 hover:bg-gray-100 dark:hover:bg-gray-600 dark:text-gray-200 dark:hover:text-white">Stop</button>
                                                    </form>
                                                </li>
                                            @elseif($account->status == 'Stopped' || $account->status == 'Stopping' || $account->status == 'Banned' || $account->status == 'NoScript')
                                                <li>
                                                    <form method="post" action="{{ route('account.start', $account->id) }}">
                                                        @csrf
                                                        <button href="#" class="w-full text-left block py-2 px-4 text-sm text-gray-700 hover:bg-gray-100 dark:hover:bg-gray-600 dark:text-gray-200 dark:hover:text-white">Start</button>
                                                    </form>
                                                </li>
                                            @elseif($account->status == 'Queued')
                                                <li>
                                                    <form method="post" action="{{ route('account.dequeue', $account->id) }}">
                                                        @csrf
                                                        <button href="#" class="w-full text-left block py-2 px-4 text-sm text-gray-700 hover:bg-gray-100 dark:hover:bg-gray-600 dark:text-gray-200 dark:hover:text-white">Cancel</button>
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

    <div id="default-modal" tabindex="-1" aria-hidden="true" class="hidden bg-[#00000075] overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 h-full max-h-full">
        <div class="relative p-4 w-full max-w-2xl max-h-full">
            <div class="relative bg-white rounded-lg shadow dark:bg-gray-700">
                <div class="flex items-center justify-between p-4 md:p-5 border-b rounded-t dark:border-gray-600">
                    <h3 class="text-xl font-semibold text-gray-900 dark:text-white">
                        Queue start
                    </h3>
                    <button type="button" class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ms-auto inline-flex justify-center items-center dark:hover:bg-gray-600 dark:hover:text-white" data-modal-hide="default-modal">
                        <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 14">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6"/>
                        </svg>
                        <span class="sr-only">Close modal</span>
                    </button>
                </div>
                <form id="bulk_queue" method="post" action="{{ route('account.bulkAction') }}">
                    @csrf
                    <input type="hidden" name="action" value="queue" />
                    <div class="p-4 md:p-5 space-y-4">
                        <p class="text-base leading-relaxed text-gray-500 dark:text-gray-400">
                            Enter the duration to wait before starting each account.
                        </p>
                        <div class="flex gap-2">
                            <input class="bg-gray-50 border border-gray-200 text-gray-900 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 w-full md:w-[75px] text-center mb-4 p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-300 dark:text-white dark:focus:ring-primary-400 dark:focus:border-primary-400 [appearance:textfield] [&::-webkit-outer-spin-button]:appearance-none [&::-webkit-inner-spin-button]:appearance-none" id="minutes" type="number" name="minutes" value="2" min="1" max="120">
                            <div class="text-gray-900 text-sm rounded-lg w-full md:w-[400px] mb-4 p-2.5 dark:text-white">Minutes</div>
                        </div>
                    </div>
                    <div class="flex items-center p-4 md:p-5 border-t border-gray-200 rounded-b dark:border-gray-600">
                        <button type="submit" data-modal-hide="default-modal" class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">Submit</button>
                        <button data-modal-hide="default-modal" type="button" class="ms-3 text-gray-500 bg-white hover:bg-gray-100 focus:ring-4 focus:outline-none focus:ring-blue-300 rounded-lg border border-gray-200 text-sm font-medium px-5 py-2.5 hover:text-gray-900 focus:z-10 dark:bg-gray-700 dark:text-gray-300 dark:border-gray-500 dark:hover:text-white dark:hover:bg-gray-600 dark:focus:ring-gray-600">Cancel</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        const toggleButton = document.querySelector('[data-modal-toggle="default-modal"]');
        const modal = document.getElementById('default-modal');

        function toggleModal() {
            modal.classList.toggle('hidden');
            modal.classList.toggle('flex');
            modal.setAttribute('aria-hidden', String(modal.classList.contains('hidden')));
        }

        toggleButton.addEventListener('click', toggleModal);

        document.querySelectorAll('[data-modal-hide="default-modal"]').forEach(closeButton => {
            closeButton.addEventListener('click', toggleModal);
        });

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

        const checkboxes = document.querySelectorAll('input[type="checkbox"][name^="accounts["]');
        checkboxes.forEach(function(checkbox) {
            checkbox.addEventListener('change', function() {
                const formIds = ['bulk_start', 'bulk_stop', 'bulk_queue'];

                formIds.forEach(function(formId) {
                    const form = document.getElementById(formId);
                    if (!form) {
                        return;
                    }
                    const hiddenInputId = formId + '-' + checkbox.name.replace(/[\[\]]/g, '');

                    if (checkbox.checked) {
                        let hiddenInput = document.createElement('input');
                        hiddenInput.type = 'hidden';
                        hiddenInput.name = checkbox.name;
                        hiddenInput.value = checkbox.value;
                        hiddenInput.id = hiddenInputId;
                        form.appendChild(hiddenInput);
                    } else {
                        const hiddenInput = document.getElementById(hiddenInputId);
                        if (hiddenInput) {
                            form.removeChild(hiddenInput);
                        }
                    }
                });
            });
        });

        const checkboxAll = document.getElementById('checkbox-all-search');
        checkboxAll.addEventListener('change', function() {
            const checkboxes = document.querySelectorAll('input[type="checkbox"][name^="accounts["]');
            const formIds = ['bulk_start', 'bulk_stop', 'bulk_queue'];

            checkboxes.forEach(checkbox => {
                formIds.forEach(formId => {
                    const form = document.getElementById(formId);
                    if (!form) {
                        return;
                    }
                    const hiddenInputId = formId + '-' + checkbox.name.replace(/[\[\]]/g, '');

                    if (checkboxAll.checked) {
                        let hiddenInput = document.createElement('input');
                        hiddenInput.type = 'hidden';
                        hiddenInput.name = checkbox.name;
                        hiddenInput.value = checkbox.value;
                        hiddenInput.id = hiddenInputId;
                        form.appendChild(hiddenInput);
                    } else {
                        const hiddenInput = document.getElementById(hiddenInputId);
                        if (hiddenInput) {
                            form.removeChild(hiddenInput);
                        }
                    }
                });
            });
        });

    </script>
</x-v1.layout>
