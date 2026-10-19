<script setup>
import { Head, Link } from '@inertiajs/vue3';
import Icon from '../../Components/Icon.vue';
import { shortDate, dateTime } from '../../Composables/format';

defineProps({ stats: Object, upcoming: Array, messages: Array });

const statusClass = { pending: 'bg-amber-100 text-amber-800', confirmed: 'bg-navy-100 text-navy-800' };
</script>

<template>
    <Head title="Dashboard" />

    <h1 class="text-3xl font-medium">Dashboard</h1>
    <p class="mt-1 text-navy-900/60">Here's what is happening at the shop.</p>

    <div class="mt-8 grid gap-4 sm:grid-cols-2 xl:grid-cols-4">
        <Link href="/admin/appointments" class="navy-panel rounded-3xl p-6 text-cream-100 transition hover:-translate-y-1 hover:shadow-xl">
            <Icon name="calendar" class="text-navy-300" />
            <p class="mt-6 font-display text-4xl">{{ stats.todayAppointments }}</p>
            <p class="text-sm text-navy-200">Appointments today</p>
        </Link>
        <Link href="/admin/appointments?status=pending" class="card p-6 transition hover:-translate-y-1 hover:shadow-xl">
            <Icon name="clock" class="text-navy-500" />
            <p class="mt-6 font-display text-4xl">{{ stats.pendingAppointments }}</p>
            <p class="text-sm text-navy-900/60">Waiting for confirmation</p>
        </Link>
        <Link href="/admin/messages" class="card p-6 transition hover:-translate-y-1 hover:shadow-xl">
            <Icon name="inbox" class="text-navy-500" />
            <p class="mt-6 font-display text-4xl">{{ stats.unreadMessages }}</p>
            <p class="text-sm text-navy-900/60">Unread messages</p>
        </Link>
        <Link href="/admin/products" class="card p-6 transition hover:-translate-y-1 hover:shadow-xl">
            <Icon name="box" class="text-navy-500" />
            <p class="mt-6 font-display text-4xl">{{ stats.activeProducts }}<span class="text-lg text-navy-900/40"> / {{ stats.products }}</span></p>
            <p class="text-sm text-navy-900/60">Products live <span v-if="stats.lowStock" class="font-semibold text-amber-700">· {{ stats.lowStock }} low stock</span></p>
        </Link>
    </div>

    <div class="mt-8 grid gap-6 xl:grid-cols-[1.4fr_1fr]">
        <section class="card p-6">
            <div class="flex items-center justify-between">
                <h2 class="text-xl font-medium">Upcoming appointments</h2>
                <Link href="/admin/appointments" class="text-sm font-semibold text-navy-600 hover:underline">View all</Link>
            </div>
            <ul v-if="upcoming.length" class="mt-4 divide-y divide-navy-900/8">
                <li v-for="a in upcoming" :key="a.id" class="flex items-center gap-4 py-3">
                    <div class="w-20 shrink-0 text-center">
                        <p class="text-xs text-navy-900/50">{{ shortDate(a.preferred_date) }}</p>
                        <p class="font-semibold">{{ a.time_label }}</p>
                    </div>
                    <div class="min-w-0 flex-1">
                        <p class="truncate font-semibold">{{ a.name }}</p>
                        <p class="truncate text-xs text-navy-900/55">{{ a.service_label }} · {{ a.phone }}</p>
                    </div>
                    <span class="badge capitalize" :class="statusClass[a.status]">{{ a.status }}</span>
                </li>
            </ul>
            <p v-else class="mt-6 text-sm text-navy-900/50">No upcoming appointments.</p>
        </section>

        <section class="card p-6">
            <div class="flex items-center justify-between">
                <h2 class="text-xl font-medium">Latest messages</h2>
                <Link href="/admin/messages" class="text-sm font-semibold text-navy-600 hover:underline">View all</Link>
            </div>
            <ul v-if="messages.length" class="mt-4 divide-y divide-navy-900/8">
                <li v-for="m in messages" :key="m.id" class="py-3">
                    <div class="flex items-center justify-between gap-2">
                        <p class="truncate font-semibold"><span v-if="!m.read_at" class="mr-2 inline-block size-2 rounded-full bg-navy-500" />{{ m.name }}</p>
                        <p class="shrink-0 text-xs text-navy-900/50">{{ dateTime(m.created_at) }}</p>
                    </div>
                    <p class="mt-1 line-clamp-2 text-sm text-navy-900/60">{{ m.message }}</p>
                </li>
            </ul>
            <p v-else class="mt-6 text-sm text-navy-900/50">No messages yet.</p>
        </section>
    </div>
</template>
