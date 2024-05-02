import TgMdEditor from "@/Components/Markdown/Core/Core.js";
import MarkdownIt from "markdown-it";
import hljs from "highlight.js";
import SpoilerPlugin from "@/Components/Markdown/Plugins/SpoilerPlugin.jsx";
import 'react-markdown-editor-lite/lib/index.css';
import 'highlight.js/styles/default.css';
import './tgMarkdownEditor.css';
import markdownItRegex from 'markdown-it-regex'
import {memo} from "react";

const mdParser = new MarkdownIt({
    breaks: true,
    highlight: function (str, lang) {
        if (lang && hljs.getLanguage(lang)) {
            try {
                return '<pre class="hljs"><code>' +
                    hljs.highlight(lang, str, true).value +
                    '</code></pre>';
            } catch (__) {}
        }
        return '<pre class="hljs"><code>' + mdParser.utils.escapeHtml(str) + '</code></pre>';
    },
});

mdParser.disable(['emphasis']);

mdParser.core.ruler.push('spoiler', function (state) {
    // Перебираем все токены
    for (let i = 0; i < state.tokens.length; i++) {
        const token = state.tokens[i];
        // Проверяем, является ли текущий токен текстовым и содержит ли он символы ||
        if (token.type === 'inline' && token.children && token.children.length) {
            for (let j = 0; j < token.children.length; j++) {
                const childToken = token.children[j];
                if (childToken.type === 'text' && childToken.content.match(/\|\|.*?\|\|/)) {
                    // Обработка токена, содержащего текст внутри ||
                    const newText = childToken.content.replace(/\|\|(.+?)\|\|/g, '<span class="spoiler-wrapper" onClick="this.classList.toggle(\'active\')">$1</span>');
                    // Заменяем текстовый токен на html-токен с вставленной разметкой
                    token.children[j] = new state.Token('html_block', '', 0);
                    token.children[j].content = newText;
                }
            }
        }
    }
});

mdParser.use(markdownItRegex,{
    name: 'telegram-bold',
    regex: /\*([^*]+)\*/,
    replace: (match) => {
        return `<strong>${match}</strong>`
    }
});
const processTelegramMarkup = (text) => {
    let result = '';
    let i = 0;
    while (i < text.length) {
        if (text[i] === '_' && text[i+1] === '_') {
            let endIndex = text.indexOf('__', i+2);
            if (endIndex !== -1) {
                result += `<u>${text.substring(i+2, endIndex)}</u>`;
                i = endIndex + 2;
                continue;
            }
        }
        if (text[i] === '_') {
            let endIndex = text.indexOf('_', i+1);
            if (endIndex !== -1) {
                result += `<em>${text.substring(i+1, endIndex)}</em>`;
                i = endIndex + 1;
                continue;
            }
        }
        result += text[i];
        i++;
    }
    return result;
};

mdParser.use((md) => {
    md.core.ruler.push('telegram-markup', (state) => {
        state.tokens.forEach((token) => {
            if (token.type === 'inline') {
                token.children.forEach((child) => {
                    if (child.type === 'text') {
                        child.content = processTelegramMarkup(child.content);
                    }
                });
            }
        });
    });

    md.renderer.rules.text = (tokens, idx) => {
        return tokens[idx].content;
    };
});

/*mdParser.use(markdownItRegex,{
    name: 'telegram-italic',
    regex: /_([^*]+)_/,
    replace: (match) => {
        return `<em>${match}</em>`
    }
});

mdParser.use(markdownItRegex,{
    name: 'telegram-underline',
    regex: /__([^*]+)__/,
    replace: (match) => {
        return `<u>${match}</u>`
    }
});*/

mdParser.use(markdownItRegex,{
    name: 'telegram-strike',
    regex: /~([^*]+)~/,
    replace: (match) => {
        return `<s>${match}</s>`
    }
});

TgMdEditor.use(SpoilerPlugin);

export { mdParser };

export const TgMarkdownEditor = memo(({value, onChange}) => {

    return (
        <TgMdEditor
            style={{height: '100vh'}}
            renderHTML={text => mdParser.render(text)}
            value={value}
            onChange={onChange}
            plugins={['font-bold', 'font-italic', 'font-underline', 'font-strikethrough', 'spoiler', 'block-quote', 'block-code-inline', 'block-code-block', 'link', 'clear', 'logger', 'mode-toggle', 'full-screen', 'auto-resize', 'tab-insert']}
            shortcuts={true}
        />
    )
})
