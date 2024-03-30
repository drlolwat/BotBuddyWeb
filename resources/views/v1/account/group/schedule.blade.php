<x-v1.layout page="{{ $group->name }} Schedule">
{{--    <section>--}}
{{--        <div class="mb-4 text-xl font-bold text-gray-900 dark:text-white">{{ $group->name }} Schedule (coming soon)--}}
{{--        </div>--}}
{{--    </section>--}}
    <section>
        <div class="relative p-4 bg-white rounded-lg shadow dark:bg-gray-800 sm:p-5">
            <header
                class="flex flex-none items-center justify-between border-b border-gray-200 dark:border-gray-700 pb-4">
                <h1 class="text-base font-semibold leading-6 text-gray-900 dark:text-gray-100">{{ $group->name }} Schedule</h1>
                <div class="flex items-center">
                    <div class="md:ml-4 md:flex md:items-center">
                        <a href="{{ route('account.group.schedule.create', $group->id) }}"
                                class="ml-6 rounded-md bg-blue-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-blue-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-blue-600">
                            Add event
                        </a>
                    </div>
                </div>
            </header>
            {{-- temp hack --}}
            <script>Window.schedule_events = {!! json_encode($events) !!};</script>
            <div id="schedule_app"></div>
        </div>
    </section>
</x-v1.layout>
