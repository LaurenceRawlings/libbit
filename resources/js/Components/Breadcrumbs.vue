<script setup>
import { Link } from '@inertiajs/inertia-vue3';

const props = defineProps({
    crumbs: {
        type: Array,
        required: true,
    },
    title: {
        type: String,
        required: true,
    },
});
</script>

<template>
    <div>
        <span v-for="(crumb, i) in props.crumbs" :key="i" class="inline-flex items-center">
            <Link :href="crumb.link" class="hover:underline cursor-pointer">
                <span>{{ crumb.title }}</span>
            </Link>

            <span class="text-green-600 font-black mx-2">/</span>
        </span>
        <span class="font-semibold">{{ props.title }}</span>
    </div>
</template>
