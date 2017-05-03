@extends('layouts.app')

@section('leftTabs')
    @foreach($tabsLeft as $title=>$url)
        <a href="{{ $url }}" class="@if ($url == $_url) active @endif tab">{{ $title }}</a>
    @endforeach
@endsection

@section('rightTabs')
    <a href="{{ route('page.show', ["reference" => $page->reference]) }}" class="active tab">Read</a>
    <a href="{{ route('page.edit', ['reference' => $page->reference]) }}" class="tab">Edit</a>
    <a href="#" class="tab">View history</a>
@endsection

@section('content')
    <header>
        <h1>{{ $page->title }}</h1>
        <nav>
            <a href="#" class="icon-button"><i class="fa fa-fw fa-star-o"></i> </a>
            @can('protect', $page)
            <a href="#" class="icon-button"><i class="fa fa-fw fa-shield"></i> </a>
            @endcan
            @can('delete', $page)
            <a href="#" class="icon-button"><i class="fa fa-fw fa-trash-o"></i> </a>
            @endcan
        </nav>
    </header>
    @if ($page->edits->last())
        <p class="meta">Last edited {{ $page->edits->last()->created_at->diffForHumans() }} by <a
                    href="/">{{ $page->edits->last()->user->name }}</a>.</p>
    @endif

    <section id="page-text" class="clearfix">
            {!! $page->current_version->html !!}
    </section>

    <table class="info-box">
        <thead>
            <tr>
                <th colspan="2">
                    Page Info
                </th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <th>Categories ({{ $page->categories->count() }})</th>
                <td>
                    @foreach ($page->categories as $category)
                        <a href="#">{{ $category->title }}</a>@if(!$loop->last) • @endif
                    @endforeach
                </td>
            </tr>
            <tr>
                <th>Links to ({{ $page->links_to->count() }})</th>
                <td>
                    @foreach ($page->links_to as $link)
                        <a href="{{ route('page.show', ['reference' => $link->link_reference]) }}" class="{{ ( $link->target_page_id !== null ? 'blue-link' : 'red-link') }}">
                            {{ $link->link_reference }}
                        </a>@if(!$loop->last) • @endif
                    @endforeach
                </td>
            </tr>
            <tr>
                <th>Linked from ({{ $page->linked_from->count() }})</th>
                <td>
                    @foreach ($page->linked_from as $link)
                        <a href="{{ route('page.show', ['reference' => $link->page->reference]) }}" class="blue-link">{{ $link->page->title }}</a>@if(!$loop->last) • @endif
                    @endforeach
                </td>
            </tr>
        </tbody>
    </table>
@endsection
