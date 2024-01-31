<x-v1.layout page="Dashboard">
    <dl class="grid grid-cols-1 gap-5 sm:grid-cols-3">
        <div class="overflow-hidden rounded-lg bg-white border border-gray-200 px-4 py-5 dark:bg-gray-800 dark:border-gray-700">
            <dt class="mb-2 text-sm font-medium tracking-tight text-gray-900 dark:text-white">Accounts online</dt>
            <dd class="text-3xl font-semibold tracking-tight text-gray-700 dark:text-gray-400">{{ $online }}</dd>
        </div>
        <div class="overflow-hidden rounded-lg bg-white border border-gray-200 px-4 py-5 dark:bg-gray-800 dark:border-gray-700">
            <dt class="mb-2 text-sm font-medium tracking-tight text-gray-900 dark:text-white">Accounts offline</dt>
            <dd class="text-3xl font-semibold tracking-tight text-gray-700 dark:text-gray-400">{{ $offline }}</dd>
        </div>
        <div class="overflow-hidden rounded-lg bg-white border border-gray-200 px-4 py-5 dark:bg-gray-800 dark:border-gray-700">
            <dt class="mb-2 text-sm font-medium tracking-tight text-gray-900 dark:text-white">Accounts banned past 24h</dt>
            <dd class="text-3xl font-semibold tracking-tight text-gray-700 dark:text-gray-400">{{ $bannedLast24h }}</dd>
        </div>
    </dl>
</x-v1.layout>
