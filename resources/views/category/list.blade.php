@extends('layouts.app')

@section('leftTabs')
    <a href="/" class="active tab">All Categories</a>
@endsection

@section('content')
    <header>
        <h1>All Categories</h1>
        <nav>
            <a class="btn btn-success" href="{{ route('page.create') }}">
                <i class="fa fa-fw fa-plus"></i> Create Page
            </a>
        </nav>
    </header>

    <p>
        This page shows a list of all the categories in the wiki.
    </p>

    @if ($categories->count() !== 0)
        <dl class="text-columns">
            @foreach($categories->groupBy('first_letter') as $first_letter=>$cat_list)
                <dt>
                    {{ $first_letter }}
                </dt>
                <dd>
                    <ul>
                        @foreach ($cat_list as $cat)
                            <li>
                                <a href="{{ route('category.show', ['title' => $cat->title]) }}">{{ $cat->title }}</a>
                            </li>
                        @endforeach
                    </ul>
                </dd>
            @endforeach
        </dl>

        {{ $categories->links() }} <em>Total categories: {{ $categories->total() }}</em>
    @else
        <p>No Categories. Make sure when you create a new page, to add it to some categories!</p>
    @endif
@endsection

