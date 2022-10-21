<script setup>
import { Link } from '@inertiajs/inertia-vue3';

const props = defineProps({
    project: Object,
});

</script>

<template>
    <Link :href="route('projects.show', project.id)">
        <div class="px-4 py-5 bg-white sm:p-6 shadow sm:rounded-md">
            {{ project.name }}
        </div>
    </Link>
</template>
