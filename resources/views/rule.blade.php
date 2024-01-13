<x-layout>
    <div class="mb-2 text-xl font-bold">Rule Management</div>
    @if($errors->isNotEmpty())
        <div>{{ $errors }}</div>
    @endif
    @if (session('status'))
        <div>{{ session('status') }}</div>
    @endif

    <div class="py-2 font-bold">Create Rule</div>

    <form method="post" action="{{ route('rule.create') }}">
        @csrf
        <div id="app"></div>
    </form>

    <div class="py-2 font-bold">Rules</div>

    <div class="relative overflow-x-auto">
        <table class="w-full text-sm text-left text-gray-500">
            <thead class="text-xs text-white bg-gray-500">
            <tr>
                <th class="px-6 py-3">Model</th>
                <th class="px-6 py-3">Event</th>
                <th class="px-6 py-3">Action</th>
            </tr>
            </thead>
            <tbody>
            @foreach(auth()->user()->rules as $rule)
                <tr class="bg-white border hover:bg-gray-50">
                    <td class="px-6 py-4">{{ $rule->model_type }}:{{ $rule->model_id }}</td>
                    <td class="px-6 py-4">{{ $rule->event }}:{{ json_encode($rule->data) }}</td>
                    <td class="px-6 py-4">{{ $rule->actions()->first()->name }}:{{ json_encode($rule->actions()->first()->data) }}</td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
</x-layout>
