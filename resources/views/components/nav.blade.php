<nav>
    <ul>
        <li><a href="{{ route('dashboard') }}">Dashboard</a></li>
        <li><a href="{{ route('account') }}">Account</a></li>
        <li>
            <form method="post" action="{{ route('logout') }}">
                @csrf
                <button>Logout</button>
            </form>
        </li>
    </ul>
</nav>
