import './bootstrap';
import './search';

import Alpine from 'alpinejs';
window.Alpine = Alpine;
Alpine.start();





import { initTiptapEditor } from './components/tiptap-editor'

// Make it available globally so Blade templates can use it
window.initTiptapEditor = initTiptapEditor

