<script setup>
import { Link } from '@inertiajs/vue3';
import { rupees } from '../Composables/format';
import ProductArt from './ProductArt.vue';

defineProps({ product: { type: Object, required: true } });
</script>

<template>
    <Link
        :href="`/eyewear/${product.slug}`"
        class="group flex h-full flex-col overflow-hidden rounded-3xl border border-navy-900/8 bg-cream-50 transition duration-500 ease-out-soft hover:-translate-y-1.5 hover:shadow-2xl hover:shadow-navy-900/10"
    >
        <div class="relative aspect-[4/3] overflow-hidden bg-navy-100/60">
            <img
                v-if="product.image_url"
                :src="product.image_url"
                :alt="product.name"
                loading="lazy"
                class="size-full object-cover transition duration-700 ease-out-soft group-hover:scale-105"
            />
            <ProductArt v-else :category="product.category" class="transition duration-700 ease-out-soft group-hover:scale-105" />

            <span v-if="product.sale_price" class="badge absolute left-4 top-4 bg-navy-900 text-cream-100">Offer</span>
            <span v-if="!product.in_stock" class="badge absolute right-4 top-4 bg-cream-100 text-navy-900">Ask for availability</span>
        </div>

        <div class="flex flex-1 flex-col p-6">
            <p class="text-xs font-semibold uppercase tracking-[0.18em] text-navy-500">{{ product.category_label }}</p>
            <h3 class="mt-2 font-display text-xl font-medium text-navy-900">{{ product.name }}</h3>
            <p v-if="product.frame_shape || product.gender" class="mt-1 text-sm capitalize text-navy-900/55">
                {{ [product.frame_shape, product.gender].filter(Boolean).join(' · ') }}
            </p>

            <div class="mt-auto flex items-end justify-between pt-5">
                <p class="text-lg font-bold text-navy-900">
                    {{ rupees(product.final_price) }}
                    <span v-if="product.sale_price" class="ml-1 text-sm font-medium text-navy-900/40 line-through">
                        {{ rupees(product.price) }}
                    </span>
                </p>
                <span
                    class="grid size-10 place-items-center rounded-full border border-navy-900/15 text-navy-900 transition duration-300 group-hover:border-navy-900 group-hover:bg-navy-900 group-hover:text-cream-100"
                >
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M5 12h14M13 6l6 6-6 6" /></svg>
                </span>
            </div>
        </div>
    </Link>
</template>
