function initWysiwygEditors() {
    if (typeof window.Jodit === 'undefined') {
        return;
    }

    document.querySelectorAll('[data-wysiwyg]').forEach((textarea) => {
        new window.Jodit(textarea, {
            height: 360,
            toolbarAdaptive: false,
            buttons: [
                'bold', 'italic', 'underline', '|',
                'ul', 'ol', '|',
                'paragraph', '|',
                'codeBlock', '|',
                'link', 'image', '|',
                'align', '|',
                'undo', 'redo', '|',
                'source',
            ],
            controls: {
                // Add "Code block" to the block-format dropdown as well, so it
                // can be applied to an existing paragraph.
                paragraph: {
                    list: window.Jodit.atom({
                        p: 'Normal',
                        h1: 'Heading 1',
                        h2: 'Heading 2',
                        h3: 'Heading 3',
                        h4: 'Heading 4',
                        blockquote: 'Quote',
                        pre: 'Code block',
                    }),
                },
                codeBlock: {
                    tooltip: 'Insert code block',
                    icon: 'source',
                    // formatBlock is a stable, long-standing command; it wraps
                    // the current block in <pre>, which the sanitizer allows.
                    exec: (editor) => {
                        editor.execCommand('formatBlock', false, 'pre');
                    },
                },
            },
        });
    });
}

document.addEventListener('DOMContentLoaded', initWysiwygEditors);
