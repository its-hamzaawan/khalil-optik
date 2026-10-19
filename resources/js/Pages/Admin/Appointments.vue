<script setup>
import { ref, watch } from 'vue';
import { Head, router } from '@inertiajs/vue3';
import Icon from '../../Components/Icon.vue';
import Pagination from '../../Components/Pagination.vue';
import { shortDate } from '../../Composables/format';

const props = defineProps({ appointments: Object, filters: Object, statuses: Array });

const f = ref({
    status: props.filters.status ?? '',
    date: props.filters.date ?? '',
    search: props.filters.search ?? '',
    when: props.filters.when ?? 'upcoming',
});

let t;
watch(f, () => {
    clearTimeout(t);
    t = setTimeout(() => {
        const params = Object.fromEntries(Object.entries(f.value).filter(([, v]) => v));
        router.get('/admin/appointments', params, { preserveState: true, replace: true });
    }, 300);
}, { deep: true });

const open = ref(null);
const statusClass = {
    pending: 'bg-amber-100 text-amber-800',
    confirmed: 'bg-navy-100 text-navy-800',
    completed: 'bg-emerald-100 text-emerald-800',
    cancelled: 'bg-navy-900/10 text-navy-900/50 line-through',
};

function setStatus(a, status) {
    router.patch(`/admin/appointments/${a.id}`, { status, admin_note: a.admin_note }, { preserveScroll: true });
}
function saveNote(a) {
    router.patch(`/admin/appointments/${a.id}`, { status: a.status, admin_note: a.admin_note }, { preserveScroll: true });
}
function destroy(a) {
    if (confirm(`Delete appointment ${a.reference}?`)) router.delete(`/admin/appointments/${a.id}`, { preserveScroll: true });
}
const whatsapp = (a) => {
    const num = a.phone.replace(/^\+?92|^0092|^0/, '');
    const msg = `Assalam-o-Alaikum ${a.name}, this is Khalil Optik confirming your appointment (${a.reference}) on ${shortDate(a.preferred_date)} at ${a.time_label}.`;
    return `https://wa.me/92${num}?text=${encodeURIComponent(msg)}`;
};
</script>

<template>
    <Head title="Appointments" />

    <h1 class="text-3xl font-medium">Appointments</h1>
    <p class="mt-1 text-navy-900/60">Confirm, complete or cancel booking requests.</p>

    <div class="mt-6 flex flex-col flex-wrap gap-3 sm:flex-row">
        <div class="inline-flex rounded-full bg-navy-900/5 p-1">
            <button v-for="w in ['upcoming', 'all']" :key="w" class="rounded-full px-4 py-2 text-sm font-semibold capitalize transition" :class="f.when === w ? 'bg-navy-900 text-cream-100' : ''" @click="f.when = w">{{ w }}</button>
        </div>
        <select v-model="f.status" class="input sm:max-w-[12rem]">
            <option value="">All statuses</option>
            <option v-for="s in statuses" :key="s" :value="s" class="capitalize">{{ s }}</option>
        </select>
        <input v-model="f.date" type="date" class="input sm:max-w-[12rem]" />
        <input v-model="f.search" type="search" placeholder="Name, phone or reference…" class="input sm:max-w-xs" />
    </div>

    <TransitionGroup tag="div" name="list" class="mt-6 space-y-3">
        <div v-for="a in appointments.data" :key="a.id" class="card overflow-hidden">
            <button class="flex w-full flex-wrap items-center gap-4 p-5 text-left" @click="open = open === a.id ? null : a.id">
                <div class="w-24 shrink-0">
                    <p class="text-xs text-navy-900/50">{{ shortDate(a.preferred_date) }}</p>
                    <p class="font-display text-xl">{{ a.time_label }}</p>
                </div>
                <div class="min-w-0 flex-1">
                    <p class="font-semibold">{{ a.name }} <span class="ml-2 font-mono text-xs text-navy-900/40">{{ a.reference }}</span></p>
                    <p class="text-sm text-navy-900/60">{{ a.service_label }} · {{ a.phone }}</p>
                </div>
                <span class="badge capitalize" :class="statusClass[a.status]">{{ a.status }}</span>
                <Icon name="arrow" :size="16" class="transition-transform duration-300" :class="open === a.id ? 'rotate-90' : ''" />
            </button>

            <Transition name="slide-down">
                <div v-if="open === a.id" class="grid gap-6 border-t border-navy-900/8 bg-navy-900/[0.02] p-5 lg:grid-cols-2">
                    <div class="space-y-2 text-sm">
                        <p><span class="text-navy-900/50">Email:</span> {{ a.email || '—' }}</p>
                        <p><span class="text-navy-900/50">Customer notes:</span> {{ a.notes || '—' }}</p>
                        <p class="text-xs text-navy-900/40">Requested {{ new Date(a.created_at).toLocaleString('en-GB') }}</p>
                        <div class="flex flex-wrap gap-2 pt-3">
                            <a :href="`tel:${a.phone}`" class="btn-outline btn-sm"><Icon name="phone" :size="14" /> Call</a>
                            <a :href="whatsapp(a)" target="_blank" rel="noopener" class="btn-outline btn-sm"><Icon name="whatsapp" :size="14" /> WhatsApp</a>
                        </div>
                    </div>
                    <div>
                        <p class="label">Status</p>
                        <div class="flex flex-wrap gap-2">
                            <button
                                v-for="s in statuses"
                                :key="s"
                                class="badge px-4 py-2 capitalize transition"
                                :class="a.status === s ? 'bg-navy-900 text-cream-100' : 'bg-navy-900/5 hover:bg-navy-900/10'"
                                @click="setStatus(a, s)"
                            >{{ s }}</button>
                        </div>
                        <label class="label mt-4" :for="`note-${a.id}`">Private note</label>
                        <textarea :id="`note-${a.id}`" v-model="a.admin_note" rows="2" class="input" placeholder="Only visible to staff" />
                        <div class="mt-3 flex justify-between">
                            <button class="btn-primary btn-sm" @click="saveNote(a)">Save note</button>
                            <button class="btn-sm rounded-full px-4 text-xs font-semibold text-red-600 hover:bg-red-50" @click="destroy(a)"><Icon name="trash" :size="14" class="inline" /> Delete</button>
                        </div>
                    </div>
                </div>
            </Transition>
        </div>
    </TransitionGroup>

    <p v-if="!appointments.data.length" class="card mt-6 p-10 text-center text-navy-900/50">No appointments match these filters.</p>
    <div class="mt-6"><Pagination :links="appointments.links" /></div>
</template>
