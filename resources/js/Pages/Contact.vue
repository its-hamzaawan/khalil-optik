<script setup>
import { Head, useForm, usePage } from '@inertiajs/vue3';
import Icon from '../Components/Icon.vue';

const site = usePage().props.site;
const form = useForm({ name: '', phone: '', email: '', subject: '', message: '', website: '' });

const submit = () => form.post('/contact', { preserveScroll: true, onSuccess: () => form.reset() });

const mapSrc = `https://www.google.com/maps?q=${encodeURIComponent(site.contact.map_query)}&output=embed`;
</script>

<template>
    <Head title="Contact Us" />

    <section class="container-x pb-12 pt-20 lg:pt-24">
        <p v-reveal class="eyebrow">Contact</p>
        <h1 v-reveal="100" class="mt-6 text-5xl font-medium sm:text-6xl">Ready to <span class="italic text-navy-500">see better?</span></h1>
        <p v-reveal="200" class="mt-6 max-w-2xl text-lg text-navy-900/70">Call, WhatsApp or visit Khalil Optik — our team is happy to help you find the right eyewear.</p>
    </section>

    <section class="container-x grid gap-6 pb-12 md:grid-cols-3">
        <a v-reveal :href="site.contact.phone_href" class="card group p-8 transition duration-500 hover:-translate-y-1 hover:bg-navy-900">
            <Icon name="phone" :size="28" class="text-navy-600 transition group-hover:text-cream-100" />
            <p class="mt-6 text-sm font-semibold uppercase tracking-[0.2em] text-navy-500 group-hover:text-navy-300">Call now</p>
            <p class="mt-1 font-display text-2xl transition group-hover:text-cream-100">{{ site.contact.phone }}</p>
        </a>
        <a v-reveal="100" :href="site.contact.whatsapp_href" target="_blank" rel="noopener" class="card group p-8 transition duration-500 hover:-translate-y-1 hover:bg-navy-900">
            <Icon name="whatsapp" :size="28" class="text-navy-600 transition group-hover:text-cream-100" />
            <p class="mt-6 text-sm font-semibold uppercase tracking-[0.2em] text-navy-500 group-hover:text-navy-300">WhatsApp</p>
            <p class="mt-1 font-display text-2xl transition group-hover:text-cream-100">{{ site.contact.whatsapp }}</p>
        </a>
        <div v-reveal="200" class="card p-8">
            <Icon name="clock" :size="28" class="text-navy-600" />
            <p class="mt-6 text-sm font-semibold uppercase tracking-[0.2em] text-navy-500">Opening hours</p>
            <p v-for="h in site.hours" :key="h.days" class="mt-1 text-sm"><span class="font-semibold">{{ h.days }}:</span> {{ h.time }}</p>
        </div>
    </section>

    <section class="container-x grid gap-6 pb-24 lg:grid-cols-2">
        <form v-reveal class="card space-y-5 p-6 sm:p-10" novalidate @submit.prevent="submit">
            <h2 class="text-3xl font-medium">Send us a message</h2>
            <!-- honeypot (hidden from people, bots fill it) -->
            <input v-model="form.website" type="text" name="website" tabindex="-1" autocomplete="off" class="hidden" aria-hidden="true" />

            <div>
                <label class="label" for="c-name">Your name *</label>
                <input id="c-name" v-model="form.name" class="input" :class="form.errors.name && 'input-error'" autocomplete="name" />
                <p v-if="form.errors.name" class="error-text">{{ form.errors.name }}</p>
            </div>
            <div class="grid gap-5 sm:grid-cols-2">
                <div>
                    <label class="label" for="c-phone">Phone</label>
                    <input id="c-phone" v-model="form.phone" type="tel" class="input" :class="form.errors.phone && 'input-error'" autocomplete="tel" />
                    <p v-if="form.errors.phone" class="error-text">{{ form.errors.phone }}</p>
                </div>
                <div>
                    <label class="label" for="c-email">Email</label>
                    <input id="c-email" v-model="form.email" type="email" class="input" :class="form.errors.email && 'input-error'" autocomplete="email" />
                    <p v-if="form.errors.email" class="error-text">{{ form.errors.email }}</p>
                </div>
            </div>
            <div>
                <label class="label" for="c-subject">Subject</label>
                <input id="c-subject" v-model="form.subject" class="input" placeholder="e.g. Contact lens availability" />
            </div>
            <div>
                <label class="label" for="c-message">Message *</label>
                <textarea id="c-message" v-model="form.message" rows="5" class="input" :class="form.errors.message && 'input-error'" />
                <p v-if="form.errors.message" class="error-text">{{ form.errors.message }}</p>
            </div>
            <button class="btn-primary w-full sm:w-auto" :disabled="form.processing">
                {{ form.processing ? 'Sending…' : 'Send message' }} <Icon name="arrow" :size="16" />
            </button>
        </form>

        <div v-reveal="150" class="flex flex-col overflow-hidden rounded-3xl border border-navy-900/8 bg-cream-50">
            <iframe :src="mapSrc" class="h-80 w-full flex-1 grayscale-[30%] lg:h-auto" style="border: 0" loading="lazy" referrerpolicy="no-referrer-when-downgrade" title="Khalil Optik location map" />
            <div class="flex items-start gap-4 p-8">
                <Icon name="pin" :size="24" class="mt-1 shrink-0 text-navy-600" />
                <div>
                    <p class="font-display text-xl">Visit Khalil Optik</p>
                    <p class="mt-1 text-sm text-navy-900/65">{{ site.contact.address }}</p>
                    <a :href="`https://www.google.com/maps/search/?api=1&query=${encodeURIComponent(site.contact.map_query)}`" target="_blank" rel="noopener" class="mt-3 inline-flex items-center gap-2 text-sm font-semibold underline-offset-4 hover:underline">
                        Open in Google Maps <Icon name="external" :size="14" />
                    </a>
                </div>
            </div>
        </div>
    </section>
</template>
