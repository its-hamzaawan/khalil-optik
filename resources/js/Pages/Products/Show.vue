<script setup>
import { computed } from 'vue';
import { Head, Link, usePage } from '@inertiajs/vue3';
import Icon from '../../Components/Icon.vue';
import ProductArt from '../../Components/ProductArt.vue';
import ProductCard from '../../Components/ProductCard.vue';
import { rupees } from '../../Composables/format';

const props = defineProps({ product: Object, related: Array });
const site = usePage().props.site;

const whatsappLink = computed(
    () =>
        `${site.contact.whatsapp_href}?text=${encodeURIComponent(
            `Assalam-o-Alaikum! I'm interested in "${props.product.name}" (${rupees(props.product.final_price)}). Is it available?`,
        )}`,
);

const details = computed(() =>
    [
        ['Category', props.product.category_label],
        ['Brand', props.product.brand],
        ['Frame shape', props.product.frame_shape],
        ['For', props.product.gender && props.product.gender[0].toUpperCase() + props.product.gender.slice(1)],
        ['Availability', props.product.in_stock ? 'In stock' : 'Ask us — we can arrange it'],
    ].filter(([, v]) => v),
);
</script>

<template>
    <Head :title="product.name" />

    <section class="container-x py-12 lg:py-20">
        <Link href="/eyewear" class="inline-flex items-center gap-2 text-sm font-semibold text-navy-900/60 transition hover:gap-3 hover:text-navy-900">
            <Icon name="arrowLeft" :size="16" /> Back to eyewear
        </Link>

        <div class="mt-8 grid gap-12 lg:grid-cols-2">
            <div v-reveal class="aspect-square overflow-hidden rounded-[2rem] border border-navy-900/8 bg-navy-100/60">
                <img v-if="product.image_url" :src="product.image_url" :alt="product.name" class="size-full object-cover" />
                <ProductArt v-else :category="product.category" />
            </div>

            <div class="flex flex-col justify-center">
                <p v-reveal class="eyebrow">{{ product.category_label }}</p>
                <h1 v-reveal="80" class="mt-4 text-4xl font-medium sm:text-5xl">{{ product.name }}</h1>

                <p v-reveal="160" class="mt-6 text-3xl font-bold">
                    {{ rupees(product.final_price) }}
                    <span v-if="product.sale_price" class="ml-2 text-lg font-medium text-navy-900/40 line-through">{{ rupees(product.price) }}</span>
                </p>

                <p v-if="product.description" v-reveal="220" class="mt-6 whitespace-pre-line leading-relaxed text-navy-900/70">{{ product.description }}</p>

                <dl v-reveal="280" class="mt-8 divide-y divide-navy-900/10 border-y border-navy-900/10">
                    <div v-for="[k, v] in details" :key="k" class="flex justify-between py-3 text-sm">
                        <dt class="text-navy-900/55">{{ k }}</dt>
                        <dd class="font-semibold">{{ v }}</dd>
                    </div>
                </dl>

                <div v-reveal="340" class="mt-8 flex flex-wrap gap-3">
                    <a :href="whatsappLink" target="_blank" rel="noopener" class="btn-primary"><Icon name="whatsapp" :size="16" /> Ask on WhatsApp</a>
                    <a :href="site.contact.phone_href" class="btn-outline"><Icon name="phone" :size="16" /> Call the shop</a>
                </div>
                <p class="mt-4 text-xs text-navy-900/50">Need a prescription lens? <Link href="/appointment" class="font-semibold underline underline-offset-4">Book an eye check-up</Link> first.</p>
            </div>
        </div>
    </section>

    <section v-if="related.length" class="container-x pb-24">
        <h2 v-reveal class="text-3xl font-medium">You may also like</h2>
        <div class="mt-8 grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
            <div v-for="(p, i) in related" :key="p.id" v-reveal="i * 100"><ProductCard :product="p" /></div>
        </div>
    </section>
</template>
