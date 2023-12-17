<nav class="bg-black text-white md:bg-white md:text-black hidden md:block">
    <ul class="mx-auto container p-2 pt-0 md:pt-2">
        <li><a href="{{ route('dashboard') }}">Dashboard</a></li>
        <li><a href="{{ route('account') }}">Account</a></li>
        <li><a href="{{ route('proxy') }}">Proxy</a></li>
        <li><a href="{{ route('script') }}">Script</a></li>
        <li><a href="{{ route('settings') }}">Settings</a></li>
        <li>
            <form method="post" action="{{ route('logout') }}">
                @csrf
                <button>Logout</button>
            </form>
        </li>
    </ul>
</nav>
