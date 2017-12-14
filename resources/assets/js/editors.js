// CM Settings
CodeMirrorSpellChecker({
    codeMirrorInstance: CodeMirror,
});


// Simple MDE
window.markdownEditor = new SimpleMDE({
    element: document.getElementById("md-editor"),
    spellChecker: true,
});