<x-v1.layout page="Edit {{ $event->name }}">
    <div class="max-w-2xl px-4 mx-auto">
        <h2 class="mb-4 text-xl font-bold text-gray-900 dark:text-white">Edit schedule event {{ $event->name }}</h2>
        <form method="post" action="{{ route('account.group.schedule.event.update', [$group->id, $event->id]) }}">
            @csrf
            <input type="hidden" name="_method" value="PUT">
            <div class="grid gap-4 mb-4 sm:grid-cols-2 sm:gap-6 sm:mb-5">
                <div class="sm:col-span-2">
                    <label for="name" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Name</label>
                    <input type="text" name="name" id="name" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500" value="{{ $event->name }}" placeholder="Type the event name">
                </div>
                <div class="sm:col-span-2">
                    <label for="day" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Day</label>
                    <select name="day" id="day" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500">
                        @foreach([1 => 'Monday', 2 => 'Tuesday', 3 => 'Wednesday', 4 => 'Thursday', 5 => 'Friday', 6 => 'Saturday', 7 => 'Sunday'] as $k => $v)
                            <option value="{{ $k }}" @if($event->day == $k) selected @endif >{{ $v }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="sm:col-span-1">
                    <label for="start_time" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Start time</label>
                    <select name="start_time" id="start_time" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500">
                        @foreach([0,1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,18,19,20,21,22,23] as $h)
                            @php($ampm = $h >= 12 ? 'PM' : 'AM')
                            @php($h = $h < 10 ? '0'.$h : $h)
                            @php($h_formatted = $h == '00' ? '12' : $h)
                            <option value="{{ $h }}:00" @if("$h:00" == $event->start_at->format('H:i')) selected @endif >{{ $h_formatted > 12 ? ($h_formatted - 12) : $h_formatted }}:00 {{ $ampm }}</option>
                            <option value="{{ $h }}:30" @if("$h:30" == $event->start_at->format('H:i')) selected @endif >{{ $h_formatted > 12 ? ($h_formatted - 12) : $h_formatted }}:30 {{ $ampm }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="sm:col-span-1">
                    <label for="finish_time" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">End time</label>
                    <select name="finish_time" id="finish_time" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500">
                        @foreach([0,1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,18,19,20,21,22,23] as $h)
                            @php($ampm = $h >= 12 ? 'PM' : 'AM')
                            @php($h = $h < 10 ? '0'.$h : $h)
                            @php($h_formatted = $h == '00' ? '12' : $h)
                            <option value="{{ $h }}:00" @if("$h:00" == $event->finish_at->format('H:i')) selected @endif >{{ $h_formatted > 12 ? ($h_formatted - 12) : $h_formatted }}:00 {{ $ampm }}</option>
                            <option value="{{ $h }}:30" @if("$h:30" == $event->finish_at->format('H:i')) selected @endif >{{ $h_formatted > 12 ? ($h_formatted - 12) : $h_formatted }}:30 {{ $ampm }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="sm:col-span-2">
                    <label for="color" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Color</label>
                    <select name="color" id="color" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500">
                        @foreach(['red' => 'Red', 'green' => 'Green', 'blue' => 'Blue'] as $k => $v)
                            <option value="{{ $k }}" @if($event->color == $k) selected @endif >{{ $v }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="flex items-center space-x-4">
                <button type="submit" class="text-white bg-primary-700 hover:bg-primary-800 focus:ring-4 focus:outline-none focus:ring-primary-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-primary-600 dark:hover:bg-primary-700 dark:focus:ring-primary-800">
                    Update
                </button>
            </div>
        </form>
    </div>
</x-v1.layout>
