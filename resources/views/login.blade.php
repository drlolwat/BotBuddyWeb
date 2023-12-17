<x-layout>
    @if($errors->isNotEmpty())
        <div>{{ $errors }}</div>
    @endif
    @if (session('status'))
        <div>{{ session('status') }}</div>
    @endif
    <form method="post" class="grid gap-2">
        @csrf
        <label class="block">
            <span class="text-gray-700">Email</span>
            <input type="email" name="email" id="email" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
        </label>
        <label class="block">
            <div class="grid grid-cols-[1fr,auto]">
                <span class="text-gray-700">Password</span>
                <div><a href="{{ route('password.email') }}" class="font-semibold text-blue-600 hover:text-blue-500">Forgot password?</a></div>
            </div>
            <input type="password" name="password" id="password" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
        </label>
        <div>
            <button type="submit" class="rounded-md bg-blue-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-blue-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-blue-600">Login</button>
        </div>
    </form>
    <div class="text-center">Not a member? <a href="{{ route('register') }}" class="font-semibold text-blue-600 hover:text-blue-500">Register now</a></div>
</x-layout>
