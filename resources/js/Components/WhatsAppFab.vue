<script setup>
import { usePage } from '@inertiajs/vue3';
import Icon from './Icon.vue';
const site = usePage().props.site;
</script>

<template>
    <a
        :href="`${site.contact.whatsapp_href}?text=${encodeURIComponent('Assalam-o-Alaikum Khalil Optik, I would like to ask about ')}`"
        target="_blank"
        rel="noopener"
        class="group fixed bottom-5 right-5 z-50 flex items-center gap-2 rounded-full bg-[#1f9d55] p-4 text-white shadow-xl shadow-black/20 transition duration-300 hover:-translate-y-1 hover:pr-5"
        aria-label="Chat on WhatsApp"
    >
        <Icon name="whatsapp" :size="22" />
        <span class="max-w-0 overflow-hidden whitespace-nowrap text-sm font-semibold transition-all duration-500 group-hover:max-w-40">
            WhatsApp us
        </span>
    </a>
</template>
