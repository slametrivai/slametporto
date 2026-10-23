{{-- Contact form dialog. Any [data-open-contact] element opens it; used by layouts.public and the link-in-bio page. --}}
@php
    $field = 'mt-2 block h-11 w-full rounded-[0.625rem] border-edge bg-surface-inset px-3.5 text-[0.9375rem] text-content placeholder:text-content-faint focus:border-accent focus:shadow-[0_0_8px_rgba(242,184,75,0.2)] focus:ring-1 focus:ring-accent';
    $fieldLabel = 'text-[0.8125rem] font-medium text-content';
@endphp

<dialog id="contact-dialog" aria-labelledby="contact-dialog-title" x-data="contactForm()" @close="reset()" @click.self="$el.close()"
        class="w-[calc(100%-2rem)] rounded-[1.25rem] border border-edge bg-surface p-0 text-content shadow-[0_24px_64px_rgba(0,0,0,0.5)] backdrop:bg-void-deep/80 backdrop:backdrop-blur-[0.1875rem]"
        :class="submitted ? 'max-w-[30rem]' : 'max-w-[35rem]'">
    <div class="max-h-[90vh] overflow-y-auto">
        <form x-show="!submitted" @submit.prevent="submitForm" action="{{ route('contact.store') }}" method="POST" class="space-y-6 p-6 sm:p-8">
            @csrf
            <div class="flex items-start gap-4">
                <div class="flex-1">
                    <h2 id="contact-dialog-title" class="text-2xl font-bold tracking-[-0.02em] text-content">Let's talk operations</h2>
                    <p class="mt-1.5 text-[0.9375rem] leading-[1.4375rem] text-content-secondary">Tell me what you're working on: a role, a project or an advisory need. I'll reply by email.</p>
                </div>
                <button type="button" @click="$el.closest('dialog').close()" aria-label="Close contact form"
                        class="inline-flex h-11 w-11 shrink-0 items-center justify-center rounded-lg border border-edge text-content-secondary transition hover:text-content">
                    <x-lucide-x class="h-[1.125rem] w-[1.125rem]" />
                </button>
            </div>

            <div x-show="errorMessage" x-cloak role="alert" class="rounded-[0.625rem] border border-red-400/40 bg-red-500/10 p-3.5 text-sm text-red-200" x-text="errorMessage"></div>

            <div class="grid grid-cols-1 gap-3 sm:grid-cols-2">
                <div>
                    <label for="contact-name" class="{{ $fieldLabel }}">Name</label>
                    <input type="text" id="contact-name" name="name" x-model="formData.name" required autocomplete="name" maxlength="150" class="{{ $field }}" placeholder="e.g. Rina Kusuma">
                </div>
                <div>
                    <label for="contact-email" class="{{ $fieldLabel }}">Work email</label>
                    <input type="email" id="contact-email" name="email" x-model="formData.email" required autocomplete="email" maxlength="150" class="{{ $field }}" placeholder="name@company.co.id">
                </div>
            </div>
            <div>
                <label for="contact-company" class="{{ $fieldLabel }}">Company <span class="ml-1 text-xs font-normal text-content-faint">Optional</span></label>
                <input type="text" id="contact-company" name="company" x-model="formData.company" autocomplete="organization" maxlength="150" class="{{ $field }}" placeholder="e.g. PT Maju Bersama">
            </div>
            <div>
                <label for="contact-message" class="{{ $fieldLabel }}">Message</label>
                <textarea id="contact-message" name="message" rows="4" x-model="formData.message" required minlength="10" maxlength="5000" class="{{ $field }} h-28 py-3 leading-[1.4375rem]"
                          placeholder="What are you working on, and where is the bottleneck?"></textarea>
            </div>

            <div class="flex flex-col-reverse items-stretch gap-4 pt-1 sm:flex-row sm:items-center">
                <p class="flex flex-1 items-center gap-2 text-[0.8125rem] text-content-faint">
                    <x-lucide-lock class="h-3.5 w-3.5" />
                    Only used to reply to you.
                </p>
                <button type="submit" :disabled="loading" class="btn-primary px-[1.375rem] disabled:cursor-wait disabled:opacity-60">
                    <span x-text="loading ? 'Sending…' : 'Send message'">Send message</span>
                    <x-lucide-send class="h-4 w-4" />
                </button>
            </div>
        </form>

        <div x-show="submitted" x-cloak role="status" class="relative flex flex-col items-center gap-6 px-6 pb-8 pt-10 sm:px-10">
            <button type="button" @click="$el.closest('dialog').close()" aria-label="Close"
                    class="absolute right-5 top-5 inline-flex h-11 w-11 items-center justify-center rounded-lg border border-edge text-content-secondary transition hover:text-content">
                <x-lucide-x class="h-[1.125rem] w-[1.125rem]" />
            </button>
            <span class="flex h-16 w-16 items-center justify-center rounded-full border border-accent/30 bg-accent/10 text-accent">
                <x-lucide-check class="h-7 w-7" />
            </span>
            <div class="text-center">
                <h2 class="text-2xl font-bold tracking-[-0.02em] text-content">Message sent</h2>
                <p class="mt-2.5 text-[0.9375rem] leading-6 text-content-secondary">
                    Thanks, <span x-text="sent.firstName"></span>. Your message is in my inbox. I'll reply to <span class="break-all" x-text="sent.email"></span>.
                </p>
            </div>
            <dl class="flex w-full gap-3 rounded-xl border border-edge bg-surface-inset p-4 text-[0.8125rem]">
                <dt class="w-14 shrink-0 text-content-faint">From</dt>
                <dd class="min-w-0 break-words font-medium text-content" x-text="sent.name + ' · ' + sent.email"></dd>
            </dl>
            <button type="button" @click="$el.closest('dialog').close()" class="btn-primary w-full text-sm">{{ $closeLabel ?? 'Back to site' }}</button>
        </div>
    </div>
</dialog>

<script>
    // Any [data-open-contact] opens the dialog; its href (#contact) is the no-JS fallback.
    document.addEventListener('click', (e) => {
        if (!e.target.closest('[data-open-contact]')) return;
        e.preventDefault();
        document.getElementById('contact-dialog').showModal();
    });

    function contactForm() {
        const empty = () => ({ name: '', email: '', company: '', message: '' });

        return {
            formData: empty(),
            sent: { name: '', firstName: '', email: '' },
            loading: false,
            submitted: false,
            errorMessage: '',
            reset() {
                this.submitted = false;
                this.errorMessage = '';
            },
            submitForm() {
                this.loading = true;
                this.errorMessage = '';

                fetch(@js(route('contact.store')), {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': @js(csrf_token()),
                        'Accept': 'application/json',
                    },
                    body: JSON.stringify(this.formData),
                })
                .then(async (response) => {
                    const data = await response.json();
                    if (!response.ok) throw new Error(data.message || 'Your message could not be sent. Please try again.');
                    return data;
                })
                .then(() => {
                    const name = this.formData.name.trim();
                    this.sent = { name, firstName: name.split(/\s+/)[0], email: this.formData.email.trim() };
                    this.submitted = true;
                    this.formData = empty();
                })
                .catch((err) => { this.errorMessage = err.message; })
                .finally(() => { this.loading = false; });
            },
        };
    }
</script>
