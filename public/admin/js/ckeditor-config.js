/**
 * CKEditor Configuration for Admin Forms
 * Provides consistent CKEditor setup across different forms
 */

class CKEditorManager {
    constructor() {
        this.editors = {};
        this.defaultConfig = {
            toolbar: {
                items: [
                    'heading', '|',
                    'bold', 'italic', 'underline', 'strikethrough', '|',
                    'bulletedList', 'numberedList', '|',
                    'outdent', 'indent', '|',
                    'link', 'blockQuote', '|',
                    'insertTable', 'horizontalLine', '|',
                    'undo', 'redo', '|',
                    'fontSize', 'fontColor', 'fontBackgroundColor', '|',
                    'alignment', '|',
                    'removeFormat', 'sourceEditing'
                ],
                shouldNotGroupWhenFull: true
            },
            language: 'en',
            image: {
                toolbar: [
                    'imageTextAlternative',
                    'imageStyle:inline',
                    'imageStyle:block',
                    'imageStyle:side'
                ]
            },
            table: {
                contentToolbar: [
                    'tableColumn',
                    'tableRow',
                    'mergeTableCells',
                    'tableCellProperties',
                    'tableProperties'
                ]
            },
            licenseKey: '',
            placeholder: 'Enter your content here...',
            removePlugins: [
                'CKBox',
                'CKFinder',
                'EasyImage',
                'RealTimeCollaborativeComments',
                'RealTimeCollaborativeTrackChanges',
                'RealTimeCollaborativeRevisionHistory',
                'PresenceList',
                'Comments',
                'TrackChanges',
                'TrackChangesData',
                'RevisionHistory',
                'Pagination',
                'WProofreader',
                'MathType'
            ]
        };

        this.auctionConfig = {
            ...this.defaultConfig,
            toolbar: {
                items: [
                    'heading', '|',
                    'bold', 'italic', 'underline', '|',
                    'bulletedList', 'numberedList', '|',
                    'link', 'blockQuote', '|',
                    'insertTable', '|',
                    'undo', 'redo', '|',
                    'fontSize', 'fontColor', '|',
                    'alignment', '|',
                    'removeFormat'
                ],
                shouldNotGroupWhenFull: true
            },
            placeholder: 'Describe the auction item in detail. Include condition, provenance, dimensions, and any notable features...',
            height: 300
        };

        this.newsConfig = {
            ...this.defaultConfig,
            toolbar: {
                items: [
                    'exportPDF','exportWord', '|',
                    'findAndReplace', 'selectAll', '|',
                    'heading', '|',
                    'bold', 'italic', 'strikethrough', 'underline', 'code', 'subscript', 'superscript', 'removeFormat', '|',
                    'bulletedList', 'numberedList', 'todoList', '|',
                    'outdent', 'indent', '|',
                    'undo', 'redo',
                    '-',
                    'fontSize', 'fontFamily', 'fontColor', 'fontBackgroundColor', 'highlight', '|',
                    'alignment', '|',
                    'link', 'insertImage', 'blockQuote', 'insertTable', 'mediaEmbed', 'codeBlock', 'htmlEmbed', '|',
                    'specialCharacters', 'horizontalLine', 'pageBreak', '|',
                    'textPartLanguage', '|',
                    'sourceEditing'
                ],
                shouldNotGroupWhenFull: true
            },
            placeholder: 'Write your news article content here...',
            height: 500
        };
    }

    /**
     * Initialize CKEditor for auction lot descriptions
     */
    async initAuctionEditor(selector, options = {}) {
        const config = { ...this.auctionConfig, ...options };
        
        try {
            const editor = await ClassicEditor.create(document.querySelector(selector), config);
            this.editors[selector] = editor;
            
            // Add custom styling
            this.addAuctionEditorStyles(editor);
            
            // Add event listeners
            this.addAuctionEditorListeners(editor, selector);
            
            return editor;
        } catch (error) {
            console.error('Error initializing auction editor:', error);
            throw error;
        }
    }

    /**
     * Initialize CKEditor for news content
     */
    async initNewsEditor(selector, options = {}) {
        const config = { ...this.newsConfig, ...options };
        
        try {
            const editor = await ClassicEditor.create(document.querySelector(selector), config);
            this.editors[selector] = editor;
            
            // Add custom styling
            this.addNewsEditorStyles(editor);
            
            // Add event listeners
            this.addNewsEditorListeners(editor, selector);
            
            return editor;
        } catch (error) {
            console.error('Error initializing news editor:', error);
            throw error;
        }
    }

    /**
     * Add custom styles for auction editor
     */
    addAuctionEditorStyles(editor) {
        const editorElement = editor.ui.view.editable.element;
        if (editorElement) {
            editorElement.style.minHeight = '200px';
            editorElement.style.fontSize = '14px';
            editorElement.style.lineHeight = '1.6';
        }
    }

    /**
     * Add custom styles for news editor
     */
    addNewsEditorStyles(editor) {
        const editorElement = editor.ui.view.editable.element;
        if (editorElement) {
            editorElement.style.minHeight = '400px';
            editorElement.style.fontSize = '14px';
            editorElement.style.lineHeight = '1.6';
        }
    }

    /**
     * Add event listeners for auction editor
     */
    addAuctionEditorListeners(editor, selector) {
        // Auto-save functionality
        let autoSaveTimeout;
        editor.model.document.on('change:data', () => {
            clearTimeout(autoSaveTimeout);
            autoSaveTimeout = setTimeout(() => {
                this.updateTextarea(selector, editor.getData());
            }, 1000);
        });

        // Validation on blur
        editor.ui.focusTracker.on('change:isFocused', (evt, name, isFocused) => {
            if (!isFocused) {
                this.validateAuctionContent(editor.getData(), selector);
            }
        });
    }

    /**
     * Add event listeners for news editor
     */
    addNewsEditorListeners(editor, selector) {
        // Auto-save functionality
        let autoSaveTimeout;
        editor.model.document.on('change:data', () => {
            clearTimeout(autoSaveTimeout);
            autoSaveTimeout = setTimeout(() => {
                this.updateTextarea(selector, editor.getData());
            }, 1000);
        });

        // Word count display
        this.addWordCountDisplay(editor, selector);
    }

    /**
     * Update the original textarea with editor content
     */
    updateTextarea(selector, content) {
        const textarea = document.querySelector(selector);
        if (textarea) {
            textarea.value = content;
            // Trigger change event for form validation
            textarea.dispatchEvent(new Event('change'));
        }
    }

    /**
     * Validate auction content
     */
    validateAuctionContent(content, selector) {
        const plainText = this.stripHtml(content);
        const wordCount = plainText.split(/\s+/).filter(word => word.length > 0).length;
        
        // Remove existing validation messages
        const existingMessage = document.querySelector(`${selector}-validation`);
        if (existingMessage) {
            existingMessage.remove();
        }

        let message = '';
        let messageClass = 'text-success';

        if (wordCount < 10) {
            message = `Description is too short (${wordCount} words). Consider adding more details about the item.`;
            messageClass = 'text-warning';
        } else if (wordCount < 5) {
            message = `Description is required. Please provide details about the auction item.`;
            messageClass = 'text-danger';
        } else if (wordCount > 500) {
            message = `Description is quite long (${wordCount} words). Consider making it more concise.`;
            messageClass = 'text-info';
        } else {
            message = `Good description length (${wordCount} words).`;
        }

        // Add validation message
        const editorContainer = document.querySelector(selector).closest('.form-group');
        if (editorContainer && message) {
            const messageDiv = document.createElement('div');
            messageDiv.id = `${selector.replace('#', '')}-validation`;
            messageDiv.className = `form-text ${messageClass}`;
            messageDiv.innerHTML = `<i class="fas fa-info-circle me-1"></i>${message}`;
            editorContainer.appendChild(messageDiv);
        }
    }

    /**
     * Add word count display for news editor
     */
    addWordCountDisplay(editor, selector) {
        const editorContainer = document.querySelector(selector).closest('.form-group');
        if (editorContainer) {
            const wordCountDiv = document.createElement('div');
            wordCountDiv.className = 'word-count-display text-muted mt-2';
            wordCountDiv.innerHTML = '<small><i class="fas fa-file-word me-1"></i>Words: <span class="word-count">0</span> | Characters: <span class="char-count">0</span></small>';
            editorContainer.appendChild(wordCountDiv);

            // Update word count on content change
            editor.model.document.on('change:data', () => {
                const content = editor.getData();
                const plainText = this.stripHtml(content);
                const wordCount = plainText.split(/\s+/).filter(word => word.length > 0).length;
                const charCount = plainText.length;

                const wordCountSpan = wordCountDiv.querySelector('.word-count');
                const charCountSpan = wordCountDiv.querySelector('.char-count');
                
                if (wordCountSpan) wordCountSpan.textContent = wordCount;
                if (charCountSpan) charCountSpan.textContent = charCount;
            });
        }
    }

    /**
     * Strip HTML tags from content
     */
    stripHtml(html) {
        const tmp = document.createElement('div');
        tmp.innerHTML = html;
        return tmp.textContent || tmp.innerText || '';
    }

    /**
     * Get editor instance by selector
     */
    getEditor(selector) {
        return this.editors[selector];
    }

    /**
     * Destroy editor instance
     */
    destroyEditor(selector) {
        if (this.editors[selector]) {
            this.editors[selector].destroy();
            delete this.editors[selector];
        }
    }

    /**
     * Destroy all editors
     */
    destroyAllEditors() {
        Object.keys(this.editors).forEach(selector => {
            this.destroyEditor(selector);
        });
    }

    /**
     * Get content from editor
     */
    getContent(selector) {
        const editor = this.editors[selector];
        return editor ? editor.getData() : '';
    }

    /**
     * Set content in editor
     */
    setContent(selector, content) {
        const editor = this.editors[selector];
        if (editor) {
            editor.setData(content);
        }
    }

    /**
     * Validate all editors before form submission
     */
    validateAllEditors() {
        let isValid = true;
        const errors = [];

        Object.keys(this.editors).forEach(selector => {
            const editor = this.editors[selector];
            const content = editor.getData();
            const plainText = this.stripHtml(content);

            if (selector.includes('description') && plainText.trim().length < 10) {
                errors.push('Auction description must be at least 10 words long.');
                isValid = false;
            }

            if (selector.includes('content') && plainText.trim().length < 50) {
                errors.push('News content must be at least 50 words long.');
                isValid = false;
            }

            // Update textarea with current content
            this.updateTextarea(selector, content);
        });

        return { isValid, errors };
    }
}

// Global instance
window.ckEditorManager = new CKEditorManager();

// Auto-initialize on DOM ready
document.addEventListener('DOMContentLoaded', function() {
    // Auto-detect and initialize editors based on data attributes
    const auctionDescriptions = document.querySelectorAll('[data-ckeditor="auction"]');
    const newsContent = document.querySelectorAll('[data-ckeditor="news"]');

    auctionDescriptions.forEach(element => {
        window.ckEditorManager.initAuctionEditor(`#${element.id}`);
    });

    newsContent.forEach(element => {
        window.ckEditorManager.initNewsEditor(`#${element.id}`);
    });
});
