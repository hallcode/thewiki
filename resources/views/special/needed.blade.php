@extends('layouts.app')

@section('leftTabs')
    <a href="/" class="active tab">Special Page</a>
@endsection

@section('content')
    <header>
        <h1>Needed Pages</h1>
        <nav>
            <a class="btn btn-success" href="{{ route('page.create') }}">
                <i class="fa fa-fw fa-plus"></i> Create Page
            </a>
        </nav>
    </header>

    <p>
        This page shows a list of all the pages which have been linked to, but not yet made.
    </p>

    @if ($links->count() !== 0)
      <ol>
            @foreach($links as $link)
                <li>
                    <a href="{{ route('page.show', ['reference' => $link->link_reference]) }}" class="red-link">
                        {{ $link->link_reference }}
                        ({{$link->count}})
                    </a>
                </li>
            @endforeach
        </ol>

        {{ $links->links() }}
    @else
        <p>No pages. Make one now.</p>
    @endif
@endsection

