<x-v1.layout page="Add schedule event">
    <div class="max-w-2xl px-4 mx-auto">
        <h2 class="mb-4 text-xl font-bold text-gray-900 dark:text-white">Add event to {{ $group->name }} schedule</h2>
        <form method="post" action="{{ route('account.group.schedule.create', $group->id) }}">
            @csrf
            <div class="grid gap-4 mb-4 sm:grid-cols-2 sm:gap-6 sm:mb-5">
                <div class="sm:col-span-2">
                    <label for="name" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Name</label>
                    <input type="text" name="name" id="name" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500" placeholder="Type the event name">
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
                <div class="sm:col-span-2" id="schedule_multiselect"></div>
                <div class="sm:col-span-1">
                    <label for="start_time" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Start time</label>
                    <select name="start_time" id="start_time" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500">
                        @foreach([0,1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,18,19,20,21,22,23] as $h)
                            @php($ampm = $h >= 12 ? 'PM' : 'AM')
                            @php($h = $h < 10 ? '0'.$h : $h)
                            @php($h_formatted = $h == '00' ? '12' : $h))
                            <option value="{{ $h }}:00">{{ $h_formatted > 12 ? ($h_formatted - 12) : $h_formatted }}:00 {{ $ampm }}</option>
                            <option value="{{ $h }}:30">{{ $h_formatted > 12 ? ($h_formatted - 12) : $h_formatted }}:30 {{ $ampm }}</option>
                            @endforeach
                    </select>
                </div>
                <div class="sm:col-span-1">
                    <label for="finish_time" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">End time</label>
                    <select name="finish_time" id="finish_time" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500">
                        @foreach([0,1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,18,19,20,21,22,23] as $h)
                            @php($ampm = $h >= 12 ? 'PM' : 'AM')
                            @php($h = $h < 10 ? '0'.$h : $h)
                            @php($h = $h == '00' ? '12' : $h))
                            <option value="{{ $h }}:00">{{ $h > 12 ? ($h - 12) : $h }}:00 {{ $ampm }}</option>
                            <option value="{{ $h }}:30">{{ $h > 12 ? ($h - 12) : $h }}:30 {{ $ampm }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="sm:col-span-2">
                    <label for="color" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Color</label>
                    <select name="color" id="color" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500">
                        @foreach(['red' => 'Red', 'green' => 'Green', 'blue' => 'Blue', 'purple' => 'Purple', 'pink' => 'Pink', 'yellow' => 'Yellow', 'orange' => 'Orange'] as $k => $v)
                            <option value="{{ $k }}">{{ $v }}</option>
                        @endforeach
                    </select>
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
