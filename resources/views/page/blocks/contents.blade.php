<div id="contents" class="wiki-box squashed">
    <header>
        Contents
        <a id="toggleContentsButton" href="#contentsList" class="btn btn-default btn-xs" data-toggle="collapse">
            Hide
        </a>
    </header>
    <ol id="contentsList" class="collapse in" aria-expanded="true">
        @foreach($page->current_version->sections as $section)
            @if (!$loop->first)
                <li>
                    <a href="#{{ camel_case($section->title) }}">
                        {{ $section->title }}
                    </a>
                    @if ($section->children->count() > 0)
                        <ol>
                            @foreach($section->children as $section)
                                <li>
                                    <a href="#{{ camel_case($section->title) }}">
                                        {{ $section->title }}
                                    </a>
                                </li>
                            @endforeach
                        </ol>
                    @endif
                </li>
            @endif
        @endforeach
    </ol>
</div>