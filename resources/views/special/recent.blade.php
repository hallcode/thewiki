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
                    @if (class_exists($change->parent_type) && isset($change->parent->title))

                            @if (is_a($change->parent, 'App\Models\Page'))

                                @if ($change->action == 'created')
                                    Page <a href="{{ route('page.show', ['reference' => $change->parent->reference]) }}">{{ $change->parent->title }}</a>
                                    was created at <strong>{{ $change->created_at->format('H:i') }}</strong>
                                @else
                                    (diff | hist) . .
                                    <a href="{{ route('page.show', ['reference' => $change->parent->reference]) }}">{{ $change->parent->title }}</a>;
                                    {{ ucfirst($change->action) }} at
                                    <strong>{{ $change->created_at->format('H:i') }}</strong> . .

                                    @if ( $change->parent->wordCountChange($change->created_at)  < 0)
                                        <strong style="color: darkred">({{ $change->parent->wordCountChange($change->created_at) }})</strong>
                                    @elseif($change->parent->wordCountChange($change->created_at)  === 0)
                                        ({{ $change->parent->wordCountChange($change->created_at)  }})
                                    @else
                                        <strong style="color: darkgreen">(+ {{ $change->parent->wordCountChange($change->created_at) }})</strong>
                                    @endif
                                @endif

                            @else
                                {{ $change->parent->title }}
                            @endif
                        @elseif ($change->parent_type == 'special:home')
                            Home Page, Edited at <strong>{{ $change->created_at->format('H:i') }}</strong>
                        @else
                            {{ $change->parent_type }} {{ ucfirst($change->action) }} at
                            <strong>{{ $change->created_at->format('H:i') }}</strong>
                        @endif
                        by <a href="{{ url('/user/' . $change->user->name) }}">{{ $change->user->name }}</a>
                        @if ($change->edit_count > 1)
                            . . x{{ $change->edit_count }}
                        @endif
                    </li>
                @endforeach
            </ul>
        @endforeach
    @else
        <p>No pages. Make one now.</p>
    @endif
@endsection

