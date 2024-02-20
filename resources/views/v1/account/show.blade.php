<x-v1.layout page="{{ $account->email }}">
    <div class="grid lg:grid-cols-[2fr,1fr] gap-4">
        <section class="bg-gray-50 dark:bg-gray-900 mb-4">
            <div class="mx-auto">
                <div class="bg-white dark:bg-gray-800 relative shadow-md sm:rounded-lg">
                    <div class="flex flex-col md:flex-row items-center justify-between space-y-3 md:space-y-0 md:space-x-4 p-4">
                        <div class="w-full">
                            <h2 class="mb-4 text-xl font-bold text-gray-900 dark:text-white">Update account</h2>
                            <form method="post" action="{{ route('account.update', $account->id) }}">
                                @csrf
                                <input type="hidden" name="_method" value="PUT">
                                <div class="grid gap-4 mb-4 sm:grid-cols-2 sm:gap-6 sm:mb-5">
                                    <div>
                                        <label for="email" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">User/Email</label>
                                        <input value="{{ $account->email }}" type="text" name="email" id="email" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500" placeholder="Type the account login user/email" required>
                                    </div>
                                    <div>
                                        <label for="password" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Password</label>
                                        <input value="{{ $account->password }}" type="text" name="password" id="password" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500" placeholder="Type the account password" required>
                                    </div>
                                    <div class="sm:col-span-2">
                                        <label for="password_2fa" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">OTP Code (optional)</label>
                                        <input value="{{ $account->password_2fa }}" type="text" name="password_2fa" id="password_2fa" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500" placeholder="Type the account OTP (optional)">
                                    </div>
                                    <div class="sm:col-span-2">
                                        <label for="account_group_id" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Account Group</label>
                                        <select name="account_group_id" id="account_group_id" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500">
                                            <option value="0">None</option>
                                            @foreach(auth()->user()->account_groups as $group)
                                                <option value="{{ $group->id }}" @if($account->account_group_id == $group->id) selected @endif >{{ $group->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="sm:col-span-2">
                                        <label for="agent_id" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Agent</label>
                                        <select name="agent_id" id="agent_id" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500">
                                            <option value="0">None</option>
                                            @foreach(auth()->user()->agents as $agent)
                                                <option value="{{ $agent->id }}" @if($account->agent_id == $agent->id) selected @endif >{{ $agent->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="sm:col-span-2">
                                        <label for="proxy_id" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Proxy</label>
                                        <select name="proxy_id" id="proxy_id" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500">
                                            <option value="0">None</option>
                                            @foreach($proxies as $proxy)
                                                <option value="{{ $proxy->id }}" @if($account->proxy_id == $proxy->id) selected @endif >{{ $proxy->host }}:{{ $proxy->port }} {{ $proxy->username }} (accounts: {{ $proxy->accounts_count }})</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div>
                                        <label for="script_id" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Script</label>
                                        <select name="script_id" id="script_id" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500">
                                            <option value="0">None</option>
                                            @foreach(auth()->user()->scripts as $script)
                                                <option value="{{ $script->id }}" @if($account->script_id == $script->id) selected @endif >{{ $script->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div>
                                        <label for="script_params" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Script params</label>
                                        <input type="text" name="script_params" id="script_params" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500" value="{{ $account->script_params }}" placeholder="Enter script params (optional)">
                                    </div>
                                    <div>
                                        <label for="world" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">World</label>
                                        <input type="text" name="world" id="world" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500" value="{{ $account->world }}" placeholder="Type the world (world id, 'f2p' or 'members')">
                                    </div>
                                    <div>
                                        <label for="fps" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">FPS</label>
                                        <input type="number" name="fps" id="fps" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500" value="{{ $account->fps }}" placeholder="Type the group name">
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
                            <form id="delete" method="post" action="{{ route('account.destroy', $account->id) }}">
                                @csrf
                                <input type="hidden" name="_method" value="DELETE">
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        @if($account->stats)
        <section class="bg-gray-50 dark:bg-gray-900 mb-4">
            <div class="mx-auto">
                <div class="bg-white dark:bg-gray-800 relative shadow-md sm:rounded-lg">
                    <div class="flex flex-col md:flex-row items-center justify-between space-y-3 md:space-y-0 md:space-x-4 p-4">
                        <div class="w-full">
                            <h2 class="mb-4 text-xl font-bold text-gray-900 dark:text-white">Stats</h2>
                            <div class="relative overflow-x-auto sm:rounded-lg">
                                <table class="mb-2 w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
                                    <thead class="text-xs text-gray-700 uppercase dark:text-gray-400">
                                    <tr>
                                        <th scope="col" class="px-6 py-3">
                                            Gold Pieces
                                        </th>
                                        <th scope="col" class="px-6 py-3">
                                            Quest Points
                                        </th>
                                        <th scope="col" class="px-6 py-3">
                                            Total Level
                                        </th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <tr class="border-t border-gray-200 dark:border-gray-700">
                                        <td class="px-6 py-4">
                                            <img class="inline mb-[2px]" src="{{ url('/gp.png') }}" /> {{ $account->stats->gp_formatted }}
                                        </td>
                                        <td class="px-6 py-4">
                                            <img class="inline mb-[2px]" src="{{ url('/qp.png') }}" /> {{ $account->stats->qp }}
                                        </td>
                                        <td class="px-6 py-4">
                                            <img class="inline mb-[2px]" src="{{ url('/ttl.webp') }}" /> {{ $account->stats->ttl }}
                                        </td>
                                    </tr>
                                    </tbody>
                                </table>
                            </div>
                            <h2 class="mb-4 text-xl font-bold text-gray-900 dark:text-white">Skills</h2>
                            <div class="relative overflow-x-auto sm:rounded-lg">
                                <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
                                    <thead class="text-xs text-gray-700 uppercase dark:text-gray-400">
                                    <tr>
                                        <th scope="col" class="px-6 py-3">
                                            Skill
                                        </th>
                                        <th scope="col" class="px-6 py-3">
                                            Level
                                        </th>
                                        <th scope="col" class="px-6 py-3">
                                            Skill
                                        </th>
                                        <th scope="col" class="px-6 py-3">
                                            Level
                                        </th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach ($chunkedSkills as $skills)
                                    <tr class="border-t border-gray-200 dark:border-gray-700">
                                        <td class="px-6 py-4">
                                            {{ $skills[0]['skill'] }}
                                        </td>
                                        <td class="px-6 py-4">
                                            {{ $skills[0]['level'] }}
                                        </td>
                                        <td class="px-6 py-4">
                                            @if(isset($skills[1])){{ $skills[1]['skill'] }}@endif
                                        </td>
                                        <td class="px-6 py-4">
                                            @if(isset($skills[1])){{ $skills[1]['level'] }}@endif
                                        </td>
                                    </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </section>
        @endif
    </div>
</x-v1.layout>
