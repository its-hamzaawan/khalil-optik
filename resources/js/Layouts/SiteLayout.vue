<script setup>
import { computed, onBeforeUnmount, onMounted, ref, watch } from 'vue';
import { Link, usePage } from '@inertiajs/vue3';
import Logo from '../Components/Logo.vue';
import Icon from '../Components/Icon.vue';
import FlashToast from '../Components/FlashToast.vue';
import WhatsAppFab from '../Components/WhatsAppFab.vue';

const page = usePage();
const site = computed(() => page.props.site);
const path = computed(() => page.url.split('?')[0]);

// Admin button: shows "Admin Login" to visitors, "Dashboard" once an admin is signed in.
const admin = computed(() => page.props.auth?.user);
const adminLink = computed(() => (admin.value ? { href: '/admin', label: 'Dashboard' } : { href: '/admin/login', label: 'Admin Login' }));

const nav = [
    { label: 'Home', href: '/' },
    { label: 'About', href: '/about' },
    { label: 'Services', href: '/services' },
    { label: 'Eyewear', href: '/eyewear' },
    { label: 'Contact', href: '/contact' },
];

const isActive = (href) => (href === '/' ? path.value === '/' : path.value.startsWith(href));

const scrolled = ref(false);
const menuOpen = ref(false);
const onScroll = () => (scrolled.value = window.scrollY > 12);

onMounted(() => {
    onScroll();
    window.addEventListener('scroll', onScroll, { passive: true });
});
onBeforeUnmount(() => window.removeEventListener('scroll', onScroll));

// Close the mobile menu whenever the page changes.
watch(path, () => (menuOpen.value = false));
watch(menuOpen, (open) => document.body.classList.toggle('overflow-hidden', open));

const year = new Date().getFullYear();
</script>

<template>
    <div class="flex min-h-screen flex-col">
        <!-- Top info bar -->
        <div class="hidden bg-navy-950 text-xs text-navy-200 md:block">
            <div class="container-x flex h-9 items-center justify-between">
                <span class="flex items-center gap-2"><Icon name="pin" :size="14" /> Main Bazar, Sarwar Shaheed Chowk, Jauharabad</span>
                <span class="flex items-center gap-6">
                    <a :href="site.contact.phone_href" class="flex items-center gap-2 transition hover:text-cream-100"><Icon name="phone" :size="14" /> {{ site.contact.phone }}</a>
                    <a :href="site.contact.whatsapp_href" target="_blank" rel="noopener" class="flex items-center gap-2 transition hover:text-cream-100"><Icon name="whatsapp" :size="14" /> {{ site.contact.whatsapp }}</a>
                </span>
            </div>
        </div>

        <!-- Header -->
        <header
            class="sticky top-0 z-40 border-b transition-all duration-500 ease-out-soft"
            :class="scrolled ? 'border-navy-900/10 bg-cream-100/85 shadow-lg shadow-navy-900/5 backdrop-blur-xl' : 'border-transparent bg-cream-100'"
        >
            <div class="container-x flex items-center justify-between transition-all duration-500" :class="scrolled ? 'h-20' : 'h-24'">
                <Link href="/" aria-label="Khalil Optik home"><Logo /></Link>

                <nav class="hidden items-center gap-1 lg:flex">
                    <Link
                        v-for="item in nav"
                        :key="item.href"
                        :href="item.href"
                        class="group relative px-4 py-2 text-sm font-semibold transition"
                        :class="isActive(item.href) ? 'text-navy-900' : 'text-navy-900/60 hover:text-navy-900'"
                    >
                        {{ item.label }}
                        <span
                            class="absolute inset-x-4 -bottom-0.5 h-0.5 origin-left rounded-full bg-navy-900 transition-transform duration-500 ease-out-soft"
                            :class="isActive(item.href) ? 'scale-x-100' : 'scale-x-0 group-hover:scale-x-100'"
                        />
                    </Link>
                </nav>

                <div class="flex items-center gap-3">
                    <Link :href="adminLink.href" class="btn-outline hidden md:inline-flex" :title="adminLink.label">
                        <Icon name="shield" :size="16" /> {{ adminLink.label }}
                    </Link>
                    <Link href="/appointment" class="btn-primary hidden sm:inline-flex">
                        <Icon name="calendar" :size="16" /> Book Eye Check-up
                    </Link>
                    <button
                        class="grid size-11 place-items-center rounded-full border border-navy-900/15 lg:hidden"
                        :aria-expanded="menuOpen"
                        aria-label="Toggle menu"
                        @click="menuOpen = !menuOpen"
                    >
                        <Icon :name="menuOpen ? 'x' : 'menu'" />
                    </button>
                </div>
            </div>

            <!-- Mobile menu -->
            <Transition name="slide-down">
                <div v-if="menuOpen" class="border-t border-navy-900/10 bg-cream-100 lg:hidden">
                    <nav class="container-x flex flex-col py-4">
                        <Link
                            v-for="(item, i) in nav"
                            :key="item.href"
                            :href="item.href"
                            class="flex items-center justify-between border-b border-navy-900/8 py-4 font-display text-2xl"
                            :class="isActive(item.href) ? 'text-navy-900' : 'text-navy-900/60'"
                            :style="{ transitionDelay: `${i * 40}ms` }"
                        >
                            {{ item.label }} <Icon name="arrow" :size="18" />
                        </Link>
                        <Link href="/appointment" class="btn-primary mt-6">Book Eye Check-up</Link>
                        <Link :href="adminLink.href" class="btn-outline mt-3"><Icon name="shield" :size="16" /> {{ adminLink.label }}</Link>
                    </nav>
                </div>
            </Transition>
        </header>

        <!-- Page content with animated transitions -->
        <main class="flex-1">
            <Transition name="page" mode="out-in" appear>
                <div :key="path">
                    <slot />
                </div>
            </Transition>
        </main>

        <!-- Footer -->
        <footer class="navy-panel text-navy-200">
            <div class="container-x grid gap-12 py-16 md:grid-cols-2 lg:grid-cols-4">
                <div class="lg:col-span-1">
                    <Logo light large/>
                    <p class="mt-6 text-sm leading-relaxed">
                        Serving the people of Jauharabad and nearby areas with care, honesty and dedication since 1960.
                    </p>
                    <p class="mt-4 text-xs text-navy-300">Registered with the Punjab Healthcare Commission (PHC).</p>
                </div>

                <div>
                    <h3 class="text-lg text-cream-100">Explore</h3>
                    <ul class="mt-5 space-y-3 text-sm">
                        <li v-for="item in nav" :key="item.href">
                            <Link :href="item.href" class="transition hover:pl-1 hover:text-cream-100">{{ item.label }}</Link>
                        </li>
                        <li><Link href="/appointment" class="transition hover:pl-1 hover:text-cream-100">Book an Appointment</Link></li>
                        <li><Link :href="adminLink.href" class="transition hover:pl-1 hover:text-cream-100">{{ adminLink.label }}</Link></li>
                    </ul>
                </div>

                <div>
                    <h3 class="text-lg text-cream-100">Visit Us</h3>
                    <ul class="mt-5 space-y-4 text-sm">
                        <li class="flex gap-3"><Icon name="pin" :size="18" class="mt-0.5 shrink-0" /> {{ site.contact.address }}</li>
                        <li><a :href="site.contact.phone_href" class="flex gap-3 transition hover:text-cream-100"><Icon name="phone" :size="18" /> {{ site.contact.phone }}</a></li>
                        <li><a :href="site.contact.whatsapp_href" target="_blank" rel="noopener" class="flex gap-3 transition hover:text-cream-100"><Icon name="whatsapp" :size="18" /> {{ site.contact.whatsapp }}</a></li>
                    </ul>
                </div>

                <div>
                    <h3 class="text-lg text-cream-100">Opening Hours</h3>
                    <ul class="mt-5 space-y-3 text-sm">
                        <li v-for="h in site.hours" :key="h.days" class="flex justify-between gap-4 border-b border-white/10 pb-3">
                            <span>{{ h.days }}</span><span class="text-cream-100">{{ h.time }}</span>
                        </li>
                    </ul>
                </div>
            </div>

            <div class="border-t border-white/10">
                <div class="container-x flex flex-col items-center justify-between gap-3 py-6 text-xs text-navy-300 sm:flex-row">
                    <p>&copy; {{ year }} Khalil Optik. All rights reserved.</p>
                    <p class="font-display italic">See Better. Live Better. Trust Khalil Optik.</p>
                </div>
            </div>
        </footer>

        <WhatsAppFab />
        <FlashToast />
    </div>
</template>
