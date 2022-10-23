<script setup>
import { Link } from '@inertiajs/inertia-vue3';

const props = defineProps({
    project: Object,
});

</script>

<template>
    <Link :href="route('projects.show', project.id)">
        <div class="px-4 py-5 bg-white sm:p-6 shadow sm:rounded-md flex items-center">
            <div class="text-green-600 font-black mr-4">/</div>
            <div>
                {{ project.name }}
            </div>
        </div>
    </Link>
</template>
