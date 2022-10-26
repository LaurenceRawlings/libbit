<script setup>
import { Head, Link } from '@inertiajs/inertia-vue3';
import ApplicationMark from '@/Components/ApplicationMark.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import SecondaryButton from '@/Components/SecondaryButton.vue';
</script>

<template>
    <Head title="Welcome" />

    <div class="grid place-items-center min-h-screen bg-gradient-to-br from-green-500 to-green-600 sm:pt-0">
        <div class="grid place-items-center bg-white shadow-xl p-8 sm:p-16 sm:rounded-lg">
            <ApplicationMark class="w-16 h-16 mb-2" />
            <div class="text-2xl text-green-600 mb-2 text-center">
                Welcome to <span class="font-black">Libbit</span>
            </div>
            <div class="text-green-600 mb-6 text-lg text-center">
                Save solutions to common problems with the libraries you use!
            </div>
            <div>
                <Link href="/login">
                    <PrimaryButton>Login</PrimaryButton>
                </Link>
                <span class="mx-4">or</span>
                <Link href="/register">
                    <SecondaryButton>Register</SecondaryButton>
                </Link>
            </div>
        </div>
    </div>
</template>
