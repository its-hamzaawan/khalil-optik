<script setup>
import { Head, Link, usePage } from '@inertiajs/vue3';
import Icon from '../Components/Icon.vue';
import SectionHeading from '../Components/SectionHeading.vue';

defineProps({ registration: String });
const site = usePage().props.site;

const services = [
    {
        icon: 'eye', title: 'Eye-Care Services', key: 'eye_test',
        text: `${site.doctor.name}, ${site.doctor.title}, provides eye-care services at Khalil Optik. Book a visit to have your eyes checked and get professional attention for your eye health.`,
        points: ['Eye examinations by appointment', 'Advice on caring for your eyes', 'Guidance on the right correction for you'],
    },
    {
        icon: 'glasses', title: 'Spectacles', key: 'frame_fitting',
        text: 'Choose from a wide variety of frames and have lenses made to suit your prescription, face shape and daily routine.',
        points: ['Frames for men, women and children', 'Lenses matched to your needs', 'Fitting and adjustments'],
    },
    {
        icon: 'lens', title: 'Contact Lenses', key: 'contact_lens',
        text: 'Explore suitable contact lens options and learn how to wear and care for them safely and comfortably.',
        points: ['Daily and monthly options', 'Guidance for first-time wearers', 'Lens care solutions & accessories'],
    },
    {
        icon: 'sun', title: 'Sunglasses', key: null,
        text: 'Stylish eyewear for everyday use that also helps protect your eyes from harsh sunlight and glare.',
        points: ['Classic and modern styles', 'Options for driving and outdoors', 'Try them on in store'],
    },
];

const steps = [
    { n: '01', title: 'Book or walk in', text: 'Reserve a time online or visit us in Main Bazar.' },
    { n: '02', title: 'Eye check-up', text: 'Get your eyes checked and understand your vision needs.' },
    { n: '03', title: 'Choose your eyewear', text: 'Our team helps you pick frames, lenses or contact lenses.' },
    { n: '04', title: 'Collect & enjoy', text: 'Pick up your eyewear and see the world more clearly.' },
];
</script>

<template>
    <Head title="Services" />

    <section class="container-x pb-12 pt-20 lg:pt-28">
        <p v-reveal class="eyebrow">Our services</p>
        <h1 v-reveal="100" class="mt-6 max-w-4xl text-5xl font-medium leading-[1.05] sm:text-6xl">
            Professional eye care and <span class="italic text-navy-500">friendly guidance.</span>
        </h1>
        <p v-reveal="200" class="mt-8 max-w-2xl text-lg leading-relaxed text-navy-900/70">
            Your eyesight is precious, which is why we take your eye-care needs seriously. {{ registration }}.
        </p>
    </section>

    <section class="container-x space-y-6 py-12">
        <div
            v-for="(s, i) in services"
            :key="s.title"
            v-reveal
            class="card group grid gap-8 p-8 transition duration-500 hover:shadow-xl sm:p-12 lg:grid-cols-[auto_1fr_auto] lg:items-center"
        >
            <span class="grid size-20 place-items-center rounded-3xl bg-navy-900 text-cream-100 transition duration-500 group-hover:-rotate-6 group-hover:scale-105">
                <Icon :name="s.icon" :size="34" />
            </span>
            <div>
                <p class="text-xs font-semibold uppercase tracking-[0.2em] text-navy-500">0{{ i + 1 }}</p>
                <h2 class="mt-1 text-3xl font-medium">{{ s.title }}</h2>
                <p class="mt-3 max-w-2xl leading-relaxed text-navy-900/70">{{ s.text }}</p>
                <ul class="mt-5 flex flex-wrap gap-2">
                    <li v-for="p in s.points" :key="p" class="badge bg-navy-100 font-medium text-navy-800">{{ p }}</li>
                </ul>
            </div>
            <Link v-if="s.key" :href="`/appointment?service=${s.key}`" class="btn-primary whitespace-nowrap">Book now <Icon name="arrow" :size="16" /></Link>
            <Link v-else href="/eyewear?category=sunglasses" class="btn-outline whitespace-nowrap">See styles <Icon name="arrow" :size="16" /></Link>
        </div>
    </section>

    <section class="navy-panel mt-12 py-24 text-cream-100">
        <div class="container-x">
            <SectionHeading light center eyebrow="How it works" title="Clear vision in four simple steps." />
            <div class="mt-16 grid gap-6 sm:grid-cols-2 lg:grid-cols-4">
                <div v-for="(st, i) in steps" :key="st.n" v-reveal="i * 120" class="rounded-3xl border border-white/10 bg-white/5 p-8 backdrop-blur transition duration-500 hover:-translate-y-1 hover:bg-white/10">
                    <p class="font-display text-5xl text-navy-400">{{ st.n }}</p>
                    <h3 class="mt-6 text-xl font-medium">{{ st.title }}</h3>
                    <p class="mt-2 text-sm leading-relaxed text-navy-200">{{ st.text }}</p>
                </div>
            </div>
            <div v-reveal class="mt-14 text-center">
                <Link href="/appointment" class="btn-light">Book your eye check-up <Icon name="arrow" :size="16" /></Link>
            </div>
        </div>
    </section>
</template>
