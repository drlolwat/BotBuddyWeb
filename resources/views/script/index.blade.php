<x-layout>
    <div class="mb-2 text-xl font-bold">Script Management</div>

    <hr />

    <div class="grid grid-cols-[auto,1fr] gap-2">
        <div class="py-2 font-bold">Scripts</div>
        <div class="py-2"><a href="{{ route('script.create') }}" class="btn btn-primary">Add Script</a></div>
    </div>
    <table class="table-auto">
        <thead>
        <tr>
            <th>Name</th>
            <th>Script</th>
        </tr>
        </thead>
        <tbody>
        @foreach(auth()->user()->scripts as $script)
            <tr>
                <td>{{ $script->name }}</td>
                <td>{{ $script->script }}</td>
            </tr>
        @endforeach
        </tbody>
    </table>

</x-layout>
