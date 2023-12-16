<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>BotBuddy</title>
    @vite('resources/css/app.css')
</head>
<body>
<div class="min-h-screen grid grid-rows-[auto,1fr,auto] font-arial">
    <div>
        <div class="p-2">
            <div class="mx-auto container text-lg font-bold">BotBuddy</div>
        </div>
    </div>
    <div class="min-h-full">
        <div class="p-2">
            <div class="mx-auto max-w-md">
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
            </div>
        </div>
    </div>
    <footer class="sticky top-[100vh]">
        <div class="mx-auto container p-2">&copy; 2024 BotBuddy</div>
    </footer>
</div>
</body>
</html>
