<x-layout>
    <div class="mb-2 text-xl font-bold">Create Agent</div>

    @if($errors->isNotEmpty())
        <div>{{ $errors }}</div>
    @endif
    @if (session('status'))
        <div>{{ session('status') }}</div>
    @endif

    <form method="post" action="{{ route('agent.store') }}">
        @csrf
        <label class="block mb-2">
            <div>Name</div>
            <input type="text" name="name" class="border-2 border-gray-300 rounded-lg p-2 w-full" />
        </label>
        <label class="block mb-2">
            <div>DreamBot client.jar location</div>
            <div class="text-xs text-gray-500">e.g. C:\Users\User\DreamBot\BotData\client.jar</div>
            <input type="text" name="dreambot_client_path" id="dreambot_client_path" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
        </label>
        <label class="block mb-2">
            <div>DreamBot scripts location</div>
            <div class="text-xs text-gray-500">e.g. C:\Users\User\DreamBot\Scripts</div>
            <input type="text" name="dreambot_scripts_path" id="dreambot_scripts_path" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
        </label>
        <button type="submit" class="rounded-md bg-blue-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-blue-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-blue-600">Create</button>

    </form>
</x-layout>
