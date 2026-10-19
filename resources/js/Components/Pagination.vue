<script setup>
import { Link } from '@inertiajs/vue3';
defineProps({ links: { type: Array, default: () => [] }, dark: Boolean });
</script>

<template>
    <nav v-if="links.length > 3" class="flex flex-wrap items-center justify-center gap-2" aria-label="Pagination">
        <template v-for="(link, i) in links" :key="i">
            <span
                v-if="!link.url"
                class="rounded-full px-4 py-2 text-sm opacity-40"
                v-html="link.label"
            />
            <Link
                v-else
                :href="link.url"
                preserve-scroll
                class="rounded-full px-4 py-2 text-sm font-semibold transition"
                :class="link.active ? 'bg-navy-900 text-cream-100' : 'text-navy-900 hover:bg-navy-900/8'"
                v-html="link.label"
            />
        </template>
    </nav>
</template>
