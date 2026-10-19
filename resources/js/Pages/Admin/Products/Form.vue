<script setup>
import { computed, ref } from 'vue';
import { Head, Link, useForm } from '@inertiajs/vue3';
import Icon from '../../../Components/Icon.vue';

const props = defineProps({ product: Object, categories: Object });
const editing = computed(() => !!props.product);

const form = useForm({
    name: props.product?.name ?? '',
    category: props.product?.category ?? 'spectacles',
    brand: props.product?.brand ?? '',
    gender: props.product?.gender ?? '',
    frame_shape: props.product?.frame_shape ?? '',
    description: props.product?.description ?? '',
    price: props.product?.price ?? '',
    sale_price: props.product?.sale_price ?? '',
    stock: props.product?.stock ?? 1,
    image: null,
    remove_image: false,
    is_featured: props.product?.is_featured ?? false,
    is_active: props.product?.is_active ?? true,
});

const preview = ref(props.product?.image_url ?? null);

function pickImage(e) {
    const file = e.target.files[0];
    if (!file) return;
    form.image = file;
    form.remove_image = false;
    preview.value = URL.createObjectURL(file);
}

function clearImage() {
    form.image = null;
    form.remove_image = true;
    preview.value = null;
}

function submit() {
    if (editing.value) {
        // File uploads need POST + _method=PUT (browsers can't send files with PUT).
        form.transform((data) => ({ ...data, _method: 'put' })).post(`/admin/products/${props.product.slug}`, { forceFormData: true });
    } else {
        form.post('/admin/products', { forceFormData: true });
    }
}
</script>

<template>
    <Head :title="editing ? 'Edit product' : 'Add product'" />

    <Link href="/admin/products" class="inline-flex items-center gap-2 text-sm font-semibold text-navy-900/60 hover:text-navy-900"><Icon name="arrowLeft" :size="16" /> Products</Link>
    <h1 class="mt-3 text-3xl font-medium">{{ editing ? `Edit “${product.name}”` : 'Add a product' }}</h1>

    <form class="mt-8 grid gap-6 xl:grid-cols-[1fr_22rem]" @submit.prevent="submit">
        <div class="card space-y-5 p-6 sm:p-8">
            <div>
                <label class="label" for="name">Name *</label>
                <input id="name" v-model="form.name" class="input" :class="form.errors.name && 'input-error'" />
                <p v-if="form.errors.name" class="error-text">{{ form.errors.name }}</p>
            </div>
            <div class="grid gap-5 sm:grid-cols-2">
                <div>
                    <label class="label" for="category">Category *</label>
                    <select id="category" v-model="form.category" class="input">
                        <option v-for="(label, key) in categories" :key="key" :value="key">{{ label }}</option>
                    </select>
                </div>
                <div>
                    <label class="label" for="brand">Brand</label>
                    <input id="brand" v-model="form.brand" class="input" />
                </div>
                <div>
                    <label class="label" for="gender">For</label>
                    <select id="gender" v-model="form.gender" class="input">
                        <option value="">—</option>
                        <option value="men">Men</option>
                        <option value="women">Women</option>
                        <option value="unisex">Unisex</option>
                        <option value="kids">Kids</option>
                    </select>
                </div>
                <div>
                    <label class="label" for="shape">Frame shape</label>
                    <input id="shape" v-model="form.frame_shape" class="input" placeholder="Round, Rectangle, Aviator…" />
                </div>
            </div>
            <div>
                <label class="label" for="desc">Description</label>
                <textarea id="desc" v-model="form.description" rows="5" class="input" />
                <p v-if="form.errors.description" class="error-text">{{ form.errors.description }}</p>
            </div>
            <div class="grid gap-5 sm:grid-cols-3">
                <div>
                    <label class="label" for="price">Price (Rs) *</label>
                    <input id="price" v-model="form.price" type="number" min="0" class="input" :class="form.errors.price && 'input-error'" />
                    <p v-if="form.errors.price" class="error-text">{{ form.errors.price }}</p>
                </div>
                <div>
                    <label class="label" for="sale">Sale price (Rs)</label>
                    <input id="sale" v-model="form.sale_price" type="number" min="0" class="input" :class="form.errors.sale_price && 'input-error'" />
                    <p v-if="form.errors.sale_price" class="error-text">{{ form.errors.sale_price }}</p>
                </div>
                <div>
                    <label class="label" for="stock">Stock *</label>
                    <input id="stock" v-model="form.stock" type="number" min="0" class="input" :class="form.errors.stock && 'input-error'" />
                    <p v-if="form.errors.stock" class="error-text">{{ form.errors.stock }}</p>
                </div>
            </div>
        </div>

        <div class="space-y-6">
            <div class="card p-6">
                <p class="label">Photo</p>
                <div class="relative mt-2 aspect-square overflow-hidden rounded-2xl border-2 border-dashed border-navy-900/15 bg-navy-900/[0.02]">
                    <Transition name="fade" mode="out-in">
                        <img v-if="preview" :key="preview" :src="preview" class="size-full object-cover" alt="Preview" />
                        <label v-else for="image" class="grid size-full cursor-pointer place-items-center text-center text-sm text-navy-900/50 hover:text-navy-900">
                            <span><Icon name="plus" :size="28" class="mx-auto" />Upload JPG, PNG or WebP<br />(max 3 MB)</span>
                        </label>
                    </Transition>
                </div>
                <input id="image" type="file" accept="image/jpeg,image/png,image/webp" class="sr-only" @change="pickImage" />
                <div class="mt-3 flex gap-2">
                    <label for="image" class="btn-outline btn-sm cursor-pointer">{{ preview ? 'Change' : 'Choose' }}</label>
                    <button v-if="preview" type="button" class="btn-sm rounded-full px-4 text-xs font-semibold text-red-600 hover:bg-red-50" @click="clearImage">Remove</button>
                </div>
                <p v-if="form.errors.image" class="error-text">{{ form.errors.image }}</p>
                <div v-if="form.progress" class="mt-3 h-1.5 overflow-hidden rounded-full bg-navy-900/10">
                    <div class="h-full bg-navy-900 transition-all" :style="{ width: form.progress.percentage + '%' }" />
                </div>
            </div>

            <div class="card space-y-4 p-6">
                <label class="flex items-center justify-between gap-4 text-sm font-semibold">
                    Show on website
                    <input v-model="form.is_active" type="checkbox" class="size-5 accent-navy-900" />
                </label>
                <label class="flex items-center justify-between gap-4 text-sm font-semibold">
                    Feature on home page
                    <input v-model="form.is_featured" type="checkbox" class="size-5 accent-navy-900" />
                </label>
            </div>

            <button class="btn-primary w-full" :disabled="form.processing">
                {{ form.processing ? 'Saving…' : editing ? 'Save changes' : 'Add product' }}
            </button>
        </div>
    </form>
</template>
