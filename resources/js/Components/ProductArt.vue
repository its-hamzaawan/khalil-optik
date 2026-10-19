<script setup>
// Elegant line-art placeholder shown when a product has no photo yet.
defineProps({ category: String });
</script>

<template>
    <div class="grid size-full place-items-center bg-gradient-to-br from-navy-100 via-cream-100 to-cream-200 text-navy-800">
        <svg v-if="category === 'contact-lenses'" viewBox="0 0 120 80" class="w-2/5" fill="none" stroke="currentColor" stroke-width="2">
            <ellipse cx="40" cy="44" rx="22" ry="12" /><path d="M18 44c4-14 40-14 44 0" />
            <ellipse cx="82" cy="40" rx="22" ry="12" /><path d="M60 40c4-14 40-14 44 0" />
        </svg>
        <svg v-else-if="category === 'accessories'" viewBox="0 0 120 80" class="w-2/5" fill="none" stroke="currentColor" stroke-width="2">
            <rect x="14" y="26" width="92" height="34" rx="17" /><path d="M14 43h92" />
        </svg>
        <svg v-else viewBox="0 0 120 60" class="w-3/5" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round">
            <path
                v-if="category === 'sunglasses'"
                d="M10 22h40c0 16-6 24-20 24S10 38 10 22ZM70 22h40c0 16-6 24-20 24s-20-8-20-24Z"
                fill="currentColor"
                fill-opacity="0.85"
            />
            <template v-else>
                <circle cx="32" cy="32" r="18" /><circle cx="88" cy="32" r="18" />
            </template>
            <path d="M50 28c5-5 15-5 20 0M14 26 4 14M106 26l10-12" />
        </svg>
    </div>
</template>
