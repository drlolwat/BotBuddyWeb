<x-v1.layout page="{{ $script->name }}">
    <div class="max-w-2xl px-4 mx-auto">
        <h2 class="mb-4 text-xl font-bold text-gray-900 dark:text-white">Update script</h2>
        <form method="post" action="{{ route('script.update', $script->id) }}">
            @csrf
            <input type="hidden" name="_method" value="PUT">
            <div class="grid gap-4 mb-4 sm:grid-cols-2 sm:gap-6 sm:mb-5">
                <div class="sm:col-span-2">
                    <label for="name" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Name</label>
                    <input type="text" name="name" id="name" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500" value="{{ $script->name }}" placeholder="Type the script name">
                </div>
                <div class="sm:col-span-2">
                    <label for="script" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Script</label>
                    <input type="text" name="script" id="script" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500" value="{{ $script->script }}" placeholder="Type the name of the script on the client">
                </div>
            </div>
            <div class="flex items-center space-x-4">
                <button type="submit" class="text-white bg-primary-700 hover:bg-primary-800 focus:ring-4 focus:outline-none focus:ring-primary-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-primary-600 dark:hover:bg-primary-700 dark:focus:ring-primary-800">
                    Update
                </button>
                <button form="delete_script" type="submit" class="text-white bg-red-700 hover:bg-red-800 focus:ring-4 focus:outline-none focus:ring-red-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-red-600 dark:hover:bg-red-700 dark:focus:ring-red-800">
                    Delete
                </button>
            </div>
        </form>
        <form id="delete_script" method="post" action="{{ route('script.destroy', $script->id) }}">
            @csrf
            <input type="hidden" name="_method" value="DELETE">
        </form>
    </div>
    <div class="flex items-center justify-between flex-column flex-wrap md:flex-row space-y-4 md:space-y-0 pb-4">
        <div>
            <button id="dropdownActionButton" data-dropdown-toggle="dropdownAction" class="inline-flex items-center text-gray-500 bg-white border border-gray-300 focus:outline-none hover:bg-gray-100 focus:ring-4 focus:ring-gray-200 font-medium rounded-lg text-sm px-3 py-1.5 dark:bg-gray-800 dark:text-gray-400 dark:border-gray-600 dark:hover:bg-gray-700 dark:hover:border-gray-600 dark:focus:ring-gray-700" type="button">
                <span class="sr-only">Action button</span>
                Action
                <svg class="w-2.5 h-2.5 ms-2.5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 10 6">
                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 4 4 4-4"></path>
                </svg>
            </button>
            <div id="dropdownAction" class="mt-2 z-10 bg-white divide-y divide-gray-100 rounded-lg shadow w-44 dark:bg-gray-700 dark:divide-gray-600 hidden">
                <ul class="py-1 text-sm text-gray-700 dark:text-gray-200" aria-labelledby="dropdownActionButton">
                    <li><form id="delete" method="post" action="{{ route('script.trigger.bulkAction') }}">@csrf<input type="hidden" name="script_id" value="{{ $script->id }}" /><input type="hidden" name="action" value="delete"><button class="w-full text-left block px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white">Delete</button></form></li>
                </ul>
            </div>
        </div>
    </div>
    <section>
        <div class="mx-auto">
            <div class="bg-white dark:bg-gray-800 relative shadow-md sm:rounded-lg">
                <div class="flex flex-col md:flex-row items-center justify-between space-y-3 md:space-y-0 md:space-x-4 p-4">
                    <div class="w-full md:w-1/2">
                        <div class="font-bold text-gray-900 dark:text-white">Script triggers</div>
                    </div>
                    <div class="w-full md:w-auto flex flex-col md:flex-row space-y-2 md:space-y-0 items-stretch md:items-center justify-end md:space-x-3 flex-shrink-0">
                        <a href="{{ route('script.trigger.create', $script->id) }}" class="flex items-center justify-center text-white bg-primary-700 hover:bg-primary-800 focus:ring-4 focus:ring-primary-300 font-medium rounded-lg text-sm px-4 py-2 dark:bg-primary-600 dark:hover:bg-primary-700 focus:outline-none dark:focus:ring-primary-800">
                            <svg class="h-3.5 w-3.5 mr-2" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                                <path clip-rule="evenodd" fill-rule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z"></path>
                            </svg>
                            Add script trigger
                        </a>
                    </div>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
                        <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                        <tr>
                            <th scope="col" class="p-4">
                                <div class="flex items-center">
                                    <input id="checkbox-all-search" type="checkbox" class="w-4 h-4 text-blue-600 bg-gray-100 rounded focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 dark:focus:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                                    <label for="checkbox-all-search" class="sr-only">checkbox</label>
                                </div>
                            </th>
                            <th scope="col" class="px-4 py-3">Name</th>
                            <th scope="col" class="px-4 py-3">Log message</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach ($triggers as $trigger)
                            <tr class="border-b dark:border-gray-700">
                                <td class="w-4 p-4">
                                    <div class="flex items-center">
                                        <input form="bulk" name="triggers[{{ $trigger->id }}]" id="checkbox-table-search-{{ $trigger->id }}" type="checkbox" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 dark:focus:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                                        <label for="checkbox-table-search-{{ $trigger->id }}" class="sr-only">checkbox</label>
                                    </div>
                                </td>
                                <th scope="row" class="px-4 py-3 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                    <a href="{{ route('script.trigger.show', $trigger->id) }}">{{ $trigger->name }}</a>
                                </th>
                                <td class="px-4 py-3">{{ $trigger->message }}</td>
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
                                {{ ($triggers->currentPage() - 1) * $triggers->perPage() + 1 }}-{{ min($triggers->total(), $triggers->currentPage() * $triggers->perPage()) }}
                            </span>
                            of
                            <span class="font-semibold text-gray-900 dark:text-white">{{ $triggers->total() }}</span>
                        </span>
                    </div>
                    <nav>
                        <ul class="inline-flex -space-x-px text-sm">
                            @php
                                $currentPage = $triggers->currentPage();
                                $lastPage = $triggers->lastPage();
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
        //

        const masterCheckbox = document.getElementById('checkbox-all-search');
        if (masterCheckbox) {
            const rowCheckboxes = document.querySelectorAll('input[type="checkbox"][name^="triggers["]');
            masterCheckbox.addEventListener('change', function() {
                rowCheckboxes.forEach(function(checkbox) {
                    if (checkbox !== masterCheckbox) {
                        checkbox.checked = masterCheckbox.checked;
                    }
                });
            });
        }

        const checkboxes = document.querySelectorAll('input[type="checkbox"][name^="triggers["]');
        checkboxes.forEach(function(checkbox) {
            checkbox.addEventListener('change', function() {
                const formIds = ['bulk_start', 'bulk_stop', 'bulk_queue', 'bulk_export', 'delete', 'change_proxy', 'remove_proxy'];

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
            const checkboxes = document.querySelectorAll('input[type="checkbox"][name^="triggers["]');
            const formIds = ['bulk_start', 'bulk_stop', 'bulk_queue', 'bulk_export', 'delete', 'change_proxy', 'remove_proxy'];

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
