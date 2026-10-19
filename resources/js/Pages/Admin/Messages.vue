<script setup>
import { ref } from 'vue';
import { Head, router } from '@inertiajs/vue3';
import Icon from '../../Components/Icon.vue';
import Pagination from '../../Components/Pagination.vue';
import { dateTime } from '../../Composables/format';

defineProps({ messages: Object });
const open = ref(null);

function toggle(m) {
    open.value = open.value === m.id ? null : m.id;
    if (open.value === m.id && !m.read_at) router.patch(`/admin/messages/${m.id}/read`, {}, { preserveScroll: true, preserveState: true });
}
const markUnread = (m) => router.patch(`/admin/messages/${m.id}/read`, {}, { preserveScroll: true });
const destroy = (m) => confirm('Delete this message?') && router.delete(`/admin/messages/${m.id}`, { preserveScroll: true });
</script>

<template>
    <Head title="Messages" />
    <h1 class="text-3xl font-medium">Messages</h1>
    <p class="mt-1 text-navy-900/60">Enquiries sent from the contact page.</p>

    <TransitionGroup tag="div" name="list" class="mt-6 space-y-3">
        <div v-for="m in messages.data" :key="m.id" class="card overflow-hidden" :class="!m.read_at && 'border-navy-500/40'">
            <button class="flex w-full items-center gap-4 p-5 text-left" @click="toggle(m)">
                <span class="size-2.5 shrink-0 rounded-full" :class="m.read_at ? 'bg-transparent' : 'bg-navy-500'" />
                <div class="min-w-0 flex-1">
                    <p class="truncate" :class="!m.read_at && 'font-bold'">{{ m.name }} <span v-if="m.subject" class="font-normal text-navy-900/50">— {{ m.subject }}</span></p>
                    <p class="truncate text-sm text-navy-900/55">{{ m.message }}</p>
                </div>
                <span class="shrink-0 text-xs text-navy-900/45">{{ dateTime(m.created_at) }}</span>
            </button>
            <Transition name="slide-down">
                <div v-if="open === m.id" class="border-t border-navy-900/8 bg-navy-900/[0.02] p-5">
                    <p class="whitespace-pre-line leading-relaxed">{{ m.message }}</p>
                    <div class="mt-5 flex flex-wrap items-center gap-2">
                        <a v-if="m.phone" :href="`tel:${m.phone}`" class="btn-outline btn-sm"><Icon name="phone" :size="14" /> {{ m.phone }}</a>
                        <a v-if="m.email" :href="`mailto:${m.email}`" class="btn-outline btn-sm"><Icon name="mail" :size="14" /> {{ m.email }}</a>
                        <span class="flex-1" />
                        <button class="btn-sm rounded-full px-4 text-xs font-semibold hover:bg-navy-900/5" @click="markUnread(m)">Mark {{ m.read_at ? 'unread' : 'read' }}</button>
                        <button class="btn-sm rounded-full px-4 text-xs font-semibold text-red-600 hover:bg-red-50" @click="destroy(m)">Delete</button>
                    </div>
                </div>
            </Transition>
        </div>
    </TransitionGroup>
    <p v-if="!messages.data.length" class="card mt-6 p-10 text-center text-navy-900/50">No messages yet.</p>
    <div class="mt-6"><Pagination :links="messages.links" /></div>
</template>
