<script setup>
import { computed, ref, watch } from 'vue';
import { Link, router, usePage } from '@inertiajs/vue3';
import Logo from '../Components/Logo.vue';
import Icon from '../Components/Icon.vue';
import FlashToast from '../Components/FlashToast.vue';

const page = usePage();
const path = computed(() => page.url.split('?')[0]);
const user = computed(() => page.props.auth.user);
const counts = computed(() => page.props.adminCounts ?? {});
const open = ref(false);

const nav = computed(() => [
    { label: 'Dashboard', href: '/admin', icon: 'grid', exact: true },
    { label: 'Products', href: '/admin/products', icon: 'box' },
    { label: 'Appointments', href: '/admin/appointments', icon: 'calendar', badge: counts.value.pendingAppointments },
    { label: 'Messages', href: '/admin/messages', icon: 'inbox', badge: counts.value.unreadMessages },
]);

const isActive = (item) => (item.exact ? path.value === item.href : path.value.startsWith(item.href));
watch(path, () => (open.value = false));

const logout = () => router.post('/admin/logout');
</script>

<template>
    <div class="min-h-screen bg-cream-100 lg:flex">
        <!-- Sidebar -->
        <aside
            class="navy-panel fixed inset-y-0 left-0 z-50 flex w-72 shrink-0 flex-col text-navy-200 transition-transform duration-500 ease-out-soft lg:sticky lg:top-0 lg:h-screen lg:translate-x-0"
            :class="open ? 'translate-x-0' : '-translate-x-full'"
        >
            <div class="flex h-24 items-center px-6"><Logo light /></div>

            <nav class="flex-1 space-y-1 px-4 py-6">
                <Link
                    v-for="item in nav"
                    :key="item.href"
                    :href="item.href"
                    class="flex items-center gap-3 rounded-xl px-4 py-3 text-sm font-semibold transition duration-300"
                    :class="isActive(item) ? 'bg-cream-100 text-navy-900 shadow-lg' : 'hover:bg-white/8 hover:text-cream-100'"
                >
                    <Icon :name="item.icon" :size="18" />
                    <span class="flex-1">{{ item.label }}</span>
                    <span v-if="item.badge" class="badge bg-navy-400 text-navy-950">{{ item.badge }}</span>
                </Link>
            </nav>

            <div class="space-y-1 border-t border-white/10 p-4">
                <a href="/" target="_blank" class="flex items-center gap-3 rounded-xl px-4 py-3 text-sm transition hover:bg-white/8 hover:text-cream-100">
                    <Icon name="external" :size="18" /> View website
                </a>
                <button class="flex w-full items-center gap-3 rounded-xl px-4 py-3 text-sm transition hover:bg-white/8 hover:text-cream-100" @click="logout">
                    <Icon name="logout" :size="18" /> Log out
                </button>
            </div>
        </aside>

        <Transition name="fade">
            <div v-if="open" class="fixed inset-0 z-40 bg-navy-950/50 backdrop-blur-sm lg:hidden" @click="open = false" />
        </Transition>

        <div class="flex min-w-0 flex-1 flex-col">
            <header class="sticky top-0 z-30 flex h-20 items-center justify-between border-b border-navy-900/10 bg-cream-100/85 px-4 backdrop-blur-xl sm:px-8">
                <button class="grid size-11 place-items-center rounded-full border border-navy-900/15 lg:hidden" aria-label="Open menu" @click="open = true">
                    <Icon name="menu" />
                </button>
                <p class="hidden text-sm text-navy-900/60 lg:block">Khalil Optik · Admin Panel</p>
                <div class="flex items-center gap-3">
                    <div class="text-right text-sm leading-tight">
                        <p class="font-semibold">{{ user?.name }}</p>
                        <p class="text-xs text-navy-900/50">{{ user?.email }}</p>
                    </div>
                    <span class="grid size-10 place-items-center rounded-full bg-navy-900 font-display text-cream-100">
                        {{ user?.name?.charAt(0) }}
                    </span>
                </div>
            </header>

            <main class="flex-1 p-4 sm:p-8">
                <Transition name="page" mode="out-in" appear>
                    <div :key="path"><slot /></div>
                </Transition>
            </main>
        </div>

        <FlashToast />
    </div>
</template>
