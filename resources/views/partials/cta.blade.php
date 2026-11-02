{{-- Closing contact panel shared by the home and writing pages: portrait on a blue block, copy beside it. --}}
<div class="px-3 pb-12 sm:px-5 lg:px-10 lg:pb-24">
    <section id="contact" class="flex flex-col gap-5 rounded-[2rem] bg-panel p-5 py-8 lg:flex-row lg:items-center lg:gap-20 lg:rounded-[3rem] lg:px-20 lg:py-16" aria-labelledby="cta-title">
        <div class="relative mx-auto h-80 w-full max-w-[20.625rem] shrink-0 lg:h-[35rem] lg:w-[32.5rem] lg:max-w-none" aria-hidden="true">
            <div class="absolute bottom-0 left-1/2 h-[16.25rem] w-[18.375rem] -translate-x-1/2 rounded-[3.75rem] bg-brand lg:h-[26.875rem] lg:w-[26.25rem] lg:rounded-[5.625rem]"></div>
            <img src="{{ asset('images/slamet-rivai-cutout.png') }}" alt="" loading="lazy" class="absolute inset-x-0 bottom-0 h-full w-full object-contain object-bottom lg:h-[32.5rem]">
        </div>
        <div class="flex flex-1 flex-col items-start gap-5 lg:gap-7">
            <h2 id="cta-title" class="text-[2rem] font-semibold leading-[2.3125rem] text-navy lg:text-5xl lg:leading-[3.375rem]">Planning an ops restructure, CRM overhaul or automation project?</h2>
            <p class="text-base leading-6 text-body lg:text-lg lg:leading-7">Tell me where the bottleneck is. Send a message and I'll reply by email.</p>
            <a href="#contact" data-open-contact aria-haspopup="dialog" class="btn-primary min-h-[3.25rem] w-full px-9 text-[1.0625rem] lg:min-h-[3.375rem] lg:w-auto lg:text-lg">Send an inquiry</a>
        </div>
    </section>
</div>
