{{-- Closing contact panel shared by the home, case study and writing pages. --}}
<section id="contact" class="page-container pb-20 sm:pb-[7.5rem]" aria-labelledby="cta-title">
    <div class="glow-corner flex flex-col gap-8 rounded-3xl border border-accent/25 bg-surface p-8 sm:p-12 lg:flex-row lg:items-center lg:gap-16 lg:p-16">
        <div class="flex-1">
            <h2 id="cta-title" class="section-title">{{ $title ?? 'Planning an ops restructure, CRM overhaul or automation project?' }}</h2>
            <p class="mt-[1.125rem] max-w-[35rem] text-[1.0625rem] leading-[1.6875rem] text-content-secondary">Tell me where the bottleneck is — send a message and I'll reply by email.</p>
        </div>
        <div class="w-full lg:w-[21.25rem]">
            <a href="#contact" data-open-contact aria-haspopup="dialog" class="btn-primary min-h-[3.25rem] w-full">
                Send an inquiry
                <x-lucide-arrow-right class="h-[1.125rem] w-[1.125rem]" />
            </a>
        </div>
    </div>
</section>
