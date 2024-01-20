<x-layout>
    <div class="mb-2 text-xl font-bold">Workflow Management</div>
    @if($errors->isNotEmpty())
        <div>{{ $errors }}</div>
    @endif
    @if (session('status'))
        <div>{{ session('status') }}</div>
    @endif

    <div class="py-2 font-bold">Create Workflow</div>

    <form method="post" action="{{ route('workflow.create') }}">
        @csrf
        <div id="app"></div>
    </form>

    <div class="py-2 font-bold">Workflows</div>

    <div class="relative overflow-x-auto">
        <table class="w-full text-sm text-left text-gray-500">
            <thead class="text-xs text-white bg-gray-500">
            <tr>
                <th class="px-6 py-3">Model</th>
                <th class="px-6 py-3">Event</th>
                <th class="px-6 py-3">Actions</th>
            </tr>
            </thead>
            <tbody>
            @foreach($workflows as $workflow)
                <tr class="bg-white border hover:bg-gray-50">
                    <td class="px-6 py-4">{{ $workflow->model_type }}:{{ $workflow->model_id }}</td>
                    <td class="px-6 py-4">{{ $workflow->event }}@if($workflow->data):{{ json_encode($workflow->data) }}@endif</td>
                    <td class="px-6 py-4">
                        {{-- todo: clean up this nasty shit --}}
                        {{ json_encode(array_map(function ($x) { $y=['name'=>$x['name']];if($x['data']){$y['data']=$x['data'];}return $y;}, $workflow->actions->toArray())) }}
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
</x-layout>
