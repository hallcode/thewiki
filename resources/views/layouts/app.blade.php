@include('layouts.html_head')
<body>
<div id="app">

    @include('layouts.sidebar')

    <main id="main">

        @include('layouts.site_nav')

        <section id="content">
            <header>
                <div id="tabs">
                    <nav>
                        <div class="active mobile-only dropdown">
                            <a href="#!" class="dropdown-toggle" type="button" id="mainMenu" data-toggle="dropdown"
                               aria-haspopup="true" aria-expanded="true">
                                <i class="fa fa-fw fa-bars"></i> Menu
                            </a>
                            <nav class="dropdown-menu">
                                @foreach (config('menu') as $menu)
                                    @if (isset($menu['title']))
                                        <h5>{{ $menu['title'] }}</h5>
                                    @endif
                                    @foreach ($menu['links'] as $title=>$url)
                                        <a href="{{ url($url) }}">{{ $title }}</a>
                                    @endforeach
                                @endforeach
                            </nav>
                        </div>
                        @yield('leftTabs')
                    </nav>
                    <nav>
                        <div class="active mobile-only dropdown">
                            <a href="#!" class="dropdown-toggle" type="button" id="actionsMenu" data-toggle="dropdown"
                               aria-haspopup="true" aria-expanded="true">
                                Actions
                                <span class="caret"></span>
                            </a>
                            <nav class="dropdown-menu dropdown-menu-right">
                                @yield('leftTabs')
                                @yield('rightTabs')
                            </nav>
                        </div>
                        @yield('rightTabs')
                    </nav>
                </div>
                <div id="search">
                    <form method="post" action="{{ url('/search') }}" class="form-inline">
                        <div class="form-group">
                            <label class="sr-only" for="search">Search</label>
                            <div class="input-group">
                                <input class="form-control" id="search" name="search" type="search"
                                       placeholder="Search...">
                                    <span class="input-group-btn">
                                        <button type="submit" class="btn btn-default"><i class="fa fa-fw fa-search"></i>
                                        </button>
                                    </span>
                            </div>
                        </div>
                    </form>
                </div>
            </header>
            <article id="page-content">
                @yield('content')
            </article>
        </section>
        @include('layouts.footer')
    </main>
</div>


<!-- Scripts -->
<script type="text/javascript" src="/DataTables/datatables.min.js"></script>
<script src="{{ asset('js/app.js') }}"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/js/select2.min.js"></script>
<script>
    $.fn.select2.defaults.set("theme", "bootstrap");
    $('select.select2').select2();
</script>
<script>
    $(document).ready(function() {
        $(document).on('keyup keypress', 'form input[type="text"]', function(e) {
            if(e.which == 13) {
                e.preventDefault();
                return false;
            }
        });
    });
</script>
<script>
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    $('.collapse').collapse({
        toggle: false
    });
</script>
<script>
    $('#contentsList').on('hide.bs.collapse', function () {
        $('#toggleContentsButton').html('Show')
    });
    $('#contentsList').on('show.bs.collapse', function () {
        $('#toggleContentsButton').html('Hide')
    })
</script>
@yield('script')
</body>
</html>
