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
            <div id="app"></div>
        </form>
    </div>
</x-v1.layout>
