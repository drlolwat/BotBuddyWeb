<x-v1.layout page="Dashboard">
    <dl class="grid grid-cols-1 gap-5 sm:grid-cols-3">
        <div class="overflow-hidden rounded-lg bg-white border border-gray-200 px-4 py-5 dark:bg-gray-800 dark:border-gray-700">
            <dt class="mb-2 text-sm font-medium tracking-tight text-gray-900 dark:text-white">Accounts online</dt>
            <dd class="text-3xl font-semibold tracking-tight text-gray-700 dark:text-gray-400">{{ $online }}</dd>
        </div>
        <div class="overflow-hidden rounded-lg bg-white border border-gray-200 px-4 py-5 dark:bg-gray-800 dark:border-gray-700">
            <dt class="mb-2 text-sm font-medium tracking-tight text-gray-900 dark:text-white">Accounts offline</dt>
            <dd class="text-3xl font-semibold tracking-tight text-gray-700 dark:text-gray-400">{{ $offline }}</dd>
        </div>
        <div class="overflow-hidden rounded-lg bg-white border border-gray-200 px-4 py-5 dark:bg-gray-800 dark:border-gray-700">
            <dt class="mb-2 text-sm font-medium tracking-tight text-gray-900 dark:text-white">Accounts banned past 24h</dt>
            <dd class="text-3xl font-semibold tracking-tight text-gray-700 dark:text-gray-400">{{ $bannedLast24h }}</dd>
        </div>
    </dl>
    <section class="bg-gray-50 dark:bg-gray-900 mt-4">
        <div class="mx-auto">
            <div class="bg-white dark:bg-gray-800 relative shadow-md sm:rounded-lg">
                <div class="flex flex-col md:flex-row items-center justify-between space-y-3 md:space-y-0 md:space-x-4 p-4">
                    <div class="w-full md:w-1/2">
                        <div class="font-bold text-gray-900 dark:text-white">Online accounts</div>
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
                            <th scope="col" class="px-4 py-3">GP</th>
                            <th scope="col" class="px-4 py-3">QP</th>
                            <th scope="col" class="px-4 py-3">TTL</th>
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
                            <th scope="row" class="px-4 py-3 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                <a href="{{ route('account.show', $account->id) }}">{{ $account->email }}</a>
                            </th>
                                <td class="px-4 py-3 text-gray-900 whitespace-nowrap dark:text-white">
                                    @if($account->stats?->gp)
                                        <img class="inline mb-[2px]" src="{{ url('/gp.png') }}" /> {{ $account->stats->gp_formatted }}
                                    @else - @endif
                                </td>
                                <td class="px-4 py-3 text-gray-900 whitespace-nowrap dark:text-white">
                                    @if($account->stats?->qp)
                                        <img class="inline mb-[2px]" src="{{ url('/qp.png') }}" /> {{ $account->stats->qp }}
                                    @else - @endif
                                </td>
                                <td class="px-4 py-3 text-gray-900 whitespace-nowrap dark:text-white">
                                    @if($account->stats?->ttl)
                                        <img class="inline mb-[2px]" src="{{ url('/ttl.webp') }}" /> {{ $account->stats->ttl }}
                                    @else - @endif
                                </td>
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
                                    if (auth()->user()->subscription && (auth()->user()->subscription->name == 'Basic' && $account->perm_banned_at)) {
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
                            <td class="px-4 py-3 flex items-center justify-end">
                                <button id="account-{{ $account->id }}-dropdown-button" data-dropdown-toggle="account-{{ $account->id }}-dropdown" class="inline-flex items-center p-0.5 text-sm font-medium text-center text-gray-500 hover:text-gray-800 rounded-lg focus:outline-none dark:text-gray-400 dark:hover:text-gray-100" type="button">
                                    <svg class="w-5 h-5" aria-hidden="true" fill="currentColor" viewbox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                        <path d="M6 10a2 2 0 11-4 0 2 2 0 014 0zM12 10a2 2 0 11-4 0 2 2 0 014 0zM16 12a2 2 0 100-4 2 2 0 000 4z" />
                                    </svg>
                                </button>
                                <div id="account-{{ $account->id }}-dropdown" class="mt-[7.75rem] mr-[-1rem] hidden z-10 w-44 bg-white rounded divide-y divide-gray-100 shadow dark:bg-gray-700 dark:divide-gray-600">
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
                                <li><a href="?accounts=1" class="flex items-center justify-center px-3 h-8 leading-tight text-gray-500 bg-white border border-gray-300 hover:bg-gray-100 hover:text-gray-700 dark:bg-gray-800 dark:border-gray-700 dark:text-gray-400 dark:hover:bg-gray-700 dark:hover:text-white">1</a></li>
                                @if($pages[0] != 2)
                                    <li><span class="flex items-center justify-center px-3 h-8 leading-tight text-gray-500 bg-white border border-gray-300 dark:bg-gray-800 dark:border-gray-700 dark:text-gray-400">...</span></li>
                                @endif
                            @endif

                            @foreach ($pages as $page)
                                @if ($page == $currentPage)
                                    <li><a href="#" aria-current="page" class="flex items-center justify-center px-3 h-8 text-blue-600 border border-gray-300 bg-blue-50 hover:bg-blue-100 hover:text-blue-700 dark:border-gray-700 dark:bg-gray-700 dark:text-white">{{ $page }}</a></li>
                                @else
                                    <li><a href="?accounts={{ $page }}" class="flex items-center justify-center px-3 h-8 leading-tight text-gray-500 bg-white border border-gray-300 hover:bg-gray-100 hover:text-gray-700 dark:bg-gray-800 dark:border-gray-700 dark:text-gray-400 dark:hover:bg-gray-700 dark:hover:text-white">{{ $page }}</a></li>
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
