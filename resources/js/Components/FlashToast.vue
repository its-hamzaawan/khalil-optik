<script setup>
import { ref, watch } from 'vue';
import { usePage } from '@inertiajs/vue3';
import Icon from './Icon.vue';

const page = usePage();
const toast = ref(null);
let timer;

watch(
    () => page.props.flash,
    (flash) => {
        const message = flash?.success || flash?.error;
        if (!message) return;
        toast.value = { message, type: flash.success ? 'success' : 'error', id: Date.now() };
        clearTimeout(timer);
        timer = setTimeout(() => (toast.value = null), 4500);
    },
    { immediate: true, deep: true },
);
</script>

<template>
    <div class="pointer-events-none fixed inset-x-0 bottom-6 z-[60] flex justify-center px-4">
        <Transition name="toast">
            <div
                v-if="toast"
                :key="toast.id"
                role="status"
                class="pointer-events-auto flex max-w-md items-center gap-3 rounded-2xl px-5 py-4 text-sm font-medium shadow-2xl"
                :class="toast.type === 'success' ? 'bg-navy-900 text-cream-100' : 'bg-red-700 text-white'"
            >
                <Icon :name="toast.type === 'success' ? 'check' : 'x'" :size="18" />
                <span>{{ toast.message }}</span>
                <button class="ml-2 opacity-60 transition hover:opacity-100" aria-label="Close" @click="toast = null">
                    <Icon name="x" :size="16" />
                </button>
            </div>
        </Transition>
    </div>
</template>
 