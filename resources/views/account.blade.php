<x-layout>
    @if($errors->isNotEmpty())
        <div>{{ $errors }}</div>
    @endif

    @if(auth()->user()->two_factor_confirmed_at && session('status') != 'two-factor-authentication-confirmed')
        <div>Two-factor authentication is enabled.</div>
    @endif

    @if(!auth()->user()->two_factor_confirmed_at && session('status') != 'two-factor-authentication-enabled')
        <form method="post" action="{{ url('user/two-factor-authentication') }}">
            @csrf
            <button type="submit" class="rounded-md bg-blue-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-blue-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-blue-600">Enable two-factor authentication</button>
        </form>
    @endif

    @if (session('status') == 'two-factor-authentication-enabled')
        <div>
            Please finish configuring two-factor authentication below.
        </div>
        <div>
            {!! request()->user()->twoFactorQrCodeSvg() !!}
        </div>

        <form method="post" action="{{ url('user/confirmed-two-factor-authentication') }}" class="grid gap-2 max-w-lg">
            @csrf
            <label class="block">
                <div class="grid grid-cols-[1fr,auto]">
                    <span class="text-gray-700">Code</span>
                </div>
                <input type="text" name="code" id="code" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
            </label>
            <div>
                <button type="submit" class="rounded-md bg-blue-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-blue-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-blue-600">Enable</button>
            </div>
        </form>
    @endif

    @if (session('status') == 'two-factor-authentication-confirmed')
        <div class="max-w-lg grid gap-2">
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
</x-layout>
