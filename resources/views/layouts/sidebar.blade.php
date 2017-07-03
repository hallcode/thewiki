<aside id="sidebar">
    <header>
        <a href="/">
            <img src="/img/Wiki_W.svg">
            <p class="h3">{{ config('app.name') }}</p>
        </a>
    </header>

    @foreach (config('menu') as $menu)
        <nav>
            @if (isset($menu['title']))
                <h5>{{ $menu['title'] }}</h5>
            @endif
            @foreach ($menu['links'] as $title=>$url)
                <a href="{{ url($url) }}">{{ $title }}</a>
            @endforeach
        </nav>
    @endforeach
</aside>