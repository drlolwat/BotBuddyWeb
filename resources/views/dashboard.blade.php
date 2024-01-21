<x-layout>
    <div class="text-xl font-bold">Dashboard</div>
    <div class="mb-4">
        <dl class="mt-5 grid grid-cols-1 gap-5 sm:grid-cols-3">
            <div class="overflow-hidden rounded-lg bg-white px-4 py-5 shadow sm:p-6">
                <dt class="truncate text-sm font-medium text-gray-500">Accounts online</dt>
                <dd class="mt-1 text-3xl font-semibold tracking-tight text-gray-900">{{ $online }}</dd>
            </div>
            <div class="overflow-hidden rounded-lg bg-white px-4 py-5 shadow sm:p-6">
                <dt class="truncate text-sm font-medium text-gray-500">Accounts offline</dt>
                <dd class="mt-1 text-3xl font-semibold tracking-tight text-gray-900">{{ $offline }}</dd>
            </div>
            <div class="overflow-hidden rounded-lg bg-white px-4 py-5 shadow sm:p-6">
                <dt class="truncate text-sm font-medium text-gray-500">Accounts banned past 24h</dt>
                <dd class="mt-1 text-3xl font-semibold tracking-tight text-gray-900">{{ $bannedLast24h }}</dd>
            </div>
        </dl>
    </div>
    <div>Welcome {{ auth()->user()->name }}</div>
</x-layout>
