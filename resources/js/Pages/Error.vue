<script setup>
import { computed } from 'vue';
import { Head } from '@inertiajs/vue3';

const props = defineProps({ status: Number });
const text = computed(
    () =>
        ({
            404: ['Page not found', "We couldn't find the page you were looking for."],
            403: ['Access denied', "You don't have permission to view this page."],
            500: ['Something went wrong', 'Please try again in a moment.'],
            503: ['Back soon', 'We are doing some maintenance. Please check back shortly.'],
        })[props.status] ?? ['Error', 'Something went wrong.'],
);
</script>

<template>
    <Head :title="text[0]" />
    <div class="navy-panel grid min-h-screen place-items-center px-6 text-center text-cream-100">
        <div>
            <p class="font-display text-[8rem] leading-none text-navy-400/50 sm:text-[12rem]">{{ status }}</p>
            <h1 class="mt-4 text-3xl font-medium sm:text-4xl">{{ text[0] }}</h1>
            <p class="mt-3 text-navy-200">{{ text[1] }}</p>
            <a href="/" class="btn-light mt-10">Back to Khalil Optik</a>
        </div>
    </div>
</template>
