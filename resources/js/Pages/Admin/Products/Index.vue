<script setup>
import { ref, watch } from 'vue';
import { Head, Link, router } from '@inertiajs/vue3';
import Icon from '../../../Components/Icon.vue';
import Pagination from '../../../Components/Pagination.vue';
import ProductArt from '../../../Components/ProductArt.vue';
import { rupees } from '../../../Composables/format';

const props = defineProps({ products: Object, filters: Object, categories: Object });

const search = ref(props.filters.search ?? '');
const category = ref(props.filters.category ?? '');

let t;
watch([search, category], () => {
    clearTimeout(t);
    t = setTimeout(() => {
        router.get('/admin/products', { search: search.value || undefined, category: category.value || undefined }, { preserveState: true, replace: true });
    }, 300);
});

function destroy(p) {
    if (confirm(`Delete "${p.name}"? This cannot be undone.`)) {
        router.delete(`/admin/products/${p.slug}`, { preserveScroll: true });
    }
}
</script>

<template>
    <Head title="Products" />

    <div class="flex flex-wrap items-end justify-between gap-4">
        <div>
            <h1 class="text-3xl font-medium">Products</h1>
            <p class="mt-1 text-navy-900/60">{{ products.total }} items in the catalogue.</p>
        </div>
        <Link href="/admin/products/create" class="btn-primary"><Icon name="plus" :size="16" /> Add product</Link>
    </div>

    <div class="mt-6 flex flex-col gap-3 sm:flex-row">
        <input v-model="search" type="search" placeholder="Search by name or brand…" class="input sm:max-w-xs" />
        <select v-model="category" class="input sm:max-w-[14rem]">
            <option value="">All categories</option>
            <option v-for="(label, key) in categories" :key="key" :value="key">{{ label }}</option>
        </select>
    </div>

    <div class="card mt-6 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm">
                <thead class="bg-navy-900/5 text-xs uppercase tracking-wider text-navy-900/60">
                    <tr>
                        <th class="px-5 py-4">Product</th>
                        <th class="px-5 py-4">Category</th>
                        <th class="px-5 py-4">Price</th>
                        <th class="px-5 py-4">Stock</th>
                        <th class="px-5 py-4">Status</th>
                        <th class="px-5 py-4 text-right">Actions</th>
                    </tr>
                </thead>
                <TransitionGroup tag="tbody" name="list" class="divide-y divide-navy-900/8">
                    <tr v-for="p in products.data" :key="p.id" class="transition hover:bg-navy-900/[0.02]">
                        <td class="px-5 py-3">
                            <div class="flex items-center gap-3">
                                <div class="size-12 shrink-0 overflow-hidden rounded-xl">
                                    <img v-if="p.image_url" :src="p.image_url" class="size-full object-cover" alt="" />
                                    <ProductArt v-else :category="p.category" />
                                </div>
                                <div>
                                    <p class="font-semibold">{{ p.name }} <Icon v-if="p.is_featured" name="star" :size="13" class="inline text-navy-500" /></p>
                                    <p class="text-xs text-navy-900/50">{{ p.brand || '—' }}</p>
                                </div>
                            </div>
                        </td>
                        <td class="px-5 py-3">{{ p.category_label }}</td>
                        <td class="px-5 py-3 whitespace-nowrap">
                            {{ rupees(p.final_price) }}
                            <span v-if="p.sale_price" class="block text-xs text-navy-900/40 line-through">{{ rupees(p.price) }}</span>
                        </td>
                        <td class="px-5 py-3"><span :class="p.stock <= 2 && 'font-semibold text-amber-700'">{{ p.stock }}</span></td>
                        <td class="px-5 py-3">
                            <span class="badge" :class="p.is_active ? 'bg-emerald-100 text-emerald-800' : 'bg-navy-900/10 text-navy-900/60'">{{ p.is_active ? 'Live' : 'Hidden' }}</span>
                        </td>
                        <td class="px-5 py-3">
                            <div class="flex justify-end gap-1">
                                <Link :href="`/admin/products/${p.slug}/edit`" class="grid size-9 place-items-center rounded-full transition hover:bg-navy-900 hover:text-cream-100" title="Edit"><Icon name="edit" :size="16" /></Link>
                                <button class="grid size-9 place-items-center rounded-full transition hover:bg-red-600 hover:text-white" title="Delete" @click="destroy(p)"><Icon name="trash" :size="16" /></button>
                            </div>
                        </td>
                    </tr>
                </TransitionGroup>
            </table>
        </div>
        <p v-if="!products.data.length" class="p-10 text-center text-navy-900/50">No products found.</p>
    </div>

    <div class="mt-6"><Pagination :links="products.links" /></div>
</template>
