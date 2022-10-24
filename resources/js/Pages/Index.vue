<script setup>
import { Head, Link } from '@inertiajs/inertia-vue3';
import ApplicationMark from '@/Components/ApplicationMark.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import SecondaryButton from '@/Components/SecondaryButton.vue';
</script>

<template>
    <Head title="Welcome" />

    <div class="flex flex-col items-top justify-center min-h-screen bg-green-400 sm:items-center sm:pt-0">
        <ApplicationMark class="w-16 h-16 mb-2" />
        <div class="text-2xl text-green-600 mb-2">
            Welcome to <span class="font-black">Libbit</span>
        </div>
        <div class="text-green-600 mb-6 text-lg">
            Save solutions to common problems with the libraries you use
        </div>
        <div>
            <Link href="/login">
                <PrimaryButton>Login</PrimaryButton>
            </Link>
            <span class="mx-2">or</span>
            <Link href="/register">
                <SecondaryButton>Register</SecondaryButton>
            </Link>
        </div>
    </div>
</template>
