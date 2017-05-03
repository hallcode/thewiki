@extends('layouts.app')

@section('leftTabs')
    <a href="/" class="active tab">Special Page</a>
@endsection

@section('content')
    <header>
        <h1>Recent Changes</h1>
    </header>

    <p>
        This page shows a list of the recent changes.
    </p>

    @if ($all_changes->count() !== 0)
        @foreach($all_changes->groupBy('date') as $date=>$changes)
            <h4>{{ date('jS F', strtotime($date)) }}</h4>
            <ul>
                @foreach($changes as $change)
                    <li>
                        (x{{ $change->edit_count }}) . .
                    @if (class_exists($change->parent_type) && isset($change->parent->title))
                            @if (is_a($change->parent, 'App\Models\Page'))
                                <a href="{{ route('page.show', ['reference' => $change->parent->reference]) }}">{{ $change->parent->title }}</a>;
                                {{ ucfirst($change->action) }}
                                <strong>{{ $change->created_at->format('H:i') }}</strong> . .
                                @if ( $change->parent->word_count_change < 0)
                                    <strong style="color: darkred">({{ $change->parent->word_count_change }})</strong>
                                @elseif($change->parent->word_count_change === 0)
                                    ({{ $change->parent->word_count_change }})
                                @else
                                    <strong style="color: darkgreen">(+ {{ $change->parent->word_count_change }})</strong>
                                @endif
                            @else
                                {{ $change->parent->title }}
                            @endif
                        @else
                            {{ $change->parent_type }} {{ ucfirst($change->action) }} at
                            <strong>{{ $change->created_at->format('H:i') }}</strong>
                        @endif
                        by <a href="{{ url('/user/' . $change->user->name) }}">{{ $change->user->name }}</a>
                    </li>
                @endforeach
            </ul>
        @endforeach
    @else
        <p>No pages. Make one now.</p>
    @endif
@endsection

