{{-- Contact form dialog. Any [data-open-contact] element opens it; used by layouts.public and the link-in-bio page. --}}
@php
    $field = 'mt-2 block h-12 w-full rounded-[0.875rem] border-line bg-white px-3.5 text-[0.9375rem] text-navy placeholder:text-body focus:border-brand focus:shadow-[0_0_8px_rgba(1,118,211,0.2)] focus:ring-1 focus:ring-brand';
    $fieldLabel = 'text-[0.9375rem] font-bold text-navy';
@endphp

<dialog id="contact-dialog" aria-labelledby="contact-dialog-title" x-data="contactForm()" @close="reset()" @click.self="$el.close()"
        class="w-[calc(100%-2rem)] max-w-[37.5rem] rounded-[1.75rem] bg-white p-0 text-body shadow-[0_20px_60px_rgba(3,35,77,0.3)] backdrop:bg-ink/70 backdrop:backdrop-blur-[0.1875rem]">
    <div class="max-h-[90vh] overflow-y-auto">
        <form x-show="!submitted" @submit.prevent="submitForm" action="{{ route('contact.store') }}" method="POST" class="flex flex-col gap-5 p-6 sm:p-9">
            @csrf
            <div class="flex items-start justify-between gap-4">
                <div class="flex-1">
                    <h2 id="contact-dialog-title" class="text-[1.75rem] font-semibold leading-tight text-navy sm:text-[2.125rem]">Let's talk operations</h2>
                    <p class="mt-1.5 text-base leading-6 text-body">Tell me what you're working on: a role, a project or an advisory need. I'll reply by email.</p>
                </div>
                <button type="button" @click="$el.closest('dialog').close()" aria-label="Close contact form"
                        class="inline-flex h-11 w-11 shrink-0 items-center justify-center rounded-full bg-panel text-navy transition-colors hover:bg-line">
                    <x-lucide-x class="h-[1.125rem] w-[1.125rem]" />
                </button>
            </div>

            <div x-show="errorMessage" x-cloak role="alert" class="rounded-[0.875rem] border border-red-300 bg-red-50 p-3.5 text-sm font-medium text-red-800" x-text="errorMessage"></div>

            <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
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
                <label for="contact-company" class="flex items-center justify-between {{ $fieldLabel }}">Company <span class="text-sm font-normal text-body">Optional</span></label>
                <input type="text" id="contact-company" name="company" x-model="formData.company" autocomplete="organization" maxlength="150" class="{{ $field }}" placeholder="e.g. PT Maju Bersama">
            </div>
            <div>
                <label for="contact-message" class="{{ $fieldLabel }}">Message</label>
                <textarea id="contact-message" name="message" rows="4" x-model="formData.message" required minlength="10" maxlength="5000" class="{{ $field }} h-[7.5rem] py-3 leading-[1.4375rem]"
                          placeholder="What are you working on, and where is the bottleneck?"></textarea>
            </div>

            <div class="flex flex-col-reverse items-stretch gap-4 sm:flex-row sm:items-center">
                <p class="flex flex-1 items-center justify-center gap-2 text-[0.9375rem] text-body sm:justify-start">
                    <x-lucide-lock class="h-3.5 w-3.5" />
                    Only used to reply to you.
                </p>
                <button type="submit" :disabled="loading" class="btn-primary disabled:cursor-wait disabled:opacity-60">
                    <span x-text="loading ? 'Sending…' : 'Send message'">Send message</span>
                    <x-lucide-send class="h-4 w-4" />
                </button>
            </div>
        </form>

        <div x-show="submitted" x-cloak role="status" class="relative flex flex-col items-center gap-3.5 px-6 py-10 sm:px-10 sm:py-12">
            <button type="button" @click="$el.closest('dialog').close()" aria-label="Close"
                    class="absolute right-5 top-5 inline-flex h-11 w-11 items-center justify-center rounded-full bg-panel text-navy transition-colors hover:bg-line">
                <x-lucide-x class="h-[1.125rem] w-[1.125rem]" />
            </button>
            <span class="flex h-[4.5rem] w-[4.5rem] items-center justify-center rounded-full bg-brand/[0.12] text-brand">
                <x-lucide-check class="h-8 w-8" />
            </span>
            <h2 class="text-[1.75rem] font-semibold text-navy sm:text-[2.125rem]">Message sent</h2>
            <p class="text-center text-[1.0625rem] leading-[1.625rem] text-body">
                Thanks, <span x-text="sent.firstName"></span>. Your message is in my inbox. I'll reply to <span class="break-all" x-text="sent.email"></span>.
            </p>
            <dl class="flex w-full justify-center gap-1.5 rounded-2xl bg-panel px-4 py-3 text-[0.9375rem]">
                <dt class="shrink-0 text-body">From</dt>
                <dd class="min-w-0 break-words font-semibold text-navy" x-text="sent.from"></dd>
            </dl>
            <button type="button" @click="$el.closest('dialog').close()" class="btn-primary">{{ $closeLabel ?? 'Back to site' }}</button>
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
            sent: { from: '', firstName: '', email: '' },
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
                    const company = this.formData.company.trim();
                    this.sent = { from: company ? name + ' · ' + company : name, firstName: name.split(/\s+/)[0], email: this.formData.email.trim() };
                    this.submitted = true;
                    this.formData = empty();
                })
                .catch((err) => { this.errorMessage = err.message; })
                .finally(() => { this.loading = false; });
            },
        };
    }
</script>
