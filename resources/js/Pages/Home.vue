<script setup>
import { Head, Link, usePage } from '@inertiajs/vue3';
import Icon from '../Components/Icon.vue';
import SectionHeading from '../Components/SectionHeading.vue';
import ProductCard from '../Components/ProductCard.vue';
import HeroGlasses from '../Components/HeroGlasses.vue';

defineProps({ featured: Array, years: Number });
const site = usePage().props.site;

const offers = [
    { icon: 'glasses', title: 'Spectacles', text: 'Find frames and lenses suited to your needs, face and lifestyle.', href: '/eyewear?category=spectacles' },
    { icon: 'lens', title: 'Contact Lenses', text: 'Explore suitable contact lens options for your vision needs.', href: '/eyewear?category=contact-lenses' },
    { icon: 'eye', title: 'Eye-Care Services', text: 'Professional attention for your eye health from our eye specialist.', href: '/services' },
    { icon: 'sun', title: 'Sunglasses', text: 'Stylish, protective eyewear for everyday use under the Punjab sun.', href: '/eyewear?category=sunglasses' },
];

const reasons = [
    { icon: 'clock', title: 'Since 1960', text: 'Among the first optical shops in the region, serving the community for generations.' },
    { icon: 'users', title: 'A trusted family business', text: 'Founded by Mr. Khalil, carried on today by Mr. Shoukat Ali and his sons.' },
    { icon: 'grid', title: 'Wide variety', text: 'Spectacles, sunglasses, contact lenses and accessories under one roof.' },
    { icon: 'heart', title: 'Personal attention', text: 'Care, respect and honest guidance for every customer, of every age.' },
];
</script>

<template>
    <Head title="Home" />

    <!-- HERO -->
    <section class="navy-panel relative overflow-hidden text-cream-100">
        <div class="container-x relative grid items-center gap-12 py-20 lg:grid-cols-[1.1fr_0.9fr] lg:py-28">
            <div>
                <p v-reveal class="eyebrow !text-navy-300">Jauharabad · Since 1960</p>
                <h1 v-reveal="100" class="mt-6 text-5xl font-medium leading-[1.05] sm:text-6xl lg:text-7xl">
                    Your eyes matter.<br />
                    <span class="italic text-[#DC9A34]">Your vision</span> matters.
                </h1>
                <p v-reveal="200" class="mt-8 max-w-xl text-lg leading-relaxed text-navy-200">
                    For more than {{ years }} years, Khalil Optik has helped the people of Jauharabad and nearby areas
                    see better and enjoy a clearer, brighter world — with care, honesty and dedication.
                </p>
                <div v-reveal="300" class="mt-10 flex flex-wrap gap-4">
                    <Link href="/appointment" class="btn-light">
                        <Icon name="calendar" :size="16" /> Book an Eye Check-up
                    </Link>
                    <Link href="/eyewear" class="btn-outline-light">Browse Eyewear <Icon name="arrow" :size="16" /></Link>
                </div>
                <div v-reveal="400" class="mt-12 flex flex-wrap items-center gap-x-8 gap-y-3 text-sm text-navy-200">
                   <span class="flex items-center gap-2 font-semibold text-[#DC9A34] [filter:drop-shadow(0_2px_6px_rgb(220_154_52/0.55))]"><Icon name="shield" :size="18" /> PHC Registered</span>
                    <span class="flex items-center gap-2"><Icon name="eye" :size="18" /> {{ site.doctor.name }}, {{ site.doctor.title }}</span>
                </div>
            </div>

            <!-- Animated spectacles in the style of the Khalil Optik logo -->
            <div class="relative hidden lg:block">
                <HeroGlasses :years="years" />
            </div>
        </div>

        <!-- Soft curve into the off-white page -->
        <svg class="block w-full text-cream-100" viewBox="0 0 1440 60" preserveAspectRatio="none" aria-hidden="true">
            <path fill="currentColor" d="M0 60h1440V20C1200 55 960 60 720 40S240 0 0 30Z" />
        </svg>
    </section>

    <!-- WHAT WE OFFER -->
    <section class="container-x py-24">
        <div class="flex flex-col justify-between gap-8 lg:flex-row lg:items-end">
            <SectionHeading eyebrow="What we offer" title="Everything your eyes need, under one roof." />
            <Link v-reveal href="/services" class="btn-outline self-start lg:self-auto">All services <Icon name="arrow" :size="16" /></Link>
        </div>

        <div class="mt-14 grid gap-6 sm:grid-cols-2 lg:grid-cols-4">
            <Link
                v-for="(o, i) in offers"
                :key="o.title"
                v-reveal="i * 100"
                :href="o.href"
                class="group card relative overflow-hidden p-8 transition duration-500 ease-out-soft hover:-translate-y-2 hover:border-navy-900 hover:bg-navy-900"
            >
                <span class="grid size-14 place-items-center rounded-2xl bg-navy-100 text-navy-800 transition duration-500 group-hover:rotate-6 group-hover:bg-cream-100">
                    <Icon :name="o.icon" :size="26" />
                </span>
                <h3 class="mt-8 text-2xl font-medium transition group-hover:text-cream-100">{{ o.title }}</h3>
                <p class="mt-3 text-sm leading-relaxed text-navy-900/65 transition group-hover:text-navy-200">{{ o.text }}</p>
                <span class="mt-8 flex items-center gap-2 text-sm font-semibold text-navy-700 transition group-hover:gap-3 group-hover:text-cream-100">
                    Learn more <Icon name="arrow" :size="16" />
                </span>
            </Link>
        </div>
    </section>

    <!-- STORY TEASER -->
    <section class="bg-cream-200/60 py-24">
        <div class="container-x grid items-center gap-14 lg:grid-cols-2">
            <div v-reveal class="relative">
                <div class="navy-panel relative overflow-hidden rounded-[2rem] p-10 text-cream-100 sm:p-14">
                    <p class="font-display text-8xl leading-none text-navy-400/40 sm:text-9xl">1960</p>
                    <p class="mt-6 font-display text-2xl leading-snug sm:text-3xl">
                        “To help people see better and enjoy a clearer, brighter world.”
                    </p>
                    <p class="mt-6 text-sm uppercase tracking-[0.2em] text-navy-300">Our mission since day one</p>
                </div>
                <div class="absolute -bottom-6 -right-2 hidden rounded-2xl bg-cream-50 px-6 py-5 shadow-xl sm:block">
                    <p class="text-xs uppercase tracking-[0.2em] text-navy-500">Founded by</p>
                    <p class="font-display text-xl">Mr. Khalil</p>
                </div>
            </div>

            <div>
                <SectionHeading
                    eyebrow="Our story"
                    title="A proud legacy, carried forward by family."
                    text="Founded in 1960 by the well-known Mr. Khalil, Khalil Optik was among the first optical shops in the region. Today the legacy continues under Mr. Shoukat Ali and his sons, Muhammad Asif and Muhammad Ashraf."
                />
                <p v-reveal class="mt-5 leading-relaxed text-navy-900/70">
                    Over the years, thousands of customers have trusted Khalil Optik. Their trust is our greatest
                    achievement — and our motivation to serve even better.
                </p>
                <Link v-reveal href="/about" class="btn-primary mt-10">Read our story <Icon name="arrow" :size="16" /></Link>
            </div>
        </div>
    </section>

    <!-- FEATURED EYEWEAR -->
    <section v-if="featured.length" class="container-x py-24">
        <div class="flex flex-col justify-between gap-8 lg:flex-row lg:items-end">
            <SectionHeading eyebrow="In store now" title="Featured eyewear" text="A glimpse of what's waiting for you at the shop. Visit us to try them on." />
            <Link v-reveal href="/eyewear" class="btn-outline self-start lg:self-auto">View all <Icon name="arrow" :size="16" /></Link>
        </div>
        <div class="mt-14 grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
            <div v-for="(p, i) in featured" :key="p.id" v-reveal="(i % 3) * 100">
                <ProductCard :product="p" />
            </div>
        </div>
    </section>

    <!-- EYE CARE -->
    <section class="container-x pb-24">
        <div class="navy-panel grid overflow-hidden rounded-[2.5rem] text-cream-100 lg:grid-cols-2">
            <div class="p-10 sm:p-16">
                <SectionHeading
                    light
                    eyebrow="Professional eye care"
                    title="Your eyesight is precious."
                    :text="`${site.doctor.name}, ${site.doctor.title}, provides eye-care services at Khalil Optik — helping people take better care of their eyes.`"
                />
                <ul v-reveal class="mt-8 space-y-3 text-navy-200">
                    <li class="flex items-center gap-3"><Icon name="check" :size="18" class="text-navy-300" /> Eye examinations by appointment</li>
                    <li class="flex items-center gap-3"><Icon name="check" :size="18" class="text-navy-300" /> Guidance on spectacles &amp; contact lenses</li>
                    <li class="flex items-center gap-3"><Icon name="check" :size="18" class="text-navy-300" /> Registered with the Punjab Healthcare Commission</li>
                </ul>
                <Link v-reveal href="/appointment" class="btn-light mt-10">Book an appointment <Icon name="arrow" :size="16" /></Link>
            </div>
            <div class="relative hidden min-h-80 items-center justify-center border-l border-white/10 lg:flex" aria-hidden="true">
                <div class="absolute size-80 animate-ping rounded-full border border-white/5 [animation-duration:4s]" />
                <div class="grid size-56 place-items-center rounded-full border border-white/15 bg-white/5 backdrop-blur">
                    <Icon name="eye" :size="96" class="text-cream-100" />
                </div>
            </div>
        </div>
    </section>

    <!-- WHY CHOOSE US -->
    <section class="bg-cream-50 py-24">
        <div class="container-x">
            <SectionHeading center eyebrow="Why choose Khalil Optik" title="Trusted for your vision, for over six decades." />
            <div class="mt-16 grid gap-10 sm:grid-cols-2 lg:grid-cols-4">
                <div v-for="(r, i) in reasons" :key="r.title" v-reveal="i * 100" class="text-center">
                    <span class="mx-auto grid size-16 place-items-center rounded-full border border-navy-900/15 text-navy-800">
                        <Icon :name="r.icon" :size="26" />
                    </span>
                    <h3 class="mt-6 text-xl font-medium">{{ r.title }}</h3>
                    <p class="mt-3 text-sm leading-relaxed text-navy-900/65">{{ r.text }}</p>
                </div>
            </div>
        </div>
    </section>

    <!-- CTA -->
    <section class="container-x py-24">
        <div v-reveal class="text-center">
            <p class="eyebrow">Don't ignore your eyes</p>
            <h2 class="mx-auto mt-6 max-w-3xl text-4xl font-medium sm:text-5xl lg:text-6xl">
                Poor vision affects work, studies, driving and daily life.
                <span class="italic text-navy-500">Take the right step today.</span>
            </h2>
            <div class="mt-10 flex flex-wrap justify-center gap-4">
                <a :href="site.contact.phone_href" class="btn-primary"><Icon name="phone" :size="16" /> Call {{ site.contact.phone }}</a>
                <a :href="site.contact.whatsapp_href" target="_blank" rel="noopener" class="btn-outline"><Icon name="whatsapp" :size="16" /> WhatsApp us</a>
                <Link href="/contact" class="btn-outline"><Icon name="pin" :size="16" /> Visit the shop</Link>
            </div>
        </div>
    </section>
</template>