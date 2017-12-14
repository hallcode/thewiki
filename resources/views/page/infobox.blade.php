@extends('layouts.app')

@section('leftTabs')
    @foreach($tabsLeft as $title=>$url)
        <a href="{{ $url }}" class="@if ($url == $_url) active @endif tab">{{ $title }}</a>
    @endforeach
@endsection

@section('rightTabs')
    <a href="{{ route('page.show', ["reference" => $page->reference]) }}" class="tab">Read</a>
    <a href="#!" class="tab active">Edit</a>
    <a href="#" class="tab">View history</a>
@endsection

@section('content')
    <header>
        <h1>Edit: {{ $page->combinedTitle }} - InfoBox</h1>
        <nav>
            <button class="btn btn-primary" form="infobox_form">Save</button>
            <a class="btn btn-default" href="{{ route('page.show', ["reference" => $page->reference]) }}">Back</a>
        </nav>
    </header>

    <form v-pre id="infobox_form" method="post" action="{{ route('infobox.save', ['reference' => $page->reference]) }}">
        {!! csrf_field() !!}

        <div style="display: grid; grid-template-columns: 3fr 2fr">
            <div>
                <div class="form-group">
                    <label for="md-editor">Infobox</label>
                    <p>
                        You can write the Infobox here using YAML.
                    </p>
                    <p>
                        <strong>Do not use tabs!</strong> Instead, use four spaces. If you use tabs, things will break.
                    </p>
                    <textarea id="yaml-editor" name="yaml" style="height: auto">{{ $page->infobox->yaml }}</textarea>
                </div>
            </div>
            <div id="infoboxPreview" class="clearfix">
                {!! $page->infobox->renderHTML() !!}
            </div>
        </div>

        <div class="form-group wiki-box left-align">
            <button class="btn btn-primary" form="infobox_form">Save</button>
            <a class="btn btn-default" href="{{ route('page.show', ["reference" => $page->reference]) }}">Back</a>
        </div>
    </form>
@endsection

@section ('script')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.27.4/mode/yaml/yaml.min.js"></script>
    <script>
        var editorArea = document.getElementById('yaml-editor')
        var yamlEditor = CodeMirror.fromTextArea(editorArea, {
            mode: 'text/x-yaml',
            theme: 'elegant',
            smartIndent: true,
            lineNumbers: true,
            viewportMargin: Infinity,
            indentWithTabs: false
        });

        yamlEditor.on('change', _.debounce(function () {
            var yaml = yamlEditor.getValue();
            $.post('/ajax/infobox', {
                    yaml: yaml
                }, function(data) {
                    $('#infoboxPreview').html(data);
                });
            }, 800));
    </script>
@endsection
