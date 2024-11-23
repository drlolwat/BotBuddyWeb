<x-v1.layout page="Add account group">
    <div class="bg-white dark:bg-gray-800 relative shadow-md sm:rounded-lg">
        <div class="flex flex-col md:flex-row items-center justify-between space-y-3 md:space-y-0 md:space-x-4 p-4">
            <div class="w-full">
                <h2 class="mb-4 text-xl font-bold text-gray-900 dark:text-white">Add account group</h2>
                <form method="post" action="{{ route('account.group.store') }}">
                    @csrf
                    <div class="grid gap-4 mb-6 sm:grid-cols-2 sm:gap-6">
                        <div class="sm:col-span-2">
                            <label for="name" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Name</label>
                            <input type="text" name="name" id="name" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500" placeholder="Type the group name">
                        </div>
                        <div class="sm:col-span-2">
                            <label for="agent_id" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Agent</label>
                            <select name="agent_id" id="agent_id" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500">
                                <option value="0">None</option>
                                @foreach(auth()->user()->agents as $agent)
                                    <option value="{{ $agent->id }}">{{ $agent->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label for="script_id" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Script</label>
                            <select name="script_id" id="script_id" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500">
                                <option value="0">None</option>
                                @foreach(auth()->user()->scripts as $script)
                                    <option value="{{ $script->id }}">{{ $script->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label for="script_params" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Script params</label>
                            <input type="text" name="script_params" id="script_params" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500" placeholder="Enter script params (optional)">
                        </div>
                        <div>
                            <label for="world" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">World</label>
                            <input type="text" name="world" id="world" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500" value="f2p" placeholder="Type the world (world id, 'f2p' or 'members')">
                        </div>
                        <div>
                            <label for="fps" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">FPS</label>
                            <input type="number" name="fps" id="fps" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500" value="5" placeholder="Type the group name">
                        </div>
                    </div>
                    <details class="mb-4">
                        <summary class="mb-4 text-lg font-bold text-gray-900 dark:text-white">Advanced options</summary>
                        <div>
                            <div class="grid gap-1 mb-4 sm:grid-cols-2 sm:mb-5">
                                <div>
                                    <label for="db_render" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Render mode</label>
                                    <select name="db_render" id="db_render" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500">
                                        <option value="all">All</option>
                                        <option value="script">Script</option>
                                        <option value="none">None</option>
                                    </select>
                                </div>
                            </div>
                            <div class="grid gap-1 mb-4 sm:grid-cols-2 sm:mb-5">
                                <div class="flex items-center space-x-4 mb-2">
                                    <input type="checkbox" name="disable_browser_proxy" id="disable_browser_proxy" class="rounded-lg focus:ring-primary-600 focus:ring-offset-0 focus:ring-2 focus:outline-none focus:ring-offset-gray-50">
                                    <label for="disable_browser_proxy" class="text-gray-900 dark:text-white">Disable browser proxy</label>
                                </div>
                                <div class="flex items-center space-x-4 mb-2">
                                    <input type="checkbox" name="db_debug" id="db_debug" class="rounded-lg focus:ring-primary-600 focus:ring-offset-0 focus:ring-2 focus:outline-none focus:ring-offset-gray-50">
                                    <label for="db_debug" class="text-gray-900 dark:text-white">Debug mode</label>
                                </div>
                                <div class="flex items-center space-x-4 mb-2">
                                    <input type="checkbox" name="db_disable_animations" id="db_disable_animations" class="rounded-lg focus:ring-primary-600 focus:ring-offset-0 focus:ring-2 focus:outline-none focus:ring-offset-gray-50">
                                    <label for="db_disable_animations" class="text-gray-900 dark:text-white">Disable animations</label>
                                </div>
                                <div class="flex items-center space-x-4 mb-2">
                                    <input type="checkbox" name="db_disable_models" id="db_disable_models" class="rounded-lg focus:ring-primary-600 focus:ring-offset-0 focus:ring-2 focus:outline-none focus:ring-offset-gray-50">
                                    <label for="db_disable_models" class="text-gray-900 dark:text-white">Disable models</label>
                                </div>
                                <div class="flex items-center space-x-4 mb-2">
                                    <input type="checkbox" name="db_disable_sounds" id="db_disable_sounds" class="rounded-lg focus:ring-primary-600 focus:ring-offset-0 focus:ring-2 focus:outline-none focus:ring-offset-gray-50">
                                    <label for="db_disable_sounds" class="text-gray-900 dark:text-white">Disable sounds</label>
                                </div>
                                <div class="flex items-center space-x-4 mb-2">
                                    <input type="checkbox" name="db_dismiss_random_events" id="db_dismiss_random_events" class="rounded-lg focus:ring-primary-600 focus:ring-offset-0 focus:ring-2 focus:outline-none focus:ring-offset-gray-50">
                                    <label for="db_dismiss_random_events" class="text-gray-900 dark:text-white">Dismiss random events</label>
                                </div>
                                <div class="flex items-center space-x-4 mb-2">
                                    <input type="checkbox" name="db_low_detail" id="db_low_detail" class="rounded-lg focus:ring-primary-600 focus:ring-offset-0 focus:ring-2 focus:outline-none focus:ring-offset-gray-50">
                                    <label for="db_low_detail" class="text-gray-900 dark:text-white">Low detail</label>
                                </div>
                                <div class="flex items-center space-x-4 mb-2">
                                    <input type="checkbox" name="db_menu_manipulation" id="db_menu_manipulation" class="rounded-lg focus:ring-primary-600 focus:ring-offset-0 focus:ring-2 focus:outline-none focus:ring-offset-gray-50">
                                    <label for="db_menu_manipulation" class="text-gray-900 dark:text-white">Menu manipulation</label>
                                </div>
                                <div class="flex items-center space-x-4 mb-2">
                                    <input type="checkbox" name="db_no_click_walk" id="db_no_click_walk" class="rounded-lg focus:ring-primary-600 focus:ring-offset-0 focus:ring-2 focus:outline-none focus:ring-offset-gray-50">
                                    <label for="db_no_click_walk" class="text-gray-900 dark:text-white">No click walk</label>
                                </div>
                                <div class="flex items-center space-x-4 mb-2">
                                    <input type="checkbox" name="db_minimized" id="db_minimized" class="rounded-lg focus:ring-primary-600 focus:ring-offset-0 focus:ring-2 focus:outline-none focus:ring-offset-gray-50">
                                    <label for="db_minimized" class="text-gray-900 dark:text-white">Minimized</label>
                                </div>
                                @if(in_array(auth()->user()->subscription->name, ['Farm', 'Founder']))
                                    <div class="flex items-center space-x-4 mb-2">
                                        <input type="checkbox" name="db_beta" id="db_beta" class="rounded-lg focus:ring-primary-600 focus:ring-offset-0 focus:ring-2 focus:outline-none focus:ring-offset-gray-50">
                                        <label for="db_beta" class="text-gray-900 dark:text-white">Beta mode</label>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </details>
                    <div class="flex items-center space-x-4">
                        <button type="submit" class="text-white bg-primary-700 hover:bg-primary-800 focus:ring-4 focus:outline-none focus:ring-primary-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-primary-600 dark:hover:bg-primary-700 dark:focus:ring-primary-800">
                            Add
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-v1.layout>
