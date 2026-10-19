<script setup>
import { computed, ref, watch } from 'vue';
import { Head, Link, router } from '@inertiajs/vue3';
import Icon from '../../Components/Icon.vue';
import ProductCard from '../../Components/ProductCard.vue';
import Pagination from '../../Components/Pagination.vue';

const props = defineProps({ products: Object, filters: Object, categories: Object, counts: Object });

const search = ref(props.filters.search ?? '');
const loading = ref(false);
const total = computed(() => Object.values(props.counts).reduce((a, b) => a + Number(b), 0));

function apply(changes = {}) {
    const params = { ...props.filters, search: search.value, ...changes };
    Object.keys(params).forEach((k) => (params[k] === '' || params[k] == null || (k === 'sort' && params[k] === 'newest')) && delete params[k]);

    router.get('/eyewear', params, {
        preserveState: true,
        preserveScroll: true,
        replace: true,
        onStart: () => (loading.value = true),
        onFinish: () => (loading.value = false),
    });
}

// Debounced live search
let t;
watch(search, () => {
    clearTimeout(t);
    t = setTimeout(() => apply(), 350);
});
</script>

<template>
    <Head title="Eyewear" />

    <section class="container-x pb-10 pt-20 lg:pt-24">
        <p v-reveal class="eyebrow">Our collection</p>
        <h1 v-reveal="100" class="mt-6 text-5xl font-medium sm:text-6xl">Find your <span class="italic text-navy-500">perfect pair.</span></h1>
        <p v-reveal="200" class="mt-6 max-w-2xl text-lg text-navy-900/70">
            Browse a selection of what we carry. Prices are in Pakistani Rupees — visit the shop or WhatsApp us to check
            availability and try them on.
        </p>
    </section>

    <section class="container-x pb-24">
        <!-- Filters -->
        <div class="sticky top-16 z-20 -mx-4 mb-10 border-y border-navy-900/10 bg-cream-100/90 px-4 py-4 backdrop-blur-xl sm:mx-0 sm:rounded-2xl sm:border">
            <div class="flex flex-col gap-4 lg:flex-row lg:items-center">
                <div class="flex flex-1 gap-2 overflow-x-auto pb-1 lg:pb-0">
                    <button
                        class="badge shrink-0 px-4 py-2 transition"
                        :class="!filters.category ? 'bg-navy-900 text-cream-100' : 'bg-navy-900/5 text-navy-900 hover:bg-navy-900/10'"
                        @click="apply({ category: null, page: null })"
                    >
                        All <span class="ml-1.5 opacity-60">{{ total }}</span>
                    </button>
                    <button
                        v-for="(label, key) in categories"
                        :key="key"
                        class="badge shrink-0 px-4 py-2 transition"
                        :class="filters.category === key ? 'bg-navy-900 text-cream-100' : 'bg-navy-900/5 text-navy-900 hover:bg-navy-900/10'"
                        @click="apply({ category: key, page: null })"
                    >
                        {{ label }} <span class="ml-1.5 opacity-60">{{ counts[key] ?? 0 }}</span>
                    </button>
                </div>

                <div class="flex gap-3">
                    <label class="relative flex-1 lg:w-64">
                        <span class="sr-only">Search</span>
                        <Icon name="search" :size="16" class="absolute left-4 top-1/2 -translate-y-1/2 text-navy-900/40" />
                        <input v-model="search" type="search" placeholder="Search eyewear…" class="input !rounded-full !py-2.5 pl-10" />
                    </label>
                    <select
                        :value="filters.sort"
                        class="input !w-auto !rounded-full !py-2.5"
                        aria-label="Sort"
                        @change="apply({ sort: $event.target.value, page: null })"
                    >
                        <option value="newest">Newest</option>
                        <option value="price_asc">Price: low to high</option>
                        <option value="price_desc">Price: high to low</option>
                        <option value="name">Name</option>
                    </select>
                </div>
            </div>
        </div>

        <!-- Grid -->
        <div class="transition-opacity duration-300" :class="loading && 'opacity-50'">
            <TransitionGroup v-if="products.data.length" tag="div" name="list" class="grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
                <ProductCard v-for="p in products.data" :key="p.id" :product="p" />
            </TransitionGroup>

            <div v-else class="card mx-auto max-w-lg p-12 text-center">
                <Icon name="search" :size="32" class="mx-auto text-navy-400" />
                <h2 class="mt-4 text-2xl font-medium">Nothing found</h2>
                <p class="mt-2 text-navy-900/60">Try another search or category — or ask us on WhatsApp, we may have it in store.</p>
                <Link href="/eyewear" class="btn-outline mt-6">Clear filters</Link>
            </div>
        </div>

        <div class="mt-14"><Pagination :links="products.links" /></div>
    </section>
</template>
