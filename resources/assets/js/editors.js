// CM Settings
CodeMirrorSpellChecker({
    codeMirrorInstance: CodeMirror,
});


// Simple MDE
var markdownEditor = new SimpleMDE({
    element: document.getElementById("md-editor"),
    spellChecker: true,
});