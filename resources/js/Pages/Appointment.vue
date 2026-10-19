<script setup>
import { computed, ref, watch } from 'vue';
import { Head, Link, useForm, usePage } from '@inertiajs/vue3';
import Icon from '../Components/Icon.vue';

const props = defineProps({ services: Object, minDate: String, maxDate: String, closedDays: Array, preselect: String });
const page = usePage();
const site = page.props.site;
const booking = computed(() => page.props.flash?.booking);

const form = useForm({
    name: '',
    phone: '',
    email: '',
    service: props.services[props.preselect] ? props.preselect : 'eye_test',
    preferred_date: '',
    preferred_time: '',
    notes: '',
});

const slots = ref([]);
const slotsLoading = ref(false);
const slotsError = ref('');

const isClosedDay = (iso) => props.closedDays.includes(new Date(iso + 'T00:00:00').getDay());
const prettyDate = computed(() =>
    form.preferred_date
        ? new Date(form.preferred_date + 'T00:00:00').toLocaleDateString('en-GB', { weekday: 'long', day: 'numeric', month: 'long' })
        : '',
);

async function loadSlots() {
    form.preferred_time = '';
    slots.value = [];
    slotsError.value = '';
    if (!form.preferred_date) return;

    if (isClosedDay(form.preferred_date)) {
        slotsError.value = 'The shop is closed on this day. Please choose another date.';
        return;
    }

    slotsLoading.value = true;
    try {
        const res = await fetch(`/appointment/slots?date=${form.preferred_date}`, { headers: { Accept: 'application/json' } });
        if (!res.ok) throw new Error();
        slots.value = (await res.json()).slots;
        if (!slots.value.some((s) => s.available)) slotsError.value = 'No free times left on this day. Please try another date.';
    } catch {
        slotsError.value = 'Could not load times. Please check your connection and try again.';
    } finally {
        slotsLoading.value = false;
    }
}
watch(() => form.preferred_date, loadSlots);

function submit() {
    form.post('/appointment', {
        preserveScroll: true,
        onSuccess: () => {
            form.reset();
            slots.value = [];
            window.scrollTo({ top: 0, behavior: 'smooth' });
        },
        // If the slot was taken meanwhile, refresh the list.
        onError: (errors) => errors.preferred_time && loadSlots(),
    });
}
</script>

<template>
    <Head title="Book an Appointment" />

    <section class="container-x grid gap-12 py-20 lg:grid-cols-[0.8fr_1.2fr] lg:py-24">
        <!-- Intro -->
        <div>
            <p v-reveal class="eyebrow">Appointments</p>
            <h1 v-reveal="100" class="mt-6 text-5xl font-medium leading-[1.05] sm:text-6xl">
                Book your <span class="italic text-navy-500">eye check-up.</span>
            </h1>
            <p v-reveal="200" class="mt-6 text-lg leading-relaxed text-navy-900/70">
                Pick a date and time that suits you. We'll confirm your appointment by phone or WhatsApp.
            </p>

            <div v-reveal="300" class="navy-panel mt-10 rounded-3xl p-8 text-cream-100">
                <span class="grid size-12 place-items-center rounded-2xl bg-cream-100 text-navy-900"><Icon name="eye" /></span>
                <p class="mt-5 font-display text-2xl">{{ site.doctor.name }}</p>
                <p class="text-sm text-navy-300">{{ site.doctor.title }}</p>
                <p class="mt-4 text-sm leading-relaxed text-navy-200">Provides eye-care services at Khalil Optik.</p>
                <div class="mt-6 border-t border-white/10 pt-6 text-sm">
                    <p class="font-semibold">Prefer to talk?</p>
                    <div class="mt-3 flex flex-wrap gap-3">
                        <a :href="site.contact.phone_href" class="btn-light btn-sm"><Icon name="phone" :size="14" /> {{ site.contact.phone }}</a>
                        <a :href="site.contact.whatsapp_href" target="_blank" rel="noopener" class="btn-outline-light btn-sm"><Icon name="whatsapp" :size="14" /> WhatsApp</a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Form / confirmation -->
        <div v-reveal="150">
            <Transition name="page" mode="out-in">
                <!-- Confirmation -->
                <div v-if="booking" key="done" class="card p-8 text-center sm:p-12">
                    <span class="mx-auto grid size-20 place-items-center rounded-full bg-navy-900 text-cream-100">
                        <Icon name="check" :size="36" />
                    </span>
                    <h2 class="mt-6 text-3xl font-medium">Thank you, {{ booking.name }}!</h2>
                    <p class="mt-3 text-navy-900/70">Your request has been received. We'll contact you shortly to confirm.</p>

                    <dl class="mx-auto mt-8 max-w-sm divide-y divide-navy-900/10 rounded-2xl border border-navy-900/10 text-left text-sm">
                        <div class="flex justify-between p-4"><dt class="text-navy-900/55">Reference</dt><dd class="font-mono font-bold">{{ booking.reference }}</dd></div>
                        <div class="flex justify-between p-4"><dt class="text-navy-900/55">Service</dt><dd class="font-semibold">{{ booking.service }}</dd></div>
                        <div class="flex justify-between p-4"><dt class="text-navy-900/55">Date</dt><dd class="font-semibold">{{ booking.date }}</dd></div>
                        <div class="flex justify-between p-4"><dt class="text-navy-900/55">Time</dt><dd class="font-semibold">{{ booking.time }}</dd></div>
                    </dl>
                    <p class="mt-6 text-xs text-navy-900/50">Please keep your reference number. Bring your old glasses or prescription if you have them.</p>
                    <div class="mt-8 flex flex-wrap justify-center gap-3">
                        <Link href="/appointment" class="btn-outline">Book another</Link>
                        <Link href="/" class="btn-primary">Back to home</Link>
                    </div>
                </div>

                <!-- Form -->
                <form v-else key="form" class="card space-y-8 p-6 sm:p-10" novalidate @submit.prevent="submit">
                    <!-- Step 1 -->
                    <fieldset>
                        <legend class="flex items-center gap-3 font-display text-xl"><span class="grid size-8 place-items-center rounded-full bg-navy-900 text-sm text-cream-100">1</span> Choose a service</legend>
                        <div class="mt-5 grid gap-3 sm:grid-cols-2">
                            <label
                                v-for="(label, key) in services"
                                :key="key"
                                class="flex cursor-pointer items-center gap-3 rounded-2xl border p-4 text-sm font-semibold transition duration-300"
                                :class="form.service === key ? 'border-navy-900 bg-navy-900 text-cream-100 shadow-lg' : 'border-navy-900/15 hover:border-navy-900/40'"
                            >
                                <input v-model="form.service" type="radio" :value="key" class="sr-only" />
                                <span class="grid size-5 place-items-center rounded-full border-2" :class="form.service === key ? 'border-cream-100' : 'border-navy-900/30'">
                                    <span v-if="form.service === key" class="size-2 rounded-full bg-cream-100" />
                                </span>
                                {{ label }}
                            </label>
                        </div>
                        <p v-if="form.errors.service" class="error-text">{{ form.errors.service }}</p>
                    </fieldset>

                    <!-- Step 2 -->
                    <fieldset>
                        <legend class="flex items-center gap-3 font-display text-xl"><span class="grid size-8 place-items-center rounded-full bg-navy-900 text-sm text-cream-100">2</span> Pick a date &amp; time</legend>
                        <div class="mt-5">
                            <label class="label" for="date">Date</label>
                            <input id="date" v-model="form.preferred_date" type="date" :min="minDate" :max="maxDate" class="input sm:max-w-xs" :class="form.errors.preferred_date && 'input-error'" />
                            <p v-if="form.errors.preferred_date" class="error-text">{{ form.errors.preferred_date }}</p>
                        </div>

                        <Transition name="slide-down" mode="out-in">
                            <div v-if="slotsLoading" key="loading" class="mt-5 grid grid-cols-3 gap-2 sm:grid-cols-5">
                                <div v-for="n in 10" :key="n" class="h-11 animate-pulse rounded-xl bg-navy-900/8" />
                            </div>
                            <p v-else-if="slotsError" key="err" class="mt-5 rounded-xl bg-navy-100 p-4 text-sm text-navy-800">{{ slotsError }}</p>
                            <div v-else-if="slots.length" key="slots" class="mt-5">
                                <p class="label">Available times on {{ prettyDate }}</p>
                                <div class="grid grid-cols-3 gap-2 sm:grid-cols-5">
                                    <button
                                        v-for="s in slots"
                                        :key="s.time"
                                        type="button"
                                        :disabled="!s.available"
                                        class="rounded-xl border px-2 py-3 text-sm font-semibold transition duration-200"
                                        :class="[
                                            form.preferred_time === s.time
                                                ? 'scale-105 border-navy-900 bg-navy-900 text-cream-100 shadow-md'
                                                : 'border-navy-900/15 hover:border-navy-900',
                                            !s.available && 'cursor-not-allowed !border-transparent bg-navy-900/5 text-navy-900/30 line-through hover:!border-transparent',
                                        ]"
                                        @click="form.preferred_time = s.time"
                                    >
                                        {{ s.label }}
                                    </button>
                                </div>
                            </div>
                        </Transition>
                        <p v-if="form.errors.preferred_time" class="error-text">{{ form.errors.preferred_time }}</p>
                    </fieldset>

                    <!-- Step 3 -->
                    <fieldset>
                        <legend class="flex items-center gap-3 font-display text-xl"><span class="grid size-8 place-items-center rounded-full bg-navy-900 text-sm text-cream-100">3</span> Your details</legend>
                        <div class="mt-5 grid gap-5 sm:grid-cols-2">
                            <div>
                                <label class="label" for="name">Full name *</label>
                                <input id="name" v-model="form.name" autocomplete="name" class="input" :class="form.errors.name && 'input-error'" />
                                <p v-if="form.errors.name" class="error-text">{{ form.errors.name }}</p>
                            </div>
                            <div>
                                <label class="label" for="phone">Phone / WhatsApp *</label>
                                <input id="phone" v-model="form.phone" type="tel" inputmode="tel" autocomplete="tel" placeholder="03XX XXXXXXX" class="input" :class="form.errors.phone && 'input-error'" />
                                <p v-if="form.errors.phone" class="error-text">{{ form.errors.phone }}</p>
                            </div>
                            <div class="sm:col-span-2">
                                <label class="label" for="email">Email <span class="font-normal text-navy-900/40">(optional)</span></label>
                                <input id="email" v-model="form.email" type="email" autocomplete="email" class="input" :class="form.errors.email && 'input-error'" />
                                <p v-if="form.errors.email" class="error-text">{{ form.errors.email }}</p>
                            </div>
                            <div class="sm:col-span-2">
                                <label class="label" for="notes">Anything we should know? <span class="font-normal text-navy-900/40">(optional)</span></label>
                                <textarea id="notes" v-model="form.notes" rows="3" maxlength="500" class="input" placeholder="e.g. headaches while reading, existing prescription…" />
                                <p v-if="form.errors.notes" class="error-text">{{ form.errors.notes }}</p>
                            </div>
                        </div>
                    </fieldset>

                    <div class="flex flex-col items-start gap-4 border-t border-navy-900/10 pt-6 sm:flex-row sm:items-center sm:justify-between">
                        <p class="text-xs text-navy-900/50">This is a booking request. We'll call to confirm.</p>
                        <button type="submit" class="btn-primary w-full sm:w-auto" :disabled="form.processing || !form.preferred_time">
                            <svg v-if="form.processing" class="size-4 animate-spin" viewBox="0 0 24 24" fill="none"><circle cx="12" cy="12" r="10" stroke="currentColor" stroke-width="3" opacity=".25" /><path d="M22 12a10 10 0 0 1-10 10" stroke="currentColor" stroke-width="3" /></svg>
                            {{ form.processing ? 'Booking…' : 'Request appointment' }}
                        </button>
                    </div>
                </form>
            </Transition>
        </div>
    </section>
</template>
