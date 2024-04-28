/**
 * THIS IS A HACK FOR A LIBRARY react-markdown-editor-lite
 * this hack allows you to redefine the symbols that are needed for the telegram MD editor
 */
import Editor, {PluginComponent} from "react-markdown-editor-lite";
import {getLineAndCol, repeat} from "react-markdown-editor-lite/cjs/utils/tool.js";

const SIMPLE_DECORATOR = {
    bold: ['*', '*'],
    italic: ['_', '_'],
    underline: ['__', '__'],
    strikethrough: ['~', '~'],
    quote: ['\n> ', '\n'],
    inlinecode: ['`', '`'],
    code: ['\n```\n', '\n```\n'],
    spoiler: ['||', '||'],
};

for (let i = 1; i <= 6; i++) {
    SIMPLE_DECORATOR[`h${i}`] = [`\n${repeat('#', i)} `, '\n'];
}


function decorateTableText(option) {
    const { row = 2, col = 2 } = option;
    const rowHeader = ['|'];
    const rowData = ['|'];
    const rowDivision = ['|'];
    let colStr = '';
    for (let i = 1; i <= col; i++) {
        rowHeader.push(' Head |');
        rowDivision.push(' --- |');
        rowData.push(' Data |');
    }
    for (let j = 1; j <= row; j++) {
        colStr += '\n' + rowData.join('');
    }
    return `${rowHeader.join('')}\n${rowDivision.join('')}${colStr}`;
}

function decorateList(type, target) {
    let text = target;
    if (text.substr(0, 1) !== '\n') {
        text = '\n' + text;
    }
    if (type === 'unordered') {
        return text.length > 1 ? text.replace(/\n/g, '\n* ').trim() : '* ';
    } else {
        let count = 1;
        if (text.length > 1) {
            return text
                .replace(/\n/g, () => {
                    return `\n${count++}. `;
                })
                .trim();
        } else {
            return '1. ';
        }
    }
}

function createTextDecorated(text, newBlock) {
    return {
        text,
        newBlock,
        selection: {
            start: text.length,
            end: text.length,
        },
    };
}


function getDecorated(target, type, option = {}) {
    if (typeof SIMPLE_DECORATOR[type] !== 'undefined') {
        return {
            text: `${SIMPLE_DECORATOR[type][0]}${target}${SIMPLE_DECORATOR[type][1]}`,
            selection: {
                start: SIMPLE_DECORATOR[type][0].length,
                end: SIMPLE_DECORATOR[type][0].length + target.length,
            },
        };
    }
    switch (type) {
        case 'tab':
            const inputValue = option.tabMapValue === 1 ? '\t' : ' '.repeat(option.tabMapValue);
            const newSelectedText = inputValue + target.replace(/\n/g, `\n${inputValue}`);
            const lineBreakCount = target.includes('\n') ? target.match(/\n/g).length : 0;
            return {
                text: newSelectedText,
                selection: {
                    start: option.tabMapValue,
                    end: option.tabMapValue * (lineBreakCount + 1) + target.length,
                },
            };
        case 'unordered':
            return createTextDecorated(decorateList('unordered', target), true);
        case 'order':
            return createTextDecorated(decorateList('order', target), true);
        case 'hr':
            return createTextDecorated('---', true);
        case 'table':
            return {
                text: decorateTableText(option),
                newBlock: true,
            };
        case 'image':
            return {
                text: `![${target || option.target}](${option.imageUrl || ''})`,
                selection: {
                    start: 2,
                    end: target.length + 2,
                },
            };
        case 'link':
            return {
                text: `[${target}](${option.linkUrl || ''})`,
                selection: {
                    start: 1,
                    end: target.length + 1,
                },
            };
    }
    return {
        text: target,
        selection: {
            start: 0,
            end: target.length,
        },
    };
}

export default class TgMdEditor extends Editor {
    insertMarkdown(type, option) {
        const curSelection = this.getSelection();
        let decorateOption = option ? { ...option } : {};
        if (type === 'image') {
            decorateOption = {
                ...decorateOption,
                target: option.target || curSelection.text || '',
                imageUrl: option.imageUrl || this.config.imageUrl,
            };
        }
        if (type === 'link') {
            decorateOption = {
                ...decorateOption,
                linkUrl: this.config.linkUrl,
            };
        }
        if (type === 'tab' && curSelection.start !== curSelection.end) {
            const curLineStart = this.getMdValue()
                .slice(0, curSelection.start)
                .lastIndexOf('\n') + 1;
            this.setSelection({
                start: curLineStart,
                end: curSelection.end,
            });
        }
        const decorate = getDecorated(curSelection.text, type, decorateOption);
        let { text } = decorate;
        const { selection } = decorate;
        if (decorate.newBlock) {
            const startLineInfo = getLineAndCol(this.getMdValue(), curSelection.start);
            const { col, curLine } = startLineInfo;
            if (col > 0 && curLine.length > 0) {
                text = `\n${text}`;
                if (selection) {
                    selection.start++;
                    selection.end++;
                }
            }
            let { afterText } = startLineInfo;
            if (curSelection.start !== curSelection.end) {
                afterText = getLineAndCol(this.getMdValue(), curSelection.end).afterText;
            }
            if (afterText.trim() !== '' && afterText.substr(0, 2) !== '\n\n') {
                if (afterText.substr(0, 1) !== '\n') {
                    text += '\n';
                }
                text += '\n';
            }
        }
        this.insertText(text, true, selection);
    }
}
