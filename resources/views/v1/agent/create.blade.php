<x-v1.layout page="Add agent">
    <div class="max-w-2xl px-4 mx-auto">
        <h2 class="mb-4 text-xl font-bold text-gray-900 dark:text-white">Add agent</h2>
        <form method="post" action="{{ route('agent.store') }}">
            @csrf
            <div class="grid gap-4 mb-4 sm:grid-cols-2 sm:gap-6 sm:mb-5">
                <div class="sm:col-span-2">
                    <label for="name" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Name</label>
                    <input type="text" name="name" id="name" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500" placeholder="Type the agent name">
                </div>
                <div class="sm:col-span-2">
                    <label for="dreambot_client_path" class="block text-sm font-medium text-gray-900 dark:text-white">DreamBot client.jar location</label>
                    <div class="mb-2 text-sm text-gray-900 dark:text-gray-400">e.g. C:\Users\User\DreamBot\BotData\client.jar</div>
                    <input type="text" name="dreambot_client_path" id="dreambot_client_path" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500" placeholder="Type the path on your device to client.jar">
                </div>
                <div class="sm:col-span-2">
                    <label for="dreambot_scripts_path" class="block text-sm font-medium text-gray-900 dark:text-white">DreamBot scripts location</label>
                    <div class="mb-2 text-sm text-gray-900 dark:text-gray-400">e.g. C:\Users\User\DreamBot\Scripts</div>
                    <input type="text" name="dreambot_scripts_path" id="dreambot_scripts_path" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500" placeholder="Type the path on your device to the scripts folder">
                </div>
                <div class="sm:col-span-2">
                    <label for="dreambot_min_heap" class="block text-sm font-medium text-gray-900 dark:text-white">DreamBot minimum RAM</label>
                    <div class="mb-2 text-sm text-gray-900 dark:text-gray-400">e.g. 256M or 1G</div>
                    <input type="text" name="dreambot_min_heap" id="dreambot_min_heap" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500" value="256M" placeholder="Type the min amount of RAM to allocate to DreamBot">
                </div>
                <div class="sm:col-span-2">
                    <label for="dreambot_max_heap" class="block text-sm font-medium text-gray-900 dark:text-white">DreamBot maximum RAM</label>
                    <div class="mb-2 text-sm text-gray-900 dark:text-gray-400">e.g. 512M or 4G</div>
                    <input type="text" name="dreambot_max_heap" id="dreambot_max_heap" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500" value="512M" placeholder="Type the max amount of RAM to allocate to DreamBot">
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
