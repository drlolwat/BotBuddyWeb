<x-v1.layout page="{{ $trigger->name }}">
    <div class="max-w-2xl px-4 mx-auto">
        <div class="bg-white dark:bg-gray-800 relative shadow-md sm:rounded-lg">
            <div class="flex flex-col md:flex-row items-center justify-between space-y-3 md:space-y-0 md:space-x-4 p-4">
                <div class="w-full">
                    <h2 class="mb-4 text-xl font-bold text-gray-900 dark:text-white">Update script trigger</h2>
                    <form method="post" action="{{ route('script.trigger.update', $trigger->id) }}">
                        @csrf
                        <input type="hidden" name="_method" value="PUT">
                        <input type="hidden" name="script_id" value="{{ $trigger->script_id }}" />
                        <div class="grid gap-4 mb-4 sm:grid-cols-2 sm:gap-6 sm:mb-5">
                            <div class="sm:col-span-2">
                                <label for="name" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Name</label>
                                <input type="text" name="name" id="name" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500"
                                       placeholder="Type the name of this trigger"
                                       value="{{ $trigger->name }}"
                                >
                            </div>
                            <div class="sm:col-span-2">
                                <label for="message" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Message</label>
                                <input type="text" name="message" id="message"
                                       class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500"
                                       placeholder="Type the log message to trigger a workflow with"
                                       value="{{ $trigger->message }}"
                                >
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
                    <form id="delete" method="post" action="{{ route('script.trigger.destroy', $trigger->id) }}">
                        @csrf
                        <input type="hidden" name="_method" value="DELETE">
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-v1.layout>
