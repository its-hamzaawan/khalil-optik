<script setup>
defineProps({
    eyebrow: String,
    title: { type: String, required: true },
    text: String,
    center: Boolean,
    light: Boolean,
});
</script>

<template>
    <div v-reveal :class="center ? 'mx-auto max-w-2xl text-center' : 'max-w-2xl'">
        <p v-if="eyebrow" class="eyebrow" :class="light && '!text-navy-300'">{{ eyebrow }}</p>
        <h2 class="mt-4 text-3xl font-medium sm:text-4xl lg:text-5xl" :class="light ? 'text-cream-100' : 'text-navy-900'">
            {{ title }}
        </h2>
        <p v-if="text" class="mt-5 text-base leading-relaxed sm:text-lg" :class="light ? 'text-navy-200' : 'text-navy-900/70'">
            {{ text }}
        </p>
    </div>
</template>
