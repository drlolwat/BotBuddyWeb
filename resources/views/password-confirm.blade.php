<x-layout>
    @if($errors->isNotEmpty())
        <div>{{ $errors }}</div>
    @endif
    @if (session('status'))
        <div>{{ session('status') }}</div>
    @endif
    <div class="mb-2">Please confirm your password to proceed.</div>
    <form method="post" class="grid gap-2">
        @csrf
        <label class="block">
            <div>
                <span class="text-gray-700">Password</span>
            </div>
            <input type="password" name="password" id="password" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
        </label>
        <div>
            <button type="submit" class="rounded-md bg-blue-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-blue-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-blue-600">Confirm password</button>
        </div>
    </form>
</x-layout>
