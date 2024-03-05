<x-v1.layout page="Add workflow">
    <section>
        <div class="mb-4 text-xl font-bold text-gray-900 dark:text-white">Workflow Management</div>
    </section>
    <section>
    <div class="relative p-4 bg-white rounded-lg shadow dark:bg-gray-800 sm:p-5">
        <div class="flex justify-between items-center pb-4 mb-4 rounded-t border-b sm:mb-5 dark:border-gray-600">
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white">
                Add workflow
            </h3>
        </div>
        <form method="post" action="{{ route('workflow.store') }}">
            @csrf
            <div>
                <label>
                    <input class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 w-full md:w-[400px] mb-4 p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500" id="name" type="text" name="name" placeholder="Name your workflow" required />
                </label>
            </div>
            <div id="app"></div>
        </form>
    </div>
</x-v1.layout>
