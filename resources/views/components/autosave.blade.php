@props(['draftKey' => null, 'postId' => null])

<div x-data="autosave('{{$draftKey}}', {{ $postId ?? 'null' }})" x-init="init()">
    <!-- Recovery Banner -->
     <div x-show="showRecoveryBanner"
        x-cloak
        class="fixed top-20 left-1/2 transform -translate-x-1/2 bg-indigo-600 text-white px-6 py-3 rounded-lg shadow-lg z-50 flex items-center gap-4">
        <span x-text="`Draft found from ${new Date(draftData?.updated_at).toLocaleString()}`"></span>
        <button type="button" @click="recoverDraft()" class="bg-white text-indigo-600 px-4 py-1 rounded font-medium hover:bg-gray-100">
            Recover
</button>
        <button type="button" @click="discardDraft()" class="bg-indigo-700 px-4 py-1 rounded font-medium hover:bg-indigo-800">
            Discard
</button>
</div>

    <!-- Save Indicator -->
     <div x-show="showIndicator"
        x-cloak
        class="fixed bottom-4 right-4 px-4 py-2 rounded-lg bg-white shadow-lg text-sm"
        :class="indicatorClass">
        <span x-text="indicatorText"></span>
</div>
</div>

<script>
    document.addEventListener('alpine:init', ()=>{
        Alpine.data('autosave', (initialDraftKey, postId) => ({
            draftKey: initialDraftKey || `draft_${Date.now()}_${Math.random().toString(36).substr(2, 9)}`,
            postId: postId,
            showRecoveryBanner: false,
            draftData: null,
            showIndicator: false,
            indicatorText: '',
            indicatorClass: 'text-gray-500',
            autosaveTimer: null,
            isRecovering: false,
            AUTOSAVE_INTERVAL: 5000, // 5 seconds

            init() {
                this.checkForExistingDraft()
                this.setupAutosave()
                this.attachTiptapListener()
            },

                setupAutosave() {
                    const form = this.$el.closest('form')
                    if (!form) return

                    // Setup autosave on form inputs
                    const inputs = form.querySelectorAll('input, textarea, select')
                    inputs.forEach(input => {
                        input.addEventListener('input', () => {
                            this.scheduleAutosave()
                        })
                    })


                    // Delete draft on successful submission
                    form.addEventListener('submit', () => {
                        this.deleteDraft()
                    })
                },

                attachTiptapListener() {
                    if (window.tiptapEditor) {
                        window.tiptapEditor.on('update', () => {
                            if(!this.isRecovering) this.scheduleAutosave() 
                        })
                        return
                    }
                    setTimeout(() => this.attachTiptapListener(), 300)
                },



                scheduleAutosave() {
                    clearTimeout(this.autosaveTimer)
                    
                    this.autosaveTimer = setTimeout(() => this.saveDraft(), this.AUTOSAVE_INTERVAL)
                },

                async saveDraft() {
                    this.showSaving()
                    const form = this.$el.closest('form')
                    const formData = new FormData(form)

                    const data = {
                        draft_key: this.draftKey,
                        post_id: this.postId,
                        title: formData.get('title'),
                        excerpt: formData.get('excerpt'),
                        content:formData.get('content'),
                        category_id: formData.get('category_id'),
                        status: formData.get('status'),
                        tags: formData.getAll('tags[]'),
                    }

                    try {
                        const response = await fetch('/drafts/autosave', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                            },
                            body: JSON.stringify(data),
                        })

                        const result = await response.json()
                        if (result.success) {
                            this.showSaved(result.saved_at)
                        }
                    } catch(error) {
                        console.error('Autosave failed:', error)
                    }
                },

                async checkForExistingDraft() {
                    try {
                        const response = await fetch(`/drafts/load?draft_key=${this.draftKey}`)
                        const result = await response.json()

                        if (result.success && result.draft) {
                            this.draftData = result.draft
                            this.showRecoveryBanner = true
                        }
                    } catch(error) {
                        // No draft found
                    }
                },

                recoverDraft() {
                    const form = this.$el.closest('form')
                    const draft = this.draftData 

                    if (draft.title) form.querySelector('[name="title"]').value= draft.title
                    if (draft.excerpt) form.querySelector('[name="excerpt"]').value = draft.excerpt
                    if (draft.content) {
                        form.querySelector('[name="content"]').value = draft.content
                        if (window.tiptapEditor) {
                            this.isRecovering = true
                            window.tiptapEditor.commands.setContent(draft.content)
                            setTimeout(() => { this.isRecovering = false }, 100)
                        }
                    }
                    if (draft.category_id) form.querySelector('[name="category_id"]').value = draft.category_id
                    if (draft.status) form.querySelector('[name="status"]').value = draft.status
                    if (draft.tags) {
                        draft.tags.forEach(tagId => {
                            const checkbox = form.querySelector(`[name="tags[]"][value="${tagId}"]`)
                            if (checkbox) checkbox.checked = true
                        })
                    }

                    this.showRecoveryBanner = false
                    this.showRecovered()
                },

                discardDraft() {
                    this.deleteDraft()
                    this.showRecoveryBanner = false
                },

                async deleteDraft() {
                    try {
                        await fetch('/drafts/delete', {
                            method: 'DELETE',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                            },
                            body: JSON.stringify({draft_key: this.draftKey}),
                        })
                    } catch(error) {
                        console.error('Delete draft failed:', error)
                    }
                },

                showSaving() {
                    this.indicatorText = 'Saving...'
                    this.indicatorClass =  'text-gray-500'
                    this.showIndicator = true
                },

                showSaved(time) {
                    this.indicatorText = `Saved at ${time}`
                    this.indicatorClass = 'text-green-600'
                    this.showIndicator = true
                },

                showRecovered() {
                    this.indicatorText = 'Draft recovered'
                    this.indicatorClass = 'text-green-600'
                    this.showIndicator = true
                    setTimeout(() => {this.showIndicator = false}, 3000)
                },
            }))
        })
        </script>
    