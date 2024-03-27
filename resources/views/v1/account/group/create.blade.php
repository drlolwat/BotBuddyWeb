<x-v1.layout page="Add account group">
    <div class="max-w-2xl px-4 mx-auto">
        <h2 class="mb-4 text-xl font-bold text-gray-900 dark:text-white">Add account group</h2>
        <form method="post" action="{{ route('account.group.store') }}">
            @csrf
            <div class="grid gap-4 mb-4 sm:grid-cols-2 sm:gap-6 sm:mb-5">
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
            <div class="flex items-center space-x-4">
                <button type="submit" class="text-white bg-primary-700 hover:bg-primary-800 focus:ring-4 focus:outline-none focus:ring-primary-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-primary-600 dark:hover:bg-primary-700 dark:focus:ring-primary-800">
                    Add
                </button>
            </div>
        </form>
    </div>
</x-v1.layout>
