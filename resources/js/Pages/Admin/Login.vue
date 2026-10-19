<script setup>
import { Head, Link, useForm } from '@inertiajs/vue3';
import Logo from '../../Components/Logo.vue';
import FlashToast from '../../Components/FlashToast.vue';

const form = useForm({ email: '', password: '', remember: false });
const submit = () => form.post('/admin/login', { onFinish: () => form.reset('password') });
</script>

<template>
    <Head title="Admin Login" />
    <div class="grid min-h-screen lg:grid-cols-2">
        <div class="navy-panel relative hidden flex-col justify-between p-12 text-cream-100 lg:flex">
            <Logo light large/>
            <div>
                <p class="font-display text-5xl leading-tight">See Better.<br />Live Better.<br /><span class="italic text-navy-300">Trust Khalil Optik.</span></p>
                <p class="mt-6 text-navy-200">Manage eyewear, appointments and messages in one place.</p>
            </div>
            <p class="text-xs text-navy-300">© {{ new Date().getFullYear() }} Khalil Optik</p>
        </div>

        <div class="flex items-center justify-center bg-cream-100 px-6 py-16">
            <Transition name="page" appear>
                <form class="w-full max-w-sm" @submit.prevent="submit">
                    <div class="lg:hidden"><Logo /></div>
                    <h1 class="mt-10 text-4xl font-medium lg:mt-0">Welcome back</h1>
                    <p class="mt-2 text-navy-900/60">Sign in to the admin panel.</p>

                    <div class="mt-10 space-y-5">
                        <div>
                            <label class="label" for="email">Email</label>
                            <input id="email" v-model="form.email" type="email" autocomplete="username" autofocus class="input" :class="form.errors.email && 'input-error'" />
                            <p v-if="form.errors.email" class="error-text">{{ form.errors.email }}</p>
                        </div>
                        <div>
                            <label class="label" for="password">Password</label>
                            <input id="password" v-model="form.password" type="password" autocomplete="current-password" class="input" :class="form.errors.password && 'input-error'" />
                            <p v-if="form.errors.password" class="error-text">{{ form.errors.password }}</p>
                        </div>
                        <label class="flex items-center gap-2 text-sm">
                            <input v-model="form.remember" type="checkbox" class="size-4 rounded accent-navy-900" /> Keep me signed in
                        </label>
                        <button class="btn-primary w-full" :disabled="form.processing">{{ form.processing ? 'Signing in…' : 'Sign in' }}</button>
                    </div>
                    <Link href="/" class="mt-8 block text-center text-sm text-navy-900/60 hover:text-navy-900">← Back to website</Link>
                </form>
            </Transition>
        </div>
        <FlashToast />
    </div>
</template>
