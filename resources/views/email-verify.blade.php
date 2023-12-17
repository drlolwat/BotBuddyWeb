<x-layout>
    @if($errors->isNotEmpty())
        <div>{{ $errors }}</div>
    @endif

    @if (session('status') == 'verification-link-sent')
        <div>
            A new email verification link has been emailed to you.
        </div>
    @else
        <div>Click the verification link sent to {{ request()->user()->email }}</div>
        <form method="post" class="grid gap-2" action="{{ url('/email/verification-notification') }}">
            @csrf
            <div>
                <button type="submit" class="rounded-md bg-blue-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-blue-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-blue-600">Resend email verification link</button>
            </div>
        </form>
    @endif
</x-layout>
