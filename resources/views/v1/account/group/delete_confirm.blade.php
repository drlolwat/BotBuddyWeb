<x-v1.layout page="Delete {{ $group->name }}">

    <div class="max-w-2xl px-4 mx-auto">
        <h2 class="mb-4 text-xl font-bold text-gray-900 dark:text-white">Delete account group</h2>
        <form method="post" action="{{ route('account.group.destroy', $group->id) }}">
            @csrf
            <input type="hidden" name="_method" value="DELETE">
            <div class="grid gap-4 mb-4 sm:grid-cols-2 sm:gap-6 sm:mb-5">
                <div class="sm:col-span-2 text-gray-900 dark:text-white">
                    <p class="pb-2">Are you sure you want to delete {{ $group->name }}?</p>
                    @if($group->accounts_count > 0 || $group->schedule_events_count > 0)
                        <p>Deleting this account group will also delete the following:</p>
                        <ul>
                            <li>- {{ $group->accounts_count }} accounts</li>
                            <li>- {{ $group->schedule_events_count }} schedule events</li>
                        </ul>
                    @endif
                </div>
            </div>
            <div class="flex items-center space-x-4">
                <button type="submit" class="text-white bg-red-700 hover:bg-red-800 focus:ring-4 focus:outline-none focus:ring-red-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-red-600 dark:hover:bg-red-700 dark:focus:ring-red-800">
                    Delete
                </button>
            </div>
        </form>
    </div>
</x-v1.layout>
