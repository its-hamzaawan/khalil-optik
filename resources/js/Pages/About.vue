<script setup>
import { Head, Link } from '@inertiajs/vue3';
import Icon from '../Components/Icon.vue';
import SectionHeading from '../Components/SectionHeading.vue';

defineProps({ years: Number, leadership: Array, founder: String, registration: String });

const timeline = [
    { year: '1960', title: 'The beginning', text: 'Mr. Khalil opens Khalil Optik in Jauharabad — among the first optical shops in the region.' },
    { year: 'Decades of service', title: 'Growing with the community', text: 'Thousands of customers of all ages come to trust the shop for honest guidance and quality eyewear.' },
    { year: 'Today', title: 'The legacy continues', text: 'Mr. Shoukat Ali and his sons, Muhammad Asif and Muhammad Ashraf, lead the business with the same commitment to care.' },
];
</script>

<template>
    <Head title="About Us" />

    <section class="container-x pb-16 pt-20 lg:pt-28">
        <p v-reveal class="eyebrow">Our story</p>
        <h1 v-reveal="100" class="mt-6 max-w-4xl text-5xl font-medium leading-[1.05] sm:text-6xl lg:text-7xl">
            {{ years }} years of helping Jauharabad <span class="italic text-navy-500">see clearly.</span>
        </h1>
        <p v-reveal="200" class="mt-8 max-w-2xl text-lg leading-relaxed text-navy-900/70">
            Founded in 1960 by the well-known {{ founder }}, Khalil Optik has served the people of Jauharabad and
            nearby areas with care, honesty and dedication. Our mission has always been simple: to help people see
            better and enjoy a clearer, brighter world.
        </p>
    </section>

    <!-- Timeline -->
    <section class="container-x py-16">
        <div class="relative grid gap-10 border-l border-navy-900/15 pl-8 sm:pl-12">
            <div v-for="(t, i) in timeline" :key="t.year" v-reveal="i * 120" class="relative">
                <span class="absolute -left-[2.6rem] top-1 grid size-5 place-items-center rounded-full bg-navy-900 sm:-left-[3.6rem]">
                    <span class="size-2 rounded-full bg-cream-100" />
                </span>
                <p class="font-display text-3xl text-navy-500">{{ t.year }}</p>
                <h3 class="mt-2 text-2xl font-medium">{{ t.title }}</h3>
                <p class="mt-2 max-w-2xl leading-relaxed text-navy-900/70">{{ t.text }}</p>
            </div>
        </div>
    </section>

    <!-- Leadership -->
    <section class="bg-cream-200/60 py-24">
        <div class="container-x">
            <SectionHeading
                eyebrow="The family behind the shop"
                title="Led with hard work and a strong commitment to customer care."
                text="Our goal is to help every customer find the right eyewear and suitable solutions for their vision needs."
            />
            <div class="mt-14 grid gap-6 md:grid-cols-3">
                <div
                    v-for="(name, i) in leadership"
                    :key="name"
                    v-reveal="i * 120"
                    class="card group p-8 transition duration-500 hover:-translate-y-1 hover:shadow-xl"
                >
                    <span class="grid size-16 place-items-center rounded-2xl bg-navy-900 font-display text-2xl text-cream-100 transition group-hover:rotate-3">
                        {{ name.replace(/^(Mr\.|Muhammad)\s*/, '').charAt(0) }}
                    </span>
                    <h3 class="mt-6 text-2xl font-medium">{{ name }}</h3>
                    <p class="mt-1 text-sm text-navy-900/60">{{ i === 0 ? 'Leading Khalil Optik today' : 'Carrying the family legacy forward' }}</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Values -->
    <section class="container-x py-24">
        <div class="grid gap-12 lg:grid-cols-3">
            <div v-reveal class="lg:col-span-1">
                <p class="eyebrow">What we stand for</p>
                <h2 class="mt-4 text-4xl font-medium">Trust is our greatest achievement.</h2>
            </div>
            <div class="grid gap-6 sm:grid-cols-2 lg:col-span-2">
                <div v-reveal class="card p-8"><Icon name="heart" :size="26" class="text-navy-600" /><h3 class="mt-5 text-xl font-medium">Care</h3><p class="mt-2 text-sm leading-relaxed text-navy-900/65">Personal attention and respect for every customer who walks through our door.</p></div>
                <div v-reveal="100" class="card p-8"><Icon name="shield" :size="26" class="text-navy-600" /><h3 class="mt-5 text-xl font-medium">Honesty</h3><p class="mt-2 text-sm leading-relaxed text-navy-900/65">Straightforward advice so you choose what is genuinely right for your eyes.</p></div>
                <div v-reveal="200" class="card p-8"><Icon name="users" :size="26" class="text-navy-600" /><h3 class="mt-5 text-xl font-medium">All ages</h3><p class="mt-2 text-sm leading-relaxed text-navy-900/65">From children to grandparents — we help the whole family see better.</p></div>
                <div v-reveal="300" class="card p-8"><Icon name="check" :size="26" class="text-navy-600" /><h3 class="mt-5 text-xl font-medium">Registered</h3><p class="mt-2 text-sm leading-relaxed text-navy-900/65">{{ registration }}. Services are provided according to applicable requirements.</p></div>
            </div>
        </div>
    </section>

    <section class="container-x pb-24">
        <div v-reveal class="navy-panel flex flex-col items-start justify-between gap-8 rounded-[2rem] p-10 text-cream-100 sm:p-14 lg:flex-row lg:items-center">
            <div>
                <h2 class="text-3xl font-medium sm:text-4xl">Come and visit us in Main Bazar.</h2>
                <p class="mt-3 text-navy-200">Sarwar Shaheed Chowk, Jauharabad, District Khushab.</p>
            </div>
            <Link href="/contact" class="btn-light">Get directions <Icon name="arrow" :size="16" /></Link>
        </div>
    </section>
</template>
