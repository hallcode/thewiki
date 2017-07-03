@extends('layouts.app')

@section('leftTabs')
    <a href="/" class="active tab">Empty Page</a>
@endsection

@section('rightTabs')
    <a href="{{ route('page.show', ["reference" => $page->reference]) }}" class="active tab">Read</a>
    <a href="{{ route('page.createWithReference', ['reference' => $page->reference]) }}" class="tab">Create</a>
@endsection

@section('content')
    <header>
        <h1><i>{{ $page->title }}</i></h1>
    </header>
    @if ($page->edits->last())
        <p class="meta">Last edited {{ $page->edits->last()->created_at->diffForHumans() }} by <a
                    href="/">{{ $page->edits->last()->user->name }}</a>.</p>
    @endif

    <section id="warnings-tray">
        <article class="warning wiki-box red">
            <div class="icon">
                <i class="fa fa-fw fa-chain-broken"></i>
            </div>
            <div class="body">
                <p>
                    <strong>
                        This page has not yet been created.
                    </strong>
                </p>
                <p>
                    You can help {{ config('app.name') }} by writing this page. Click on the Create tab at the top right of this page
                    to create it.
                </p>
            </div>
        </article>
    </section>

    <p>
        Pages on {{ config('app.name') }} are created by adding links to existing pages. You can create these links by enclosing
        a word in double square brackets (e.g. <code>[[A Link]]</code>).
    </p>
    <p>
        Links to pages that haven't been created yet will show up as <a href="#!" class="red-link">red links</a>. Others will
        look like normal blue hyperlinks.
    </p>

    <h2>Links to this page ({{$linksFrom->count()}})</h2>
    <p>
        The following pages link here already.
    </p>
    @if ($linksFrom->count() !== 0)
        <ol>
            @foreach($linksFrom as $link)
                <li>
                    <a href="{{ route('page.show', ['reference' => $link->page->reference]) }}"
                       class="blue-link">{{ $link->page->title }}</a>
                </li>
            @endforeach
        </ol>
    @else
        <div class="alert alert-info">
            <p>No pages currently link here.</p>
            <p>
                Before creating this page, perhaps edit some other pages so that they link here; the wiki works better
                when pages link to other pages.
            </p>
        </div>
    @endif
@endsection
