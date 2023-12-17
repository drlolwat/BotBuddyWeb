<x-layout>
    <div class="mb-2 text-xl font-bold">Create Proxy Group</div>

    @if($errors->isNotEmpty())
        <div>{{ $errors }}</div>
    @endif
    @if (session('status'))
        <div>{{ session('status') }}</div>
    @endif

    <form method="post" action="{{ route('proxy.group.store') }}">
        @csrf
        <div>Name</div>
        <input type="text" name="name" class="border-2 border-gray-300 rounded-lg p-2 mb-2 w-full" />
        <button type="submit" class="border-2 border-gray-300 rounded-lg p-2 mb-2 w-full">Create</button>
    </form>
</x-layout>
