import Paragraph from "@editorjs/paragraph";
import Link from "@editorjs/link";
import Code from "@editorjs/code";
import Underline from '@editorjs/underline';

export const EDITOR_JS_TOOLS = {
    paragraph: {
        class: Paragraph,
        inlineToolbar: true,
    },
    link: Link,
    code: Code,
    underline: Underline,
};
