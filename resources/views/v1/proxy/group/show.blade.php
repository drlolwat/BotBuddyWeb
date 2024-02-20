<x-v1.layout page="Proxy {{ $group->name }}">
    <section class="bg-gray-50 dark:bg-gray-900 mb-4">
        <div class="mx-auto">
            <div class="bg-white dark:bg-gray-800 relative shadow-md sm:rounded-lg">
                <div class="flex flex-col md:flex-row items-center justify-between space-y-3 md:space-y-0 md:space-x-4 p-4">
                    <div class="w-full">
                        <h2 class="mb-4 text-xl font-bold text-gray-900 dark:text-white">Update proxy group</h2>
                        <form method="post" action="{{ route('proxy.group.update', $group->id) }}">
                            @csrf
                            <input type="hidden" name="_method" value="PUT">
                            <div class="grid gap-4 mb-4 sm:grid-cols-2 sm:gap-6 sm:mb-5">
                                <div class="sm:col-span-2">
                                    <label for="name" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Name</label>
                                    <input type="text" name="name" id="name" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500" value="{{ $group->name }}" placeholder="Type the group name">
                                </div>
                            </div>
                            <div class="flex items-center space-x-4">
                                <button type="submit" class="text-white bg-primary-700 hover:bg-primary-800 focus:ring-4 focus:outline-none focus:ring-primary-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-primary-600 dark:hover:bg-primary-700 dark:focus:ring-primary-800">
                                    Update
                                </button>
                                <button form="delete" type="submit" class="text-white bg-red-700 hover:bg-red-800 focus:ring-4 focus:outline-none focus:ring-red-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-red-600 dark:hover:bg-red-700 dark:focus:ring-red-800">
                                    Delete
                                </button>
                            </div>
                        </form>
                        <form id="delete" method="post" action="{{ route('proxy.group.destroy', $group->id) }}">
                            @csrf
                            <input type="hidden" name="_method" value="DELETE">
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <div class="max-w-2xl px-4 mx-auto">

    </div>
    <section class="bg-gray-50 dark:bg-gray-900">
        <div class="mx-auto">
            <div class="bg-white dark:bg-gray-800 relative shadow-md sm:rounded-lg">
                <div class="flex flex-col md:flex-row items-center justify-between space-y-3 md:space-y-0 md:space-x-4 p-4">
                    <div class="w-full md:w-1/2">
                        <div class="font-bold text-gray-900 dark:text-white">Proxies in {{ $group->name }}</div>
                    </div>
                    {{--                    <div class="w-full md:w-auto flex flex-col md:flex-row space-y-2 md:space-y-0 items-stretch md:items-center justify-end md:space-x-3 flex-shrink-0">--}}
                    {{--                        <a href="{{ route('account.create') }}" class="flex items-center justify-center text-white bg-primary-700 hover:bg-primary-800 focus:ring-4 focus:ring-primary-300 font-medium rounded-lg text-sm px-4 py-2 dark:bg-primary-600 dark:hover:bg-primary-700 focus:outline-none dark:focus:ring-primary-800">--}}
                    {{--                            <svg class="h-3.5 w-3.5 mr-2" fill="currentColor" viewbox="0 0 20 20" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">--}}
                    {{--                                <path clip-rule="evenodd" fill-rule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" />--}}
                    {{--                            </svg>--}}
                    {{--                            Add accounts--}}
                    {{--                        </a>--}}
                    {{--                    </div>--}}
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
                        <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                        <tr>
                            <th scope="col" class="px-4 py-3">Proxy</th>
                            <th scope="col" class="px-4 py-3">Auth</th>
                            <th scope="col" class="px-4 py-3">Accounts</th>
                            <th scope="col" class="px-4 py-3">Group</th>
                            <th scope="col" class="px-4 py-3">
                                <span class="sr-only">Actions</span>
                            </th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($proxies as $proxy)
                            <tr class="border-b dark:border-gray-700">
                                <th scope="row" class="px-4 py-3 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                    <a href="{{ route('proxy.show', $proxy->id) }}">{{ $proxy->host }}:{{ $proxy->port }} {{ $proxy->username }}</a>
                                </th>
                                <td class="px-4 py-3">{{ $proxy->username && $proxy->password ? $proxy->username.":".$proxy->password : "-" }}</td>
                                <td class="px-4 py-3">{{ $proxy->accounts_count }}</td>
                                <td class="px-4 py-3 text-gray-900 whitespace-nowrap dark:text-white">
                                    @if($proxy->proxy_group)
                                        <a class="font-medium" href="{{ route('proxy.group.show', $proxy->proxy_group_id) }}">{{ $proxy->proxy_group->name }}</a>
                                    @else
                                        -
                                    @endif
                                </td>
                                <td class="px-4 py-3 flex items-center justify-end">
                                    <button id="proxy-{{ $proxy->id }}-dropdown-button" data-dropdown-toggle="proxy-{{ $proxy->id }}-dropdown" class="inline-flex items-center p-0.5 text-sm font-medium text-center text-gray-500 hover:text-gray-800 rounded-lg focus:outline-none dark:text-gray-400 dark:hover:text-gray-100" type="button">
                                        <svg class="w-5 h-5" aria-hidden="true" fill="currentColor" viewbox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                            <path d="M6 10a2 2 0 11-4 0 2 2 0 014 0zM12 10a2 2 0 11-4 0 2 2 0 014 0zM16 12a2 2 0 100-4 2 2 0 000 4z" />
                                        </svg>
                                    </button>
                                    <div id="proxy-{{ $proxy->id }}-dropdown" class="mt-[8.5rem] mr-[-1rem] hidden z-10 w-44 bg-white rounded divide-y divide-gray-100 shadow dark:bg-gray-700 dark:divide-gray-600">
                                        <ul class="py-1 text-sm text-gray-700 dark:text-gray-200" aria-labelledby="proxy-{{ $proxy->id }}-dropdown-button">
                                            <li>
                                                <a href="{{ route('proxy.show', $proxy->id) }}" class="block py-2 px-4 hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white">Edit</a>
                                            </li>
                                        </ul>
                                        <div class="py-1">
                                            <form method="post" action="{{ route('proxy.destroy', $proxy->id) }}">
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
                                {{ ($proxies->currentPage() - 1) * $proxies->perPage() + 1 }}-{{ min($proxies->total(), $proxies->currentPage() * $proxies->perPage()) }}
                            </span>
                            of
                            <span class="font-semibold text-gray-900 dark:text-white">{{ $proxies->total() }}</span>
                        </span>
                    </div>
                    <nav>
                        <ul class="inline-flex -space-x-px text-sm">
                            @php
                                $currentPage = $proxies->currentPage();
                                $lastPage = $proxies->lastPage();
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
</x-v1.layout>
