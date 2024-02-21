<x-v1.layout page="Settings">
    <div class="max-w-2xl px-4 mx-auto">
        <h2 class="mb-4 text-xl font-bold text-gray-900 dark:text-white">Dark mode</h2>
        <form method="post" action="{{ route('settings.dark_mode') }}" class="mb-4">
            @csrf
            <div class="flex items-center space-x-4 mb-4">
                <input type="checkbox" name="dark_mode" id="dark_mode" class="rounded-lg focus:ring-primary-600 focus:ring-offset-0 focus:ring-2 focus:outline-none focus:ring-offset-gray-50" {{ auth()->user()->dark_mode ? 'checked' : '' }}>
                <label for="dark_mode" class="text-gray-900 dark:text-white">Dark mode enabled</label>
            </div>
            <button type="submit" class="text-white bg-primary-700 hover:bg-primary-800 focus:ring-4 focus:outline-none focus:ring-primary-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-primary-600 dark:hover:bg-primary-700 dark:focus:ring-primary-800">
                Update
            </button>
        </form>
        <h2 class="mb-4 text-xl font-bold text-gray-900 dark:text-white">DreamBot settings</h2>
        <form method="post" action="{{ route('settings.update') }}" class="mb-4">
            @csrf
            <div class="grid gap-4 mb-4 sm:grid-cols-2 sm:gap-6 sm:mb-5">
                <div class="sm:col-span-2">
                    <label for="name" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">DreamBot username</label>
                    <input type="text" name="dreambot_username" id="dreambot_username" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500" value="{{ auth()->user()->dreambot_username }}" placeholder="Type DreamBot username">
                </div>
                <div class="sm:col-span-2">
                    <label for="name" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">DreamBot password</label>
                    <input type="text" name="dreambot_password" id="dreambot_password" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500">
                </div>
            </div>
            <div class="flex items-center space-x-4">
                <button type="submit" class="text-white bg-primary-700 hover:bg-primary-800 focus:ring-4 focus:outline-none focus:ring-primary-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-primary-600 dark:hover:bg-primary-700 dark:focus:ring-primary-800">
                    Update
                </button>
            </div>
        </form>
        <h2 class="mb-4 text-xl font-bold text-gray-900 dark:text-white">Two-factor authentication</h2>
        <div>
            @if(auth()->user()->two_factor_confirmed_at)
                <div class="text-sm text-gray-900 dark:text-white">
                    Two-factor authentication is enabled.
                </div>
            @endif

            @if(!auth()->user()->two_factor_confirmed_at && session('status') != 'two-factor-authentication-enabled')
                <form method="post" action="{{ url('user/two-factor-authentication') }}">
                    @csrf
                    <button type="submit" class="text-white bg-primary-700 hover:bg-primary-800 focus:ring-4 focus:outline-none focus:ring-primary-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-primary-600 dark:hover:bg-primary-700 dark:focus:ring-primary-800">Enable two-factor authentication</button>
                </form>
            @endif

            @if (session('status') == 'two-factor-authentication-enabled')
                <div class="block mb-2 text-sm text-gray-900 dark:text-white">
                    Please finish configuring two-factor authentication below.
                </div>
                <div>
                    {!! request()->user()->twoFactorQrCodeSvg() !!}
                </div>

                <form method="post" action="{{ url('user/confirmed-two-factor-authentication') }}" class="grid gap-2 max-w-lg">
                    @csrf
                    <label class="block">
                        <div class="block mt-4 mb-2 text-sm text-gray-900 dark:text-white">
                            Code
                        </div>
                        <input type="text" name="code" id="code" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500">
                    </label>
                    <div>
                        <button type="submit" class="text-white bg-primary-700 hover:bg-primary-800 focus:ring-4 focus:outline-none focus:ring-primary-300 font-medium rounded-lg text-sm px-5 mt-4 py-2.5 text-center dark:bg-primary-600 dark:hover:bg-primary-700 dark:focus:ring-primary-800">Enable</button>
                    </div>
                </form>
            @endif

            @if (session('status') == 'two-factor-authentication-confirmed')
                <div class="max-w-lg grid gap-2 text-sm text-gray-900 dark:text-white">
                    <div>Two-factor authentication confirmed and enabled successfully.</div>
                    <div>
                        <div>Recovery codes</div>
                        <ul class="list-disc">
                            @foreach (request()->user()->recoveryCodes() as $code)
                                <li class="ml-5">{{ $code }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            @endif
        </div>
    </div>
</x-v1.layout>
