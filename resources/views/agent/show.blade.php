<x-layout>
    <div class="mb-2 text-xl font-bold">Update Agent</div>

    @if($errors->isNotEmpty())
        <div>{{ $errors }}</div>
    @endif
    @if (session('status'))
        <div>{{ session('status') }}</div>
    @endif

    <form method="post" action="{{ route('agent.update', $agent->id) }}">
        @csrf
        <input type="hidden" name="_method" value="PUT">
        <label class="block mb-2">
            <div>Name</div>
            <input type="text" name="name" class="border-2 border-gray-300 rounded-lg p-2 w-full" value="{{ $agent->name }}" />
        </label>
        <label class="block mb-2">
            <div>DreamBot client.jar location</div>
            <div class="text-xs text-gray-500">e.g. C:\Users\User\DreamBot\BotData\client.jar</div>
            <input type="text" name="dreambot_client_path" id="dreambot_client_path" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50" value="{{ $agent->dreambot_client_path }}">
        </label>
        <label class="block mb-2">
            <div>DreamBot scripts location</div>
            <div class="text-xs text-gray-500">e.g. C:\Users\User\DreamBot\Scripts</div>
            <input type="text" name="dreambot_scripts_path" id="dreambot_scripts_path" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50" value="{{ $agent->dreambot_scripts_path }}">
        </label>

        <div class="flex gap-2">
            <button type="submit" class="rounded-md bg-blue-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-blue-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-blue-600">Update</button>
            <button form="delete" type="submit" class="rounded-md bg-red-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-red-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-red-600">Delete</button>
        </div>
    </form>
    <form id="delete" method="post" action="{{ route('agent.destroy', $agent->id) }}">
        @csrf
        <input type="hidden" name="_method" value="DELETE">
    </form>
</x-layout>
