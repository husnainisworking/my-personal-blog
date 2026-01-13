import { Editor } from '@tiptap/core'
import StarterKit from '@tiptap/starter-kit'
import Placeholder from '@tiptap/extension-placeholder'
import Image from '@tiptap/extension-image'
import Link from '@tiptap/extension-link'
import { Table } from '@tiptap/extension-table'
import { TableRow } from '@tiptap/extension-table-row'
import { TableCell } from '@tiptap/extension-table-cell'
import { TableHeader } from '@tiptap/extension-table-header'
import { CodeBlockLowlight } from '@tiptap/extension-code-block-lowlight'
import { createLowlight, common } from 'lowlight'

// Create low-light instance with common languages
const lowlight = createLowlight(common)


// This function creates and configures the editor
export function initTiptapEditor(element, initialContent = '') {
    const editor = new Editor({
        element: element, // Where to render the editor
        extensions: [
            StarterKit.configure({
                codeBlock: false, // We'll use lowlight version instead
            }),
            Placeholder.configure({
                placeholder: 'Type / for commands...', // Shows hint text
            }),
            Image.configure({
                HTMLAttributes: {
                    class: 'max-w-full h-auto rounded-lg', // Style images
                },
            }),
            Link.configure({
                openOnClick: false, // Don't follow links while editing
                HTMLAttributes: {
                    class: 'text-indigo-600 hover:text-indigo-800 underline',
                },
            }),
            Table.configure({
                resizable: true, // Allow resizing table columns
            }),
            TableRow,
            TableCell,
            TableHeader,
            CodeBlockLowlight.configure({
                lowlight, // Adds syntax highlighting to code blocks
            }),
        ],
        content: initialContent, // Load existing content
        editorProps: {
            attributes: {
                class: 'tiptap',

            },
        },
    })

    return editor
}