<nav id="siteNav">
    @if (Auth::guest())
        <span><i class="fa fa-fw fa-user"></i> Not logged in</span>
        <a href="{{ route('login') }}">Login</a>
        <a href="{{ route('register') }}">Register</a>
    @else
        <a><i class="fa fa-fw fa-user"></i> {{ Auth::user()->name }}</a>

        <a href="{{ route('logout') }}"
           onclick="event.preventDefault();
                                         document.getElementById('logout-form').submit();">
            Logout
        </a>

        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
            {{ csrf_field() }}
        </form>
    @endif
</nav>