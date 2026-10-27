<script setup>
// Khalil Optik trademark.
// At rest: the round logo (public/images/logo.png).
// On hover: the circle opens into a pill and the logo turns into its text —
// orange glasses that draw themselves, "Khalil Optik" and "Since 1960".
// light = for dark-blue backgrounds (footer, admin sidebar).  large = bigger version.
defineProps({ light: Boolean, large: Boolean });
</script>

<template>
    <!-- Fixed-size placeholder so the header never shifts while the pill opens -->
    <span class="ko-logo group relative block shrink-0" :class="large ? 'size-24' : 'size-16'">
        <span
            class="ko-pill absolute left-0 top-0 z-20 flex h-full items-center overflow-hidden rounded-full shadow-lg"
            :class="[large ? 'w-24 group-hover:w-72' : 'w-16 group-hover:w-60', light ? 'ring-2 ring-white/20' : 'ring-1 ring-navy-900/10']"
        >
            <!-- The round logo: spins away on hover -->
            <img
                src="/images/logo.png"
                alt="Khalil Optik — Since 1960"
                width="96"
                height="96"
                class="ko-img absolute left-0 top-0 rounded-full"
                :class="large ? 'size-24' : 'size-16'"
            />

            <!-- The logo's text: slides in on hover -->
            <span class="ko-text flex flex-col items-start whitespace-nowrap" :class="large ? 'pl-8' : 'pl-6'" aria-hidden="true">
                <!-- The exact glasses cut from the logo (public/images/logo-glasses.png) -->
                <img src="/images/logo-glasses.png" alt="" width="237" height="90" class="ko-glasses block h-auto" :class="large ? 'w-14' : 'w-11'" />
                <span class="font-semibold leading-none text-[#DC9A34]" :class="large ? 'mt-1.5 text-[11px]' : 'mt-1 text-[9px]'">Since 1960</span>
                <span class="font-sans font-bold leading-none text-white" :class="large ? 'mt-1 text-3xl' : 'mt-0.5 text-2xl'">Khalil Optik</span>
            </span>
        </span>
    </span>
</template>

<style scoped>
.ko-pill {
    background: #082341; /* same navy as the logo */
    transition: width 0.6s cubic-bezier(0.22, 1, 0.36, 1), box-shadow 0.4s ease;
}
.ko-logo:hover .ko-pill {
    box-shadow: 0 14px 30px -10px rgb(8 35 65 / 0.55);
}

.ko-img {
    transition: opacity 0.35s ease, transform 0.6s cubic-bezier(0.22, 1, 0.36, 1);
}
.ko-logo:hover .ko-img {
    opacity: 0;
    transform: rotate(-120deg) scale(0.6);
}

.ko-text {
    opacity: 0;
    transform: translateX(14px);
    transition: opacity 0.3s ease, transform 0.6s cubic-bezier(0.22, 1, 0.36, 1);
}
.ko-logo:hover .ko-text {
    opacity: 1;
    transform: none;
    transition-delay: 0.18s;
}

/* Glasses are revealed left to right each time the pill opens */
.ko-glasses {
    clip-path: inset(0 100% 0 0);
    transition: clip-path 0.2s ease;
}
.ko-logo:hover .ko-glasses {
    clip-path: inset(0 0 0 0);
    transition: clip-path 0.8s cubic-bezier(0.22, 1, 0.36, 1) 0.25s;
}

@media (prefers-reduced-motion: reduce) {
    .ko-pill, .ko-img, .ko-text, .ko-glasses { transition: none !important; }
}
</style>